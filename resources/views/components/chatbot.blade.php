@props(['context' => 'homepage'])

<!-- Chatbot Widget -->
<div id="chatbot-widget" class="chatbot-widget" data-context="{{ $context }}">
    <!-- Floating Chat Button -->
    <div id="chatbot-toggle" class="chatbot-toggle">
        <svg class="chatbot-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
        <svg class="chatbot-close-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        <div class="chatbot-notification-badge" id="chatbot-notification">
            <span>!</span>
        </div>
    </div>

    <!-- Chat Window -->
    <div id="chatbot-window" class="chatbot-window hidden">
        <!-- Chat Header -->
        <div class="chatbot-header">
            <div class="chatbot-avatar">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div class="chatbot-header-content">
                <h3 class="chatbot-title">Resolve AI Assistant</h3>
                <p class="chatbot-status">
                    <span class="status-indicator"></span>
                    Online - Ready to help
                </p>
            </div>
            <button id="chatbot-minimize" class="chatbot-minimize">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>

        <!-- Chat Messages Container -->
        <div id="chatbot-messages" class="chatbot-messages">
            <div class="chatbot-message bot-message" data-timestamp="{{ now() }}">
                <div class="message-avatar">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="message-content">
                    @if($context === 'homepage')
                        <p>👋 Welcome to Resolve AI! I'm here to help you get started with our intelligent ticket management system.</p>
                        <p>I can help you:</p>
                        <ul>
                            <li>Learn about our features</li>
                            <li>Guide you through registration</li>
                            <li>Answer questions about the system</li>
                        </ul>
                        <p>What would you like to know?</p>
                    @elseif($context === 'customer')
                        <p>Hi {{ auth()->user()->name ?? 'there' }}! 👋</p>
                        <p>I can help you with:</p>
                        <ul>
                            <li>Check your ticket status</li>
                            <li>Create new tickets</li>
                            <li>Get help with common issues</li>
                            <li>Navigate the system</li>
                        </ul>
                        <p>How can I assist you today?</p>
                    @elseif($context === 'admin')
                        <p>Welcome back, Admin! 👋</p>
                        <p>I can help you with:</p>
                        <ul>
                            <li>View ticket statistics</li>
                            <li>Check team performance</li>
                            <li>Monitor system status</li>
                            <li>Manage assignments</li>
                        </ul>
                        <p>What would you like to check?</p>
                    @endif
                </div>
                <div class="message-time">{{ now()->format('g:i A') }}</div>
            </div>
        </div>

        <!-- Typing Indicator -->
        <div id="chatbot-typing" class="chatbot-typing hidden">
            <div class="typing-avatar">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div class="typing-indicator">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <!-- Quick Actions (Context Dependent) -->
        <div class="chatbot-quick-actions" id="chatbot-quick-actions">
            @if($context === 'homepage')
                <button class="quick-action-btn" data-action="features">🔍 Explore Features</button>
                <button class="quick-action-btn" data-action="register">📝 Get Started</button>
                <button class="quick-action-btn" data-action="demo">🎬 See Demo</button>
            @elseif($context === 'customer')
                <button class="quick-action-btn" data-action="ticket-status">📋 Check Tickets</button>
                <button class="quick-action-btn" data-action="create-ticket">➕ New Ticket</button>
                <button class="quick-action-btn" data-action="help">❓ Get Help</button>
            @elseif($context === 'admin')
                <button class="quick-action-btn" data-action="ticket-overview">📊 Overview</button>
                <button class="quick-action-btn" data-action="team-overview">👥 Team Stats</button>
                <button class="quick-action-btn" data-action="performance">📈 Performance</button>
                <button class="quick-action-btn" data-action="help">❓ Help</button>
            @endif
        </div>

        <!-- Chat Input -->
        <div class="chatbot-input-container">
            <div class="chatbot-input-wrapper">
                <input 
                    type="text" 
                    id="chatbot-input" 
                    class="chatbot-input" 
                    placeholder="Type your message..."
                    autocomplete="off"
                >
                <button id="chatbot-send" class="chatbot-send-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </div>
            <p class="chatbot-disclaimer">
                Powered by Resolve AI • Always here to help
            </p>
        </div>
    </div>
</div>