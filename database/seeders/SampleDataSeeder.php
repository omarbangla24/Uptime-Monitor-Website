<?php

namespace Database\Seeders;

use App\Models\Website;
use App\Models\MonitoringResult;
use App\Models\User;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample public websites for the landing page
        $sampleSites = [
            ['name' => 'Google', 'url' => 'https://google.com', 'domain' => 'google.com'],
            ['name' => 'GitHub', 'url' => 'https://github.com', 'domain' => 'github.com'],
            ['name' => 'Stack Overflow', 'url' => 'https://stackoverflow.com', 'domain' => 'stackoverflow.com'],
            ['name' => 'Laravel', 'url' => 'https://laravel.com', 'domain' => 'laravel.com'],
            ['name' => 'Tailwind CSS', 'url' => 'https://tailwindcss.com', 'domain' => 'tailwindcss.com'],
        ];

        // Create admin user if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@uptimemonitor.com'],
            [
                'name' => 'Admin User',
                'is_admin' => true,
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
            ]
        );

        foreach ($sampleSites as $site) {
            $website = Website::create([
                'user_id' => $admin->id,
                'name' => $site['name'],
                'url' => $site['url'],
                'domain' => $site['domain'],
                'is_public' => true,
                'is_active' => true,
                'current_status' => 'up',
                'uptime_percentage' => rand(95, 100),
                'response_time' => rand(100, 500),
                'last_checked_at' => now()->subMinutes(rand(1, 30)),
                'last_uptime_at' => now()->subMinutes(rand(1, 30)),
            ]);

            // Create some monitoring results
            for ($i = 0; $i < 20; $i++) {
                MonitoringResult::create([
                    'website_id' => $website->id,
                    'status' => rand(1, 100) > 5 ? 'up' : 'down', // 95% uptime
                    'response_time' => rand(100, 1000),
                    'response_code' => rand(1, 100) > 5 ? 200 : 500,
                    'checked_at' => now()->subHours($i),
                    'location' => 'Server',
                ]);
            }
        }

        // Create sample blog posts
        $category = BlogCategory::first();
        if ($category && $admin) {
            Blog::create([
                'blog_category_id' => $category->id,
                'author_id' => $admin->id,
                'title' => 'How to Monitor Your Website Uptime Effectively',
                'slug' => 'how-to-monitor-website-uptime-effectively',
                'excerpt' => 'Learn the best practices for monitoring your website uptime and ensuring maximum availability for your users.',
                'content' => '<p>Website monitoring is crucial for maintaining a reliable online presence...</p>',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => now()->subDays(1),
            ]);

            Blog::create([
                'blog_category_id' => $category->id,
                'author_id' => $admin->id,
                'title' => 'SSL Certificate Monitoring: Why It Matters',
                'slug' => 'ssl-certificate-monitoring-why-it-matters',
                'excerpt' => 'Understanding the importance of SSL certificate monitoring and how to prevent security issues.',
                'content' => '<p>SSL certificates are essential for website security...</p>',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => now()->subDays(3),
            ]);
        }
    }
}
