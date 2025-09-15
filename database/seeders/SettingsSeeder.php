<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Site Settings
            ['key' => 'site_name', 'value' => 'UptimeMonitor', 'type' => 'string', 'group' => 'general', 'is_public' => true],
            ['key' => 'site_description', 'value' => 'Professional website monitoring service', 'type' => 'string', 'group' => 'general', 'is_public' => true],
            ['key' => 'site_keywords', 'value' => 'uptime monitoring, website monitoring, ssl monitoring', 'type' => 'string', 'group' => 'general', 'is_public' => true],
            ['key' => 'contact_email', 'value' => 'hello@uptimemonitor.com', 'type' => 'string', 'group' => 'general', 'is_public' => true],
            ['key' => 'support_email', 'value' => 'support@uptimemonitor.com', 'type' => 'string', 'group' => 'general', 'is_public' => false],

            // Monitoring Settings
            ['key' => 'default_check_interval', 'value' => '5', 'type' => 'integer', 'group' => 'monitoring', 'is_public' => false],
            ['key' => 'max_timeout', 'value' => '30', 'type' => 'integer', 'group' => 'monitoring', 'is_public' => false],
            ['key' => 'max_redirects', 'value' => '5', 'type' => 'integer', 'group' => 'monitoring', 'is_public' => false],

            // Email Settings
            ['key' => 'email_notifications_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'email', 'is_public' => false],
            ['key' => 'smtp_host', 'value' => 'localhost', 'type' => 'string', 'group' => 'email', 'is_public' => false],
            ['key' => 'smtp_port', 'value' => '587', 'type' => 'integer', 'group' => 'email', 'is_public' => false],

            // API Settings
            ['key' => 'api_rate_limit', 'value' => '1000', 'type' => 'integer', 'group' => 'api', 'is_public' => false],
            ['key' => 'api_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'api', 'is_public' => false],

            // Social Media
            ['key' => 'twitter_url', 'value' => 'https://twitter.com/uptimemonitor', 'type' => 'string', 'group' => 'social', 'is_public' => true],
            ['key' => 'github_url', 'value' => 'https://github.com/uptimemonitor', 'type' => 'string', 'group' => 'social', 'is_public' => true],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
