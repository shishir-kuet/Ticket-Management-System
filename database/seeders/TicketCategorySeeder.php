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
                'name' => 'General Technical Problem',
                'description' => 'General technical questions and troubleshooting support',
                'is_active' => true,
            ],
            [
                'name' => 'Account & Access Issues',
                'description' => 'Login problems, password resets, and account access issues',
                'is_active' => true,
            ],
            [
                'name' => 'Billing & Payment',
                'description' => 'Billing inquiries, payment issues, and subscription management',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            TicketCategory::updateOrCreate(
                ['name' => $category['name']], // Find by name
                $category // Update or create with these values
            );
        }
        
        // Mark unwanted categories as inactive instead of deleting them
        TicketCategory::whereNotIn('name', ['Hardware Issues', 'Software Issues', 'Network Problems', 'General Technical Problem', 'Account & Access Issues', 'Billing & Payment'])
            ->update(['is_active' => false]);
    }
}
