@extends('layouts.app')

@section('title', 'Help & Support - Resolve AI')

@section('content')
<div class="container-main">
    <div class="admin-header">
        <h1 class="heading-xl">Help & Support</h1>
        <p class="text-lead">Find answers to common questions or create a support ticket for personalized assistance</p>
    </div>

    <!-- Quick Self-Service Options -->
    <div class="help-section">
        <h2 class="heading-md">Quick Solutions</h2>
        <div class="help-cards-grid">
            <div class="help-card">
                <div class="help-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="help-title">Hardware Troubleshooting</h3>
                <p class="help-description">Basic steps to resolve common hardware issues</p>
                <ul class="help-list">
                    <li>Check all cable connections</li>
                    <li>Restart your device</li>
                    <li>Update device drivers</li>
                    <li>Run hardware diagnostics</li>
                </ul>
            </div>

            <div class="help-card">
                <div class="help-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                </div>
                <h3 class="help-title">Software Problems</h3>
                <p class="help-description">Common software troubleshooting steps</p>
                <ul class="help-list">
                    <li>Update your software to latest version</li>
                    <li>Clear cache and temporary files</li>
                    <li>Restart the application</li>
                    <li>Check system requirements</li>
                </ul>
            </div>

            <div class="help-card">
                <div class="help-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path>
                    </svg>
                </div>
                <h3 class="help-title">Network Issues</h3>
                <p class="help-description">Resolve connectivity and network problems</p>
                <ul class="help-list">
                    <li>Check internet connection</li>
                    <li>Restart your router/modem</li>
                    <li>Verify network settings</li>
                    <li>Test with different device</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="help-section">
        <h2 class="heading-md">Frequently Asked Questions</h2>
        <div class="faq-list">
            <div class="faq-item">
                <h4 class="faq-question">How do I create a support ticket?</h4>
                <p class="faq-answer">You can create a support ticket by clicking "Create New Ticket" on your dashboard or using the button below. Provide a clear description of your issue for faster resolution.</p>
            </div>
            <div class="faq-item">
                <h4 class="faq-question">How long does it take to resolve a ticket?</h4>
                <p class="faq-answer">Resolution time depends on the complexity and priority of your issue. High priority tickets are typically addressed within 2-4 hours, while standard tickets are resolved within 24-48 hours.</p>
            </div>
            <div class="faq-item">
                <h4 class="faq-question">Can I track the status of my ticket?</h4>
                <p class="faq-answer">Yes! You can view all your tickets and their current status on your dashboard. You'll also receive email notifications when your ticket status changes.</p>
            </div>
            <div class="faq-item">
                <h4 class="faq-question">How do I reset my password?</h4>
                <p class="faq-answer">Use the "Forgot Password" link on the login page to reset your password. You'll receive an email with instructions to create a new password.</p>
            </div>
        </div>
    </div>

    <!-- Still Need Help Section -->
    <div class="help-section">
        <div class="need-help-card">
            <div class="need-help-content">
                <h3 class="heading-md">Still Need Help?</h3>
                <p class="text-muted">Can't find what you're looking for? Our support team is here to help you resolve any issue.</p>
                <div class="help-actions">
                    <a href="{{ route('tickets.create') }}" class="btn btn-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create Support Ticket
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2l-2 2m0 0l2 2m-2-2h14"></path>
                        </svg>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.help-section {
    margin-bottom: 3rem;
}

.help-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
}

.help-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 0.75rem;
    padding: 1.5rem;
    border: 1px solid rgba(226, 232, 240, 0.8);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    transition: all 0.3s ease;
}

.help-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
}

.help-icon {
    width: 3rem;
    height: 3rem;
    background: linear-gradient(135deg, #059669, #10b981);
    color: white;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
}

.help-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 0.5rem;
}

.help-description {
    color: #6b7280;
    font-size: 0.875rem;
    margin-bottom: 1rem;
    line-height: 1.5;
}

.help-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.help-list li {
    padding: 0.25rem 0;
    color: #374151;
    font-size: 0.875rem;
    position: relative;
    padding-left: 1rem;
}

.help-list li::before {
    content: '•';
    color: #059669;
    font-weight: bold;
    position: absolute;
    left: 0;
}

.faq-list {
    space-y: 1rem;
}

.faq-item {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 0.5rem;
    padding: 1.25rem;
    border: 1px solid rgba(226, 232, 240, 0.8);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    margin-bottom: 1rem;
}

.faq-question {
    font-size: 1rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 0.5rem;
}

.faq-answer {
    color: #6b7280;
    font-size: 0.875rem;
    line-height: 1.5;
    margin: 0;
}

.need-help-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 0.75rem;
    padding: 2rem;
    border: 1px solid rgba(226, 232, 240, 0.8);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    text-align: center;
}

.need-help-content h3 {
    margin-bottom: 0.5rem;
}

.need-help-content p {
    margin-bottom: 1.5rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.help-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .help-cards-grid {
        grid-template-columns: 1fr;
    }
    
    .help-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .help-actions .btn {
        min-width: 200px;
    }
}
</style>
@endsection