<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;

class AIService
{
    private const MAX_CONTEXT_LENGTH = 4000;
    private const CACHE_TTL = 300; // 5 minutes
    
    /**
     * Generate AI response for chatbot
     */
    public function generateChatbotResponse(string $message, string $context, array $userContext = []): array
    {
        try {
            $systemPrompt = $this->buildSystemPrompt($context);
            $userPrompt = $this->buildUserPrompt($message, $context, $userContext);
            
            Log::info('AI Request', [
                'context' => $context,
                'message' => $message,
                'user_id' => Auth::id()
            ]);

            $response = OpenAI::chat()->create([
                'model' => config('services.openai.model'),
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt]
                ],
                'max_tokens' => config('services.openai.max_tokens'),
                'temperature' => config('services.openai.temperature'),
            ]);

            $aiResponse = $response->choices[0]->message->content;
            
            Log::info('AI Response Generated', [
                'response_length' => strlen($aiResponse),
                'user_id' => Auth::id()
            ]);

            return [
                'success' => true,
                'response' => $this->formatAIResponse($aiResponse),
                'quick_actions' => $this->generateQuickActions($context, $message, $aiResponse)
            ];

        } catch (Exception $e) {
            Log::error('AI Service Error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'context' => $context,
                'message' => $message
            ]);

            return [
                'success' => false,
                'error' => 'I apologize, but I\'m having trouble processing your request right now. Please try again in a moment.'
            ];
        }
    }

    /**
     * Build system prompt based on context
     */
    private function buildSystemPrompt(string $context): string
    {
        $basePrompt = "You are Resolve AI Assistant, a helpful and knowledgeable AI customer support agent for Resolve AI - a modern ticket management system. ";
        
        $systemKnowledge = "
ABOUT RESOLVE AI:
- Modern ticket management system built with Laravel
- Supports customers, agents, and administrators
- Features: ticket creation, assignment, status tracking, comments, categories, analytics
- AI-powered prioritization and categorization
- Real-time updates and notifications
- Professional glassmorphism UI design

TICKET SYSTEM FEATURES:
- Priority levels: Low, Medium, High, Urgent
- Status types: Open, In Progress, Closed
- Categories: Technical, Billing, General Support
- Role-based access control
- Team assignment and collaboration
- Progress tracking and analytics

YOUR PERSONALITY:
- Professional but friendly and approachable
- Concise yet helpful responses
- Empathetic to customer concerns
- Proactive in offering solutions
- Knowledgeable about the platform
";

        if ($context === 'homepage') {
            return $basePrompt . $systemKnowledge . "
CONTEXT: Homepage visitor (may not be registered)
YOUR ROLE: Help visitors understand Resolve AI, guide them to registration, explain features
GUIDELINES:
- Encourage registration and platform adoption
- Explain benefits and features clearly
- Provide helpful guidance for getting started
- Be enthusiastic about the platform capabilities
- Keep responses under 150 words
";
        } else {
            $user = Auth::user();
            $userInfo = $user ? "User: {$user->name} ({$user->email}) - Role: {$user->role}" : "Anonymous user";
            
            return $basePrompt . $systemKnowledge . "
CONTEXT: Customer dashboard - authenticated user
USER INFO: {$userInfo}
YOUR ROLE: Provide personalized support, help with tickets, answer questions
GUIDELINES:
- Provide specific help based on user's tickets and role
- Be helpful with ticket management tasks
- Offer to assist with specific actions
- Reference user's actual data when relevant
- Keep responses under 150 words
";
        }
    }

    /**
     * Build user prompt with context
     */
    private function buildUserPrompt(string $message, string $context, array $userContext): string
    {
        $prompt = "User message: \"{$message}\"\n\n";
        
        if ($context === 'customer' && Auth::check()) {
            $prompt .= $this->getUserTicketContext();
        }
        
        $prompt .= "Please provide a helpful, natural response. Focus on being genuinely helpful while staying within your role as a Resolve AI assistant.";
        
        return $prompt;
    }

    /**
     * Get user's ticket context for AI
     */
    private function getUserTicketContext(): string
    {
        $user = Auth::user();
        if (!$user) return '';

        $cacheKey = "user_ticket_context_{$user->id}";
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function() use ($user) {
            $tickets = Ticket::where('customer_id', $user->id)
                ->with(['category', 'agent'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            if ($tickets->isEmpty()) {
                return "\nUSER CONTEXT: No tickets yet - new user who might need guidance on creating their first ticket.\n";
            }

            $context = "\nUSER TICKET CONTEXT:\n";
            foreach ($tickets as $ticket) {
                $agent = $ticket->agent ? $ticket->agent->name : 'Unassigned';
                $context .= "- Ticket #{$ticket->id}: \"{$ticket->title}\" (Status: {$ticket->status}, Priority: {$ticket->priority}, Agent: {$agent})\n";
            }
            
            return $context . "\n";
        });
    }

    /**
     * Format AI response for display
     */
    private function formatAIResponse(string $response): string
    {
        // Clean up the response
        $response = trim($response);
        
        // Remove any system artifacts
        $response = preg_replace('/^(AI|Assistant|Resolve AI):?\s*/i', '', $response);
        
        // Ensure proper formatting
        $response = preg_replace('/\n{3,}/', "\n\n", $response);
        
        return $response;
    }

    /**
     * Generate contextual quick actions based on AI response
     */
    private function generateQuickActions(string $context, string $message, string $aiResponse): array
    {
        $actions = [];
        
        if ($context === 'homepage') {
            // Always include these for homepage
            $actions[] = ['text' => '📝 Get Started', 'action' => 'register'];
            
            // Add contextual actions based on message/response
            if (stripos($message . $aiResponse, 'feature') !== false) {
                $actions[] = ['text' => '🔍 View Features', 'action' => 'features'];
            }
            
            if (stripos($message . $aiResponse, 'price') !== false || stripos($message . $aiResponse, 'cost') !== false) {
                $actions[] = ['text' => '💰 See Pricing', 'action' => 'pricing'];
            }
            
            if (stripos($message . $aiResponse, 'demo') !== false) {
                $actions[] = ['text' => '🎬 Watch Demo', 'action' => 'demo'];
            }
            
            $actions[] = ['text' => '❓ Ask More', 'action' => 'help'];
            
        } else {
            // Customer dashboard actions
            if (stripos($message . $aiResponse, 'ticket') !== false) {
                $actions[] = ['text' => '📋 My Tickets', 'action' => 'ticket-status'];
                $actions[] = ['text' => '➕ New Ticket', 'action' => 'create-ticket'];
            } else {
                $actions[] = ['text' => '📋 Check Tickets', 'action' => 'ticket-status'];
            }
            
            if (stripos($message . $aiResponse, 'help') !== false || stripos($message . $aiResponse, 'how') !== false) {
                $actions[] = ['text' => '❓ More Help', 'action' => 'help'];
            }
            
            $actions[] = ['text' => '🏠 Dashboard', 'action' => 'redirect-dashboard'];
        }
        
        // Limit to 4 actions max for UI
        return array_slice($actions, 0, 4);
    }

    /**
     * Generate ticket summary for AI context
     */
    public function generateTicketSummary(Ticket $ticket): string
    {
        try {
            $ticketInfo = "
Ticket ID: {$ticket->id}
Title: {$ticket->title}
Description: {$ticket->description}
Status: {$ticket->status}
Priority: {$ticket->priority}
Created: {$ticket->created_at->format('M j, Y')}
Customer: {$ticket->customer->name}
Agent: " . ($ticket->agent ? $ticket->agent->name : 'Unassigned') . "
Category: {$ticket->category->name}
";

            $response = OpenAI::chat()->create([
                'model' => config('services.openai.model'),
                'messages' => [
                    [
                        'role' => 'system', 
                        'content' => 'You are a support ticket analyst. Provide a brief, professional summary of the ticket status and next steps in 2-3 sentences.'
                    ],
                    [
                        'role' => 'user', 
                        'content' => "Analyze this ticket and provide a summary:\n{$ticketInfo}"
                    ]
                ],
                'max_tokens' => 100,
                'temperature' => 0.3,
            ]);

            return $response->choices[0]->message->content;

        } catch (Exception $e) {
            Log::error('AI Ticket Summary Error', ['error' => $e->getMessage(), 'ticket_id' => $ticket->id]);
            return "Ticket #{$ticket->id} - {$ticket->status} priority {$ticket->priority}. Created on {$ticket->created_at->format('M j, Y')}.";
        }
    }

    /**
     * Check if AI service is available
     */
    public function isAvailable(): bool
    {
        return !empty(config('services.openai.api_key'));
    }

    /**
     * Get AI usage stats (for admin dashboard)
     */
    public function getUsageStats(): array
    {
        // This could be expanded to track actual usage via database
        return [
            'requests_today' => Cache::get('ai_requests_today', 0),
            'errors_today' => Cache::get('ai_errors_today', 0),
            'avg_response_time' => Cache::get('ai_avg_response_time', 0),
            'is_available' => $this->isAvailable()
        ];
    }

    /**
     * Increment usage counters
     */
    private function incrementUsage(string $type = 'request'): void
    {
        $key = "ai_{$type}s_today";
        $current = Cache::get($key, 0);
        Cache::put($key, $current + 1, now()->endOfDay());
    }
}