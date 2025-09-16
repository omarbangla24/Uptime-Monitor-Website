<?php

namespace App\Jobs;

use App\Models\Website;
use App\Models\DnsRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckDNSRecords implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $website;

    public function __construct(Website $website)
    {
        $this->website = $website;
    }

    public function handle()
    {
        if (!$this->website->monitor_dns) {
            return;
        }

        $domain = $this->website->domain;
        $recordTypes = ['A', 'AAAA', 'CNAME', 'MX', 'NS', 'TXT'];

        foreach ($recordTypes as $type) {
            try {
                $records = $this->getDNSRecords($domain, $type);
                $this->saveDNSRecords($records, $type);
            } catch (\Exception $e) {
                \Log::error("DNS check failed for {$domain} ({$type}): " . $e->getMessage());
            }
        }
    }

    private function getDNSRecords($domain, $type)
    {
        $records = [];

        switch ($type) {
            case 'A':
                $results = @dns_get_record($domain, DNS_A);
                break;
            case 'AAAA':
                $results = @dns_get_record($domain, DNS_AAAA);
                break;
            case 'CNAME':
                $results = @dns_get_record($domain, DNS_CNAME);
                break;
            case 'MX':
                $results = @dns_get_record($domain, DNS_MX);
                break;
            case 'NS':
                $results = @dns_get_record($domain, DNS_NS);
                break;
            case 'TXT':
                $results = @dns_get_record($domain, DNS_TXT);
                break;
            default:
                $results = false;
        }

        if ($results === false) {
            return [];
        }

        foreach ($results as $record) {
            $value = $this->extractRecordValue($record, $type);
            if ($value) {
                $records[] = [
                    'name' => $record['host'] ?? $domain,
                    'value' => $value,
                    'ttl' => $record['ttl'] ?? null,
                    'priority' => $record['pri'] ?? null,
                ];
            }
        }

        return $records;
    }

    private function extractRecordValue($record, $type)
    {
        switch ($type) {
            case 'A':
                return $record['ip'] ?? null;
            case 'AAAA':
                return $record['ipv6'] ?? null;
            case 'CNAME':
                return $record['target'] ?? null;
            case 'MX':
                return $record['target'] ?? null;
            case 'NS':
                return $record['target'] ?? null;
            case 'TXT':
                return $record['txt'] ?? null;
            default:
                return null;
        }
    }

    private function saveDNSRecords($records, $type)
    {
        // Delete existing records of this type
        DnsRecord::where('website_id', $this->website->id)
                 ->where('record_type', $type)
                 ->delete();

        // Save new records
        foreach ($records as $record) {
            DnsRecord::create([
                'website_id' => $this->website->id,
                'domain' => $this->website->domain,
                'record_type' => $type,
                'name' => $record['name'],
                'value' => $record['value'],
                'ttl' => $record['ttl'],
                'priority' => $record['priority'],
                'last_checked_at' => now(),
            ]);
        }
    }
}
