<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AIService;

class TestAIIntegration extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'ai:test {--message= : Test message to send to AI}';

    /**
     * The console command description.
     */
    protected $description = 'Test the OpenAI integration';

    protected $aiService;

    public function __construct(AIService $aiService)
    {
        parent::__construct();
        $this->aiService = $aiService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Resolve AI Integration...');
        $this->newLine();

        // Check if API key is configured
        if (!config('openai.api_key')) {
            $this->error('❌ OpenAI API key is not configured.');
            $this->warn('Please add OPENAI_API_KEY to your .env file.');
            $this->warn('Get your API key from: https://platform.openai.com/api-keys');
            return 1;
        }

        // Check if AI service is available
        if (!$this->aiService->isAvailable()) {
            $this->error('❌ AI Service is not available.');
            return 1;
        }

        $this->info('✅ API key is configured');

        // Test message
        $testMessage = $this->option('message') ?? 'Hello! Can you help me understand how Resolve AI works?';
        
        $this->info("🤖 Testing with message: \"{$testMessage}\"");
        $this->newLine();

        try {
            // Test homepage context
            $this->info('Testing Homepage Context...');
            $response = $this->aiService->generateChatbotResponse($testMessage, 'homepage');
            
            if ($response['success']) {
                $this->info('✅ Homepage AI Response:');
                $this->line($response['response']);
                $this->newLine();
                
                if (!empty($response['quick_actions'])) {
                    $this->info('Quick Actions Generated:');
                    foreach ($response['quick_actions'] as $action) {
                        $this->line("  - {$action['text']} ({$action['action']})");
                    }
                    $this->newLine();
                }
            } else {
                $this->error('❌ Homepage test failed');
                if (isset($response['error'])) {
                    $this->warn($response['error']);
                }
            }

            // Test customer context
            $this->info('Testing Customer Context...');
            $customerResponse = $this->aiService->generateChatbotResponse($testMessage, 'customer');
            
            if ($customerResponse['success']) {
                $this->info('✅ Customer AI Response:');
                $this->line($customerResponse['response']);
                $this->newLine();
            } else {
                $this->error('❌ Customer test failed');
                if (isset($customerResponse['error'])) {
                    $this->warn($customerResponse['error']);
                }
            }

            // Display usage stats
            $stats = $this->aiService->getUsageStats();
            $this->info('📊 AI Usage Stats:');
            $this->table(
                ['Metric', 'Value'],
                [
                    ['Requests Today', $stats['requests_today']],
                    ['Errors Today', $stats['errors_today']],
                    ['Avg Response Time', $stats['avg_response_time'] . 'ms'],
                    ['Service Available', $stats['is_available'] ? 'Yes' : 'No']
                ]
            );

            $this->info('🎉 AI Integration test completed successfully!');
            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Test failed with error: ' . $e->getMessage());
            $this->warn('Check your OpenAI API key and internet connection.');
            return 1;
        }
    }
}