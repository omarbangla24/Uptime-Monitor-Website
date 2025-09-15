<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Website Monitoring',
                'slug' => 'website-monitoring',
                'description' => 'Tips and guides about website monitoring',
                'color' => '#3B82F6',
                'sort_order' => 1,
            ],
            [
                'name' => 'Performance',
                'slug' => 'performance',
                'description' => 'Website performance optimization',
                'color' => '#10B981',
                'sort_order' => 2,
            ],
            [
                'name' => 'Security',
                'slug' => 'security',
                'description' => 'Website security and SSL',
                'color' => '#F59E0B',
                'sort_order' => 3,
            ],
            [
                'name' => 'DevOps',
                'slug' => 'devops',
                'description' => 'DevOps and server monitoring',
                'color' => '#EF4444',
                'sort_order' => 4,
            ],
            [
                'name' => 'Tutorials',
                'slug' => 'tutorials',
                'description' => 'Step-by-step tutorials',
                'color' => '#8B5CF6',
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            BlogCategory::create($category);
        }
    }
}
