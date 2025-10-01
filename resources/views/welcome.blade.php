<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Resolve AI') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="resolve-ai-homepage">
        <div class="nav-bar">
            <div class="nav-container">
                @if (Route::has('login'))
                    <nav class="nav-content">
                        <a href="/" class="nav-brand">Resolve AI</a>
                        <div class="nav-links">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-secondary">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn btn-outline">Register</a>
                                @endif
                            @endauth
                        </div>
                    </nav>
                @endif
            </div>
        </div>

        <div class="container-main">
            <div class="hero-section">
                <div class="hero-content">
                    <h1 class="heading-xl">AI-Powered Ticket Resolution</h1>
                    <p class="text-lead">Transform your customer support with Resolve AI's intelligent ticket management system. Automate, prioritize, and resolve customer issues faster than ever before.</p>
                    
                    <div class="feature-list">
                        <div class="feature-item">
                            <span class="feature-dot">✓</span>
                            <span><strong>Track Progress:</strong> Monitor ticket status, priority levels, and resolution times with detailed analytics.</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-dot">✓</span>
                            <span><strong>Team Collaboration:</strong> Assign tickets to team members and maintain seamless communication throughout the process.</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-dot">✓</span>
                            <span><strong>Customer Support:</strong> Maintain comprehensive ticket history and provide transparent resolution tracking.</span>
                        </div>
                    </div>

                    <div class="action-buttons">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary">Access Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary">Get Started</a>
                        @endauth
                        <a href="#features" class="btn btn-learn-more">Learn More</a>
                    </div>
                </div>

                <div class="hero-image">
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <div class="quote-icon">"</div>
                            <p class="testimonial-text">Resolve AI completely transformed our customer support process. Our response times improved by 80% and customer satisfaction reached an all-time high. The AI-powered categorization saves us hours every day!</p>
                        </div>
                        <div class="testimonial-author">
                            <div class="author-avatar">MAS</div>
                            <div class="author-info">
                                <span class="author-name">Mosaddek Ali Shishir</span>
                                <span class="author-role">CEO, Resolve AI</span>
                            </div>
                        </div>
                        <div class="testimonial-rating">
                            <span class="stars">★★★★★</span>
                            <span class="rating-text">5.0 out of 5</span>
                        </div>
                    </div>
                </div>
            </div>

            <section id="features" class="section">
                <div class="section-header">
                    <h2 class="heading-lg">Complete Feature Set</h2>
                    <p class="text-lead">Everything you need to manage customer support efficiently</p>
                </div>
                
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h3 class="feature-title">Create & Manage Tickets</h3>
                        <p class="feature-description">Easily create new support tickets, set priorities, and track progress from creation to resolution.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="feature-title">Team Assignment</h3>
                        <p class="feature-description">Assign tickets to specific team members and collaborate effectively on customer issues.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                        <h3 class="feature-title">Smart Categories</h3>
                        <p class="feature-description">Organize tickets with intelligent categorization for improved filtering and management.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h3 class="feature-title">Communication Hub</h3>
                        <p class="feature-description">Centralized commenting system to maintain clear communication history and updates.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="feature-title">Progress Tracking</h3>
                        <p class="feature-description">Monitor ticket lifecycle with detailed status tracking and performance analytics.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <h3 class="feature-title">Role-Based Access</h3>
                        <p class="feature-description">Secure system with different permission levels for administrators, agents, and customers.</p>
                    </div>
                </div>
            </section>

            <div class="footer">
                <div class="footer-content">
                    <p>&copy; {{ date('Y') }} Resolve AI. Built with Laravel {{ app()->version() }}.</p>
                </div>
            </div>
        </div>

        <!-- Chatbot Integration -->
        <x-chatbot context="homepage" />
    </body>
</html>