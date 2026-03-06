<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin user ────────────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'admin@lmsproject.com'],
            [
                'name'     => 'LMS Admin',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );

        // ── Default categories ────────────────────────────────────────────────
        $categories = [
            ['name' => 'Web Development', 'slug' => 'web-development', 'icon' => '💻', 'color' => '#045592'],
            ['name' => 'Data Science',    'slug' => 'data-science',    'icon' => '📊', 'color' => '#930056'],
            ['name' => 'Design',          'slug' => 'design',          'icon' => '🎨', 'color' => '#1A1262'],
            ['name' => 'Business',        'slug' => 'business',        'icon' => '💼', 'color' => '#045592'],
            ['name' => 'Marketing',       'slug' => 'marketing',       'icon' => '📣', 'color' => '#930056'],
            ['name' => 'Mobile Dev',      'slug' => 'mobile-dev',      'icon' => '📱', 'color' => '#1A1262'],
        ];
        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // ── Default subscription plans ────────────────────────────────────────
        $plans = [
            [
                'name'          => 'Monthly',
                'description'   => 'Full access for 1 month',
                'price'         => 29.99,
                'duration_days' => 30,
                'features'      => ['Access all courses', 'HD video streaming', 'Downloadable resources', 'Certificates'],
                'is_popular'    => false,
                'sort_order'    => 1,
            ],
            [
                'name'          => 'Quarterly',
                'description'   => 'Best value — 3 months full access',
                'price'         => 69.99,
                'duration_days' => 90,
                'features'      => ['Access all courses', 'HD video streaming', 'Downloadable resources', 'Certificates', 'Priority support'],
                'is_popular'    => true,
                'sort_order'    => 2,
            ],
            [
                'name'          => 'Annual',
                'description'   => 'Full year — best savings',
                'price'         => 199.99,
                'duration_days' => 365,
                'features'      => ['Access all courses', 'HD video streaming', 'Downloadable resources', 'Certificates', 'Priority support', 'Early access to new courses'],
                'is_popular'    => false,
                'sort_order'    => 3,
            ],
        ];
        foreach ($plans as $plan) {
            SubscriptionPlan::firstOrCreate(['name' => $plan['name']], $plan);
        }
    }
}
