<?php

namespace App\Jobs;

use App\Models\Website;
use App\Models\SslCertificate;
use App\Notifications\SSLCertificateExpiringNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class CheckSSLCertificate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $website;

    public function __construct(Website $website)
    {
        $this->website = $website;
    }

    public function handle()
    {
        if (!$this->website->verify_ssl) {
            return;
        }

        $domain = parse_url($this->website->url, PHP_URL_HOST);
        $port = parse_url($this->website->url, PHP_URL_PORT) ?: 443;

        try {
            $sslInfo = $this->getSSLCertificateInfo($domain, $port);

            if ($sslInfo) {
                $this->saveCertificateInfo($sslInfo);
                $this->checkExpiryNotification($sslInfo);
            }
        } catch (\Exception $e) {
            \Log::error("SSL check failed for {$domain}: " . $e->getMessage());
        }
    }

    private function getSSLCertificateInfo($domain, $port)
    {
        $context = stream_context_create([
            'ssl' => [
                'capture_peer_cert' => true,
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ]);

        $socket = @stream_socket_client(
            "ssl://{$domain}:{$port}",
            $errno,
            $errstr,
            30,
            STREAM_CLIENT_CONNECT,
            $context
        );

        if (!$socket) {
            throw new \Exception("Failed to connect: {$errstr} ({$errno})");
        }

        $cert = stream_context_get_params($socket)['options']['ssl']['peer_certificate'];
        fclose($socket);

        if (!$cert) {
            throw new \Exception("No certificate found");
        }

        $certInfo = openssl_x509_parse($cert);
        $certDetails = openssl_x509_parse($cert, false);

        if (!$certInfo) {
            throw new \Exception("Failed to parse certificate");
        }

        $validFrom = Carbon::createFromTimestamp($certInfo['validFrom_time_t']);
        $validTo = Carbon::createFromTimestamp($certInfo['validTo_time_t']);
        $daysUntilExpiry = now()->diffInDays($validTo, false);

        // Get Subject Alternative Names
        $sanDomains = [];
        if (isset($certInfo['extensions']['subjectAltName'])) {
            $sanString = $certInfo['extensions']['subjectAltName'];
            $sanEntries = explode(',', $sanString);
            foreach ($sanEntries as $entry) {
                if (strpos(trim($entry), 'DNS:') === 0) {
                    $sanDomains[] = substr(trim($entry), 4);
                }
            }
        }

        return [
            'domain' => $domain,
            'issuer' => $certInfo['issuer']['CN'] ?? 'Unknown',
            'subject' => $certInfo['subject']['CN'] ?? 'Unknown',
            'fingerprint' => openssl_x509_fingerprint($cert),
            'serial_number' => $certInfo['serialNumber'] ?? '',
            'signature_algorithm' => $certInfo['signatureTypeSN'] ?? '',
            'san_domains' => $sanDomains,
            'valid_from' => $validFrom,
            'valid_to' => $validTo,
            'days_until_expiry' => $daysUntilExpiry,
            'is_valid' => $validTo->isFuture() && $validFrom->isPast(),
            'is_self_signed' => $certInfo['issuer']['CN'] === $certInfo['subject']['CN'],
            'is_expired' => $validTo->isPast(),
            'validation_errors' => $this->validateCertificate($certInfo, $domain),
        ];
    }

    private function validateCertificate($certInfo, $domain)
    {
        $errors = [];

        // Check if certificate covers the domain
        $subjectCN = $certInfo['subject']['CN'] ?? '';
        $sanDomains = [];

        if (isset($certInfo['extensions']['subjectAltName'])) {
            $sanString = $certInfo['extensions']['subjectAltName'];
            $sanEntries = explode(',', $sanString);
            foreach ($sanEntries as $entry) {
                if (strpos(trim($entry), 'DNS:') === 0) {
                    $sanDomains[] = substr(trim($entry), 4);
                }
            }
        }

        $coversDomain = false;
        $domainsToCheck = array_merge([$subjectCN], $sanDomains);

        foreach ($domainsToCheck as $certDomain) {
            if ($this->matchesDomain($domain, $certDomain)) {
                $coversDomain = true;
                break;
            }
        }

        if (!$coversDomain) {
            $errors[] = "Certificate does not cover domain {$domain}";
        }

        // Check expiry
        $validTo = Carbon::createFromTimestamp($certInfo['validTo_time_t']);
        if ($validTo->isPast()) {
            $errors[] = "Certificate has expired";
        } elseif ($validTo->diffInDays() <= 30) {
            $errors[] = "Certificate expires soon";
        }

        return empty($errors) ? null : implode('; ', $errors);
    }

    private function matchesDomain($domain, $certDomain)
    {
        if ($domain === $certDomain) {
            return true;
        }

        // Handle wildcard certificates
        if (strpos($certDomain, '*.') === 0) {
            $wildcardDomain = substr($certDomain, 2);
            $domainParts = explode('.', $domain);
            if (count($domainParts) > 1) {
                array_shift($domainParts);
                $parentDomain = implode('.', $domainParts);
                return $parentDomain === $wildcardDomain;
            }
        }

        return false;
    }

    private function saveCertificateInfo($sslInfo)
    {
        SslCertificate::updateOrCreate(
            [
                'website_id' => $this->website->id,
                'domain' => $sslInfo['domain'],
            ],
            array_merge($sslInfo, [
                'last_checked_at' => now(),
            ])
        );
    }

    private function checkExpiryNotification($sslInfo)
    {
        $daysUntilExpiry = $sslInfo['days_until_expiry'];
        $reminderDays = $this->website->ssl_expiry_reminder_days;

        if ($daysUntilExpiry <= $reminderDays && $daysUntilExpiry > 0) {
            $this->website->user->notify(
                new SSLCertificateExpiringNotification($this->website, $sslInfo)
            );
        }
    }
}
