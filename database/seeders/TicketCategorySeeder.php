<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TicketCategory;

class TicketCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Hardware Issues',
                'description' => 'Problems related to computer hardware, devices, and equipment',
                'is_active' => true,
            ],
            [
                'name' => 'Software Issues',
                'description' => 'Software bugs, application problems, and installation issues',
                'is_active' => true,
            ],
            [
                'name' => 'Network Problems',
                'description' => 'Internet connectivity, network configuration, and access issues',
                'is_active' => true,
            ],
            [
                'name' => 'Account Issues',
                'description' => 'Login problems, password resets, and account access',
                'is_active' => true,
            ],
            [
                'name' => 'General Technical Support',
                'description' => 'General technical questions and how-to inquiries',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            TicketCategory::create($category);
        }
    }
}
