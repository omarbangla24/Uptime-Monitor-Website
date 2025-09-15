<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SubscriptionPlansSeeder::class,
            BlogCategoriesSeeder::class,
            SettingsSeeder::class,
        ]);

        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@uptimemonitor.com',
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);
    }
}
