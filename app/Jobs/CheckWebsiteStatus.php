<?php

namespace App\Jobs;

use App\Models\Website;
use App\Models\MonitoringResult;
use App\Notifications\WebsiteDownNotification;
use App\Notifications\WebsiteUpNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class CheckWebsiteStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $website;

    public function __construct(Website $website)
    {
        $this->website = $website;
    }

    public function handle()
    {
        if (!$this->website->is_active) {
            return;
        }

        $startTime = microtime(true);
        $result = [
            'website_id' => $this->website->id,
            'status' => 'unknown',
            'response_time' => null,
            'response_code' => null,
            'response_headers' => null,
            'error_message' => null,
            'ip_address' => null,
            'checked_at' => now(),
            'location' => 'server'
        ];

        try {
            $response = Http::timeout($this->website->timeout)
                           ->withHeaders($this->parseHeaders())
                           ->get($this->website->url);

            $endTime = microtime(true);
            $responseTime = round(($endTime - $startTime) * 1000);

            $result['response_time'] = $responseTime;
            $result['response_code'] = $response->status();
            $result['response_headers'] = json_encode($response->headers());

            // Check if status code is expected
            $expectedCodes = explode(',', $this->website->expected_status_codes);
            $expectedCodes = array_map('trim', $expectedCodes);

            if (in_array($response->status(), $expectedCodes)) {
                // Check content if specified
                if ($this->website->expected_content) {
                    $body = $response->body();
                    if (strpos($body, $this->website->expected_content) !== false) {
                        $result['status'] = 'up';
                    } else {
                        $result['status'] = 'down';
                        $result['error_message'] = 'Expected content not found';
                    }
                } else {
                    $result['status'] = 'up';
                }
            } else {
                $result['status'] = 'down';
                $result['error_message'] = "HTTP {$response->status()} received";
            }

            // Get IP address
            try {
                $result['ip_address'] = gethostbyname(parse_url($this->website->url, PHP_URL_HOST));
            } catch (\Exception $e) {
                // Ignore IP resolution errors
            }

        } catch (\Illuminate\Http\Client\RequestException $e) {
            $result['status'] = 'timeout';
            $result['error_message'] = 'Request timeout';
        } catch (\Exception $e) {
            $result['status'] = 'down';
            $result['error_message'] = $e->getMessage();
        }

        // Save monitoring result
        $monitoringResult = MonitoringResult::create($result);

        // Update website status
        $previousStatus = $this->website->current_status;
        $this->website->updateStatus(
            $result['status'],
            $result['response_time'],
            $result['error_message']
        );

        // Send notifications if status changed
        $this->handleStatusChangeNotifications($previousStatus, $result['status']);

        // Check SSL if enabled
        if ($this->website->verify_ssl && $result['status'] === 'up') {
            CheckSSLCertificate::dispatch($this->website);
        }

        // Check DNS if enabled
        if ($this->website->monitor_dns) {
            CheckDNSRecords::dispatch($this->website);
        }
    }

    private function parseHeaders()
    {
        $headers = [];

        if ($this->website->request_headers) {
            $headerLines = explode("\n", $this->website->request_headers);
            foreach ($headerLines as $line) {
                if (strpos($line, ':') !== false) {
                    list($key, $value) = explode(':', $line, 2);
                    $headers[trim($key)] = trim($value);
                }
            }
        }

        // Add default User-Agent if not specified
        if (!isset($headers['User-Agent'])) {
            $headers['User-Agent'] = 'UptimeMonitor/1.0';
        }

        return $headers;
    }

    private function handleStatusChangeNotifications($previousStatus, $currentStatus)
    {
        $user = $this->website->user;

        // Website went down
        if ($previousStatus === 'up' && $currentStatus !== 'up') {
            $user->notify(new WebsiteDownNotification($this->website));
        }

        // Website came back up
        if ($previousStatus !== 'up' && $currentStatus === 'up') {
            $user->notify(new WebsiteUpNotification($this->website));
        }
    }

    public function failed(\Exception $exception)
    {
        // Handle job failure
        MonitoringResult::create([
            'website_id' => $this->website->id,
            'status' => 'down',
            'error_message' => 'Job failed: ' . $exception->getMessage(),
            'checked_at' => now(),
            'location' => 'server'
        ]);

        $this->website->updateStatus('down', null, 'Job failed: ' . $exception->getMessage());
    }
}
