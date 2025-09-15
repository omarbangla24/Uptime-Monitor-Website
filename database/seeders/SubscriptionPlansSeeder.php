<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlansSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Perfect for getting started with website monitoring',
                'price' => 0.00,
                'interval' => 'monthly',
                'websites_limit' => 3,
                'checks_per_minute' => 5,
                'ssl_monitoring' => false,
                'dns_monitoring' => false,
                'domain_expiry_monitoring' => false,
                'email_alerts' => true,
                'sms_alerts' => false,
                'webhook_alerts' => false,
                'data_retention_days' => 7,
                'api_access' => false,
                'white_label' => false,
                'team_members' => 1,
                'sort_order' => 1,
            ],
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'description' => 'Essential monitoring features for small websites',
                'price' => 9.99,
                'interval' => 'monthly',
                'websites_limit' => 10,
                'checks_per_minute' => 1,
                'ssl_monitoring' => true,
                'dns_monitoring' => true,
                'domain_expiry_monitoring' => false,
                'email_alerts' => true,
                'sms_alerts' => false,
                'webhook_alerts' => true,
                'data_retention_days' => 30,
                'api_access' => false,
                'white_label' => false,
                'team_members' => 2,
                'sort_order' => 2,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'description' => 'Advanced monitoring for professional websites',
                'price' => 29.99,
                'interval' => 'monthly',
                'websites_limit' => 50,
                'checks_per_minute' => 1,
                'ssl_monitoring' => true,
                'dns_monitoring' => true,
                'domain_expiry_monitoring' => true,
                'email_alerts' => true,
                'sms_alerts' => true,
                'webhook_alerts' => true,
                'data_retention_days' => 90,
                'api_access' => true,
                'white_label' => false,
                'team_members' => 5,
                'sort_order' => 3,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'Complete monitoring solution for large organizations',
                'price' => 99.99,
                'interval' => 'monthly',
                'websites_limit' => 0, // Unlimited
                'checks_per_minute' => 1,
                'ssl_monitoring' => true,
                'dns_monitoring' => true,
                'domain_expiry_monitoring' => true,
                'email_alerts' => true,
                'sms_alerts' => true,
                'webhook_alerts' => true,
                'data_retention_days' => 365,
                'api_access' => true,
                'white_label' => true,
                'team_members' => 0, // Unlimited
                'sort_order' => 4,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
}
