<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionFeature;

class SubscriptionFeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Free Plan Features
        $this->createFeatures('free', [
            'ticket_limit' => '50',
            'agent_limit' => '1',
            'storage' => '100MB',
            'priority_support' => 'false',
            'api_access' => 'false',
            'analytics' => 'basic',
            'custom_domain' => 'false',
            'sla_management' => 'false',
            'ai_responses' => 'false',
        ]);

        // Professional Plan Features
        $this->createFeatures('professional', [
            'ticket_limit' => 'unlimited',
            'agent_limit' => '5',
            'storage' => '5GB',
            'priority_support' => 'true',
            'api_access' => 'true',
            'analytics' => 'advanced',
            'custom_domain' => 'false',
            'sla_management' => 'true',
            'ai_responses' => 'true',
        ]);

        // Enterprise Plan Features
        $this->createFeatures('enterprise', [
            'ticket_limit' => 'unlimited',
            'agent_limit' => 'unlimited',
            'storage' => '50GB',
            'priority_support' => 'true',
            'api_access' => 'true',
            'analytics' => 'enterprise',
            'custom_domain' => 'true',
            'sla_management' => 'true',
            'ai_responses' => 'true',
        ]);
    }

    private function createFeatures(string $plan, array $features)
    {
        foreach ($features as $feature => $value) {
            SubscriptionFeature::create([
                'plan_name' => $plan,
                'feature_name' => $feature,
                'feature_value' => $value,
            ]);
        }
    }
}