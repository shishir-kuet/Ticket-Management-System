<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\User;
use App\Services\AIService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Handle chatbot messages with AI integration
     */
    public function handleMessage(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:500',
            'context' => 'required|string|in:homepage,customer',
            'action' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input provided.'
            ], 400);
        }

        $message = trim($request->input('message'));
        $context = $request->input('context');
        $action = $request->input('action');

        try {
            // Handle quick actions with rule-based responses (fast and reliable)
            if ($action) {
                return $this->handleQuickAction($action, $context);
            }

            // Try AI-powered response first
            if ($this->aiService->isAvailable()) {
                $userContext = $this->gatherUserContext($context);
                $aiResponse = $this->aiService->generateChatbotResponse($message, $context, $userContext);
                
                if ($aiResponse['success']) {
                    Log::info('AI Response Used', [
                        'user_id' => Auth::id(),
                        'context' => $context,
                        'message_length' => strlen($message)
                    ]);
                    
                    return response()->json($aiResponse);
                }
            }

            // Fallback to rule-based responses
            Log::info('Fallback to Rule-based Response', [
                'user_id' => Auth::id(),
                'context' => $context,
                'ai_available' => $this->aiService->isAvailable()
            ]);
            
            return $this->processMessageFallback($message, $context);

        } catch (\Exception $e) {
            Log::error('Chatbot Controller Error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'context' => $context
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Sorry, I encountered an error. Please try again.'
            ], 500);
        }
    }

    /**
     * Gather user context for AI
     */
    private function gatherUserContext(string $context): array
    {
        $userContext = [];
        
        if ($context === 'customer' && Auth::check()) {
            $user = Auth::user();
            $userContext = [
                'user_name' => $user->name,
                'user_role' => $user->role,
                'user_email' => $user->email,
                'total_tickets' => Ticket::where('customer_id', $user->id)->count(),
                'open_tickets' => Ticket::where('customer_id', $user->id)->where('status', 'open')->count(),
                'recent_activity' => $user->updated_at->diffForHumans()
            ];
        }
        
        return $userContext;
    }

    /**
     * Handle quick action buttons
     */
    private function handleQuickAction(string $action, string $context): JsonResponse
    {
        switch ($context) {
            case 'homepage':
                return $this->handleHomepageAction($action);
            case 'customer':
                return $this->handleCustomerAction($action);
            default:
                return $this->getErrorResponse();
        }
    }

    /**
     * Handle homepage quick actions
     */
    private function handleHomepageAction(string $action): JsonResponse
    {
        switch ($action) {
            case 'features':
                return response()->json([
                    'success' => true,
                    'response' => "🔍 **Resolve AI Features:**\n\n" .
                        "• **Smart Ticket Management** - Automatically categorize and prioritize tickets\n" .
                        "• **Team Collaboration** - Assign tickets to agents and track progress\n" .
                        "• **Customer Portal** - Self-service options for customers\n" .
                        "• **Analytics Dashboard** - Real-time insights and reporting\n" .
                        "• **AI-Powered Responses** - Get intelligent suggestions for common issues\n\n" .
                        "Would you like to know more about any specific feature?",
                    'quick_actions' => [
                        ['text' => '📊 Analytics', 'action' => 'analytics'],
                        ['text' => '🤖 AI Features', 'action' => 'ai-features'],
                        ['text' => '📝 Get Started', 'action' => 'register']
                    ]
                ]);

            case 'register':
                return response()->json([
                    'success' => true,
                    'response' => "📝 **Ready to get started?**\n\n" .
                        "Creating an account is quick and easy:\n\n" .
                        "1. Click the **Register** button in the top right\n" .
                        "2. Fill in your details (name, email, password)\n" .
                        "3. Start managing tickets immediately!\n\n" .
                        "You'll have access to:\n" .
                        "• Create and track tickets\n" .
                        "• View ticket history\n" .
                        "• Real-time status updates\n\n" .
                        "Would you like me to guide you through the registration process?",
                    'redirect' => route('register'),
                    'quick_actions' => [
                        ['text' => '🚀 Register Now', 'action' => 'redirect-register'],
                        ['text' => '🔑 Login Instead', 'action' => 'redirect-login'],
                        ['text' => '❓ More Questions', 'action' => 'help']
                    ]
                ]);

            case 'demo':
                return response()->json([
                    'success' => true,
                    'response' => "🎬 **See Resolve AI in Action!**\n\n" .
                        "Here's what you can do with our system:\n\n" .
                        "**For Customers:**\n" .
                        "• Submit tickets with detailed descriptions\n" .
                        "• Track progress in real-time\n" .
                        "• Communicate with support agents\n" .
                        "• View ticket history and status\n\n" .
                        "**For Support Teams:**\n" .
                        "• Manage tickets efficiently\n" .
                        "• Assign tickets to team members\n" .
                        "• Monitor team performance\n" .
                        "• Generate detailed reports\n\n" .
                        "Ready to try it yourself?",
                    'quick_actions' => [
                        ['text' => '📝 Start Free Trial', 'action' => 'register'],
                        ['text' => '💬 Chat with Sales', 'action' => 'contact'],
                        ['text' => '📋 View Pricing', 'action' => 'pricing']
                    ]
                ]);

            case 'analytics':
                return response()->json([
                    'success' => true,
                    'response' => "📊 **Powerful Analytics & Insights:**\n\n" .
                        "• **Real-time Dashboard** - Live metrics and KPIs\n" .
                        "• **Performance Tracking** - Response times and resolution rates\n" .
                        "• **Team Analytics** - Individual and team performance\n" .
                        "• **Customer Satisfaction** - Feedback and rating trends\n" .
                        "• **Trend Analysis** - Identify patterns and improve processes\n\n" .
                        "Make data-driven decisions to improve your support operations!",
                    'quick_actions' => [
                        ['text' => '🎯 See Demo', 'action' => 'demo'],
                        ['text' => '📝 Get Started', 'action' => 'register']
                    ]
                ]);

            case 'ai-features':
                return response()->json([
                    'success' => true,
                    'response' => "🤖 **AI-Powered Features:**\n\n" .
                        "• **Smart Categorization** - Automatically categorize incoming tickets\n" .
                        "• **Priority Detection** - AI determines ticket urgency\n" .
                        "• **Response Suggestions** - Get AI-powered response recommendations\n" .
                        "• **Sentiment Analysis** - Understand customer emotions\n" .
                        "• **Predictive Insights** - Forecast ticket volumes and trends\n\n" .
                        "Let AI handle the routine work while your team focuses on solving complex issues!",
                    'quick_actions' => [
                        ['text' => '🚀 Try AI Features', 'action' => 'register'],
                        ['text' => '📖 Learn More', 'action' => 'features']
                    ]
                ]);

            default:
                return $this->getHelpResponse('homepage');
        }
    }

    /**
     * Handle customer dashboard quick actions
     */
    private function handleCustomerAction(string $action): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please log in to continue.'
            ], 401);
        }

        switch ($action) {
            case 'ticket-status':
                return $this->getTicketStatus();

            case 'create-ticket':
                return response()->json([
                    'success' => true,
                    'response' => "➕ **Create a New Ticket:**\n\n" .
                        "I can help you create a new support ticket. Here's what I need:\n\n" .
                        "1. **Title** - Brief description of your issue\n" .
                        "2. **Category** - Type of issue (Technical, Billing, General)\n" .
                        "3. **Priority** - How urgent is this issue?\n" .
                        "4. **Description** - Detailed explanation\n\n" .
                        "Would you like to start creating a ticket now?",
                    'redirect' => route('tickets.create'),
                    'quick_actions' => [
                        ['text' => '🎫 Create Ticket', 'action' => 'redirect-create'],
                        ['text' => '📋 View My Tickets', 'action' => 'ticket-status'],
                        ['text' => '❓ Get Help', 'action' => 'help']
                    ]
                ]);

            case 'help':
                return $this->getHelpResponse('customer');

            default:
                return $this->getHelpResponse('customer');
        }
    }

    /**
     * Process natural language messages (fallback method)
     */
    private function processMessageFallback(string $message, string $context): JsonResponse
    {
        $message = strtolower($message);

        // Ticket-related queries
        if ($this->containsWords($message, ['ticket', 'status', 'progress', 'update'])) {
            if ($context === 'customer' && Auth::check()) {
                return $this->getTicketStatus();
            } else {
                return $this->explainTicketFeatures();
            }
        }

        // Pricing and cost queries
        if ($this->containsWords($message, ['price', 'cost', 'pricing', 'plan', 'subscription'])) {
            return $this->getPricingInfo();
        }

        // Feature-related queries
        if ($this->containsWords($message, ['feature', 'what can', 'capabilities', 'functions'])) {
            return $this->handleHomepageAction('features');
        }

        // Registration and getting started
        if ($this->containsWords($message, ['register', 'sign up', 'get started', 'create account'])) {
            return $this->handleHomepageAction('register');
        }

        // Help and support
        if ($this->containsWords($message, ['help', 'support', 'assistance', 'guide'])) {
            return $this->getHelpResponse($context);
        }

        // Contact and communication
        if ($this->containsWords($message, ['contact', 'reach', 'talk to', 'speak with'])) {
            return $this->getContactInfo();
        }

        // Default response based on context
        return $this->getDefaultResponse($context);
    }

    /**
     * Get ticket status for authenticated customer
     */
    private function getTicketStatus(): JsonResponse
    {
        $user = Auth::user();
        $tickets = Ticket::where('customer_id', $user->id)
            ->with(['category', 'agent'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        if ($tickets->isEmpty()) {
            return response()->json([
                'success' => true,
                'response' => "📋 **Your Tickets:**\n\n" .
                    "You don't have any tickets yet. When you create your first ticket, I'll be able to show you the status and progress here.\n\n" .
                    "Would you like to create a new ticket?",
                'quick_actions' => [
                    ['text' => '➕ Create Ticket', 'action' => 'create-ticket'],
                    ['text' => '❓ Get Help', 'action' => 'help']
                ]
            ]);
        }

        $response = "📋 **Your Recent Tickets:**\n\n";
        foreach ($tickets as $ticket) {
            $statusEmoji = $this->getStatusEmoji($ticket->status);
            $priorityEmoji = $this->getPriorityEmoji($ticket->priority);
            
            $response .= "{$statusEmoji} **#{$ticket->id}** - {$ticket->title}\n";
            $response .= "   Status: {$ticket->status} {$priorityEmoji}\n";
            if ($ticket->agent) {
                $response .= "   Agent: {$ticket->agent->name}\n";
            }
            $response .= "   Created: " . $ticket->created_at->format('M j, Y') . "\n\n";
        }

        $response .= "Need more details about any ticket? Just ask!";

        return response()->json([
            'success' => true,
            'response' => $response,
            'quick_actions' => [
                ['text' => '➕ New Ticket', 'action' => 'create-ticket'],
                ['text' => '📋 View All', 'action' => 'redirect-tickets'],
                ['text' => '❓ Get Help', 'action' => 'help']
            ]
        ]);
    }

    /**
     * Helper methods
     */
    private function containsWords(string $text, array $words): bool
    {
        foreach ($words as $word) {
            if (strpos($text, $word) !== false) {
                return true;
            }
        }
        return false;
    }

    private function getStatusEmoji(string $status): string
    {
        return match($status) {
            'open' => '🔵',
            'in_progress' => '🟡',
            'closed' => '✅',
            default => '📋'
        };
    }

    private function getPriorityEmoji(string $priority): string
    {
        return match($priority) {
            'low' => '🟢',
            'medium' => '🟡',
            'high' => '🟠',
            'urgent' => '🔴',
            default => '⚪'
        };
    }

    private function getHelpResponse(string $context): JsonResponse
    {
        if ($context === 'homepage') {
            return response()->json([
                'success' => true,
                'response' => "❓ **How can I help you?**\n\n" .
                    "I can assist you with:\n\n" .
                    "• Learning about Resolve AI features\n" .
                    "• Guiding you through registration\n" .
                    "• Explaining pricing and plans\n" .
                    "• Connecting you with our team\n" .
                    "• Answering general questions\n\n" .
                    "What would you like to know more about?",
                'quick_actions' => [
                    ['text' => '🔍 Features', 'action' => 'features'],
                    ['text' => '📝 Get Started', 'action' => 'register'],
                    ['text' => '💰 Pricing', 'action' => 'pricing'],
                    ['text' => '📞 Contact Us', 'action' => 'contact']
                ]
            ]);
        } else {
            return response()->json([
                'success' => true,
                'response' => "❓ **Customer Support Help:**\n\n" .
                    "I can help you with:\n\n" .
                    "• Checking your ticket status\n" .
                    "• Creating new support tickets\n" .
                    "• Navigating the dashboard\n" .
                    "• Understanding ticket priorities\n" .
                    "• General platform questions\n\n" .
                    "What do you need help with?",
                'quick_actions' => [
                    ['text' => '📋 Check Tickets', 'action' => 'ticket-status'],
                    ['text' => '➕ New Ticket', 'action' => 'create-ticket'],
                    ['text' => '🏠 Dashboard', 'action' => 'redirect-dashboard']
                ]
            ]);
        }
    }

    private function getDefaultResponse(string $context): JsonResponse
    {
        if ($context === 'homepage') {
            return response()->json([
                'success' => true,
                'response' => "I'd be happy to help! I can provide information about Resolve AI's features, help you get started, or answer any questions you have.\n\n" .
                    "What would you like to know about our ticket management system?",
                'quick_actions' => [
                    ['text' => '🔍 Explore Features', 'action' => 'features'],
                    ['text' => '📝 Get Started', 'action' => 'register'],
                    ['text' => '❓ Ask Question', 'action' => 'help']
                ]
            ]);
        } else {
            return response()->json([
                'success' => true,
                'response' => "I'm here to help with your support needs! I can check your ticket status, help you create new tickets, or answer questions about the platform.\n\n" .
                    "What can I assist you with today?",
                'quick_actions' => [
                    ['text' => '📋 My Tickets', 'action' => 'ticket-status'],
                    ['text' => '➕ New Ticket', 'action' => 'create-ticket'],
                    ['text' => '❓ Get Help', 'action' => 'help']
                ]
            ]);
        }
    }

    private function getPricingInfo(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'response' => "💰 **Resolve AI Pricing:**\n\n" .
                "We offer flexible plans to suit teams of all sizes:\n\n" .
                "🆓 **Starter** - Free\n" .
                "• Up to 50 tickets/month\n" .
                "• Basic support features\n" .
                "• Email support\n\n" .
                "⭐ **Professional** - \$29/month\n" .
                "• Unlimited tickets\n" .
                "• Advanced AI features\n" .
                "• Priority support\n" .
                "• Analytics dashboard\n\n" .
                "🚀 **Enterprise** - Custom pricing\n" .
                "• Custom integrations\n" .
                "• Dedicated support\n" .
                "• Advanced security\n\n" .
                "All plans include a 14-day free trial!",
            'quick_actions' => [
                ['text' => '🆓 Start Free', 'action' => 'register'],
                ['text' => '📞 Contact Sales', 'action' => 'contact'],
                ['text' => '📋 Compare Plans', 'action' => 'features']
            ]
        ]);
    }

    private function getContactInfo(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'response' => "📞 **Get in Touch:**\n\n" .
                "We'd love to hear from you! Here are the ways to reach us:\n\n" .
                "• **Email:** support@resolveai.com\n" .
                "• **Phone:** +1 (555) 123-4567\n" .
                "• **Live Chat:** Available 24/7 (that's me!)\n" .
                "• **Response Time:** Usually within 2 hours\n\n" .
                "For immediate assistance, I'm here to help right now!",
            'quick_actions' => [
                ['text' => '📧 Email Us', 'action' => 'email'],
                ['text' => '💬 Continue Chat', 'action' => 'help'],
                ['text' => '📝 Get Started', 'action' => 'register']
            ]
        ]);
    }

    private function explainTicketFeatures(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'response' => "🎫 **Ticket Management Features:**\n\n" .
                "• **Smart Creation** - Easy ticket submission with auto-categorization\n" .
                "• **Real-time Updates** - Track progress from start to resolution\n" .
                "• **Priority Levels** - Urgent, High, Medium, Low prioritization\n" .
                "• **Team Assignment** - Tickets routed to the right specialists\n" .
                "• **Communication Hub** - Chat directly with support agents\n" .
                "• **History Tracking** - Complete audit trail of all interactions\n\n" .
                "Ready to experience efficient ticket management?",
            'quick_actions' => [
                ['text' => '📝 Try It Now', 'action' => 'register'],
                ['text' => '🎬 See Demo', 'action' => 'demo'],
                ['text' => '❓ Ask More', 'action' => 'help']
            ]
        ]);
    }

    private function getErrorResponse(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, I didn\'t understand that. Could you please rephrase your question?'
        ], 400);
    }
}