@extends('layouts.app')

@section('body-class', 'resolve-ai-homepage')

@section('title', 'Choose Your Plan - Resolve AI')

@section('content')
<div class="subscription-page">
        <div class="page-gradient">

        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto px-4">
        <!-- Free Plan Section -->
        <div class="bg-white rounded-2xl p-8 mb-8 border border-gray-200 free-plan-wrapper">
            <div class="subscription-header">
                <h1 class="subscription-title">Choose Your Plan</h1>
                <p class="subscription-subtitle">Select the plan that best fits your needs</p>
                <div class="header-accent"></div>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Start with Free</h2>
            <div class="plan-card">
            <div class="plan-header">
                <h2 class="plan-name">Free</h2>
                <div class="plan-price">
                    <span class="currency">৳</span>
                    <span class="amount">0</span>
                    <span class="period">/month</span>
                </div>
                <p class="plan-description">Perfect for individuals and small teams</p>
            </div>

            <div class="plan-features">
                <ul>
                    <li>✓ Up to 10 tickets/month</li>
                    <li>✓ 1 agent</li>
                    <li>✓ 50MB storage</li>
                    <li>✓ Basic analytics</li>
                    <li class="disabled">✗ Priority support</li>
                    <li class="disabled">✗ API access</li>
                    <li class="disabled">✗ AI responses</li>
                </ul>
            </div>

            <div class="plan-action">
                @if($currentPlan === 'free')
                    <button class="btn-current">Current Plan</button>
                @else
                    <form action="{{ route('subscription.downgrade') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-select">Select Plan</button>
                    </form>
                @endif
            </div>
        </div>

        </div>
        <!-- Professional Plan Section -->
        <div class="bg-slate-50 rounded-2xl p-8 mb-8 border-2 border-slate-600">
            <div class="text-center mb-6">
                <span class="bg-slate-700 text-white text-sm font-bold py-2 px-6 rounded-full inline-block">MOST POPULAR</span>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Professional Plan</h2>
            <div class="plan-card featured">
            <div class="plan-badge">MOST POPULAR</div>
            <div class="plan-header">
                <h2 class="plan-name">Professional</h2>
                <div class="plan-price">
                    <span class="currency">৳</span>
                    <span class="amount">2,999</span>
                    <span class="period">/month</span>
                </div>
                <p class="plan-description">For businesses that need full power</p>
            </div>

            <div class="plan-features">
                <ul>
                    <li>✓ Unlimited tickets</li>
                    <li>✓ Up to 5 agents</li>
                    <li>✓ 10GB storage</li>
                    <li>✓ Advanced analytics</li>
                    <li>✓ Priority support 24/7</li>
                    <li>✓ API access</li>
                    <li>✓ AI-powered responses</li>
                    <li>✓ SLA management</li>
                    <li>✓ Custom branding</li>
                </ul>
            </div>

            <div class="plan-action">
                @if($currentPlan === 'professional')
                    <button class="btn-current">Current Plan</button>
                @else
                    <form action="{{ route('subscription.checkout') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan" value="professional">
                        <button type="submit" class="btn-select btn-featured">Choose Professional</button>
                    </form>
                @endif
            </div>
        </div>

        </div>
        <!-- Enterprise Plan Section -->
        <div class="bg-white rounded-2xl p-8 border border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Enterprise Solution</h2>
            <div class="plan-card">
            <div class="plan-header">
                <h2 class="plan-name">Enterprise</h2>
                <div class="plan-price">
                    <span class="currency">৳</span>
                    <span class="amount">7,999</span>
                    <span class="period">/month</span>
                </div>
                <p class="plan-description">Complete solution for large organizations</p>
            </div>

            <div class="plan-features">
                <ul>
                    <li>✓ Unlimited tickets & workflows</li>
                    <li>✓ Unlimited agents</li>
                    <li>✓ 100GB storage</li>
                    <li>✓ Enterprise analytics & reporting</li>
                    <li>✓ 24/7 Premium support</li>
                    <li>✓ Advanced API access</li>
                    <li>✓ AI-powered automation</li>
                    <li>✓ Custom domain & branding</li>
                    <li>✓ Custom integrations</li>
                    <li>✓ Dedicated account manager</li>
                    <li>✓ On-premise deployment option</li>
                </ul>
            </div>

            <div class="plan-action">
                <a href="{{ route('contact.sales') }}" class="btn-contact">Contact Sales</a>
            </div>
        </div>
    </div>

            </div>
        </div>

        <div class="container mx-auto px-4 mt-24">
            <div class="subscription-faq">
                <h2 class="text-3xl font-bold text-center mb-12">Frequently Asked Questions</h2>
                
                <div class="faq-grid">
                    <div class="faq-item">
                        <span class="faq-icon">?</span>
                        <h3>Can I change plans later?</h3>
                        <p>Yes, you can upgrade, downgrade, or cancel your plan at any time.</p>
                    </div>
                    
                    <div class="faq-item">
                        <span class="faq-icon">?</span>
                        <h3>What happens if I exceed my ticket limit?</h3>
                        <p>You'll be notified when approaching your limit. Consider upgrading to continue creating tickets.</p>
                    </div>
                    
                    <div class="faq-item">
                        <span class="faq-icon">?</span>
                        <h3>Is there a contract or commitment?</h3>
                        <p>No contracts - pay monthly or annually. Cancel anytime.</p>
                    </div>
                    
                    <div class="faq-item">
                        <span class="faq-icon">?</span>
                        <h3>Do you offer refunds?</h3>
                        <p>Yes, we offer a 30-day money-back guarantee for all paid plans.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Main Container Styles */
.subscription-page {
    @apply min-h-screen w-full;
    /* keep the page container transparent so the body background image shows through */
    background: transparent;
}

.page-gradient {
    @apply py-24 px-4;
    /* subtle overlay to improve contrast over the background image */
    background: linear-gradient(180deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.03) 100%);
}

.subscription-header {
    @apply relative text-center;
    margin-bottom: 1rem;
    position: relative;
    z-index: 10;
}

.subscription-title {
    @apply text-5xl md:text-6xl font-extrabold mb-6;
    color: #ffffff;
    letter-spacing: -0.015em;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

.subscription-subtitle {
    @apply text-xl md:text-2xl font-medium mb-8;
    color: #cbd5e1;
}

.header-accent {
    @apply w-32 h-1 mx-auto;
    background: linear-gradient(90deg, #3b82f6 0%, #60a5fa 100%);
    box-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
}

.plans-container {
    @apply flex flex-col gap-8 mb-16 max-w-5xl mx-auto;
}

.plan-card {
    @apply bg-white rounded-2xl p-8 relative transition-all duration-300;
    border: 2px solid #e2e8f0;
    box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.1);
}

.plan-card:hover {
    border-color: #3b82f6;
    box-shadow: 0 20px 50px -5px rgba(59, 130, 246, 0.15);
    transform: translateY(-4px);
}

.plan-card.featured {
    @apply border-2;
    border-color: #3b82f6;
    background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 100%);
    box-shadow: 0 20px 50px -5px rgba(59, 130, 246, 0.25);
}

.plan-card.featured:hover {
    box-shadow: 0 25px 60px -5px rgba(59, 130, 246, 0.3);
}

/* Position subscription header over the free plan on wider screens */
@media (min-width: 768px) {
    .subscription-header {
        position: absolute;
        top: -4.5rem;
        left: 50%;
        transform: translateX(-50%);
        width: calc(100% - 4rem);
    }
    .free-plan-wrapper {
        padding-top: 5.5rem;
    }
}

.plan-badge {
    @apply absolute -top-4 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-blue-500 to-blue-400 text-white text-xs font-bold py-2 px-6 rounded-full;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
    letter-spacing: 0.1em;
}

.plan-header {
    @apply text-center mb-10 relative;
}

.plan-name {
    @apply text-3xl font-bold mb-4;
    color: #1e293b;
    letter-spacing: -0.015em;
}

.plan-price {
    @apply flex items-center justify-center mb-4;
}

.currency {
    @apply text-3xl font-bold mr-1;
    color: #3b82f6;
}

.amount {
    @apply text-6xl font-black;
    background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    letter-spacing: -0.025em;
}

.period {
    @apply ml-2 text-lg font-medium;
    color: #64748b;
}

.plan-description {
    @apply text-base font-medium;
    color: #475569;
}

.plan-features {
    @apply mt-10 py-8 border-t border-b;
    border-color: #e2e8f0;
}

.plan-features ul {
    @apply space-y-4;
}

.plan-features li {
    @apply flex items-start text-base font-medium;
    color: #334155;
}

.plan-features li::before {
    content: "✓";
    @apply mr-4 text-emerald-400 font-bold text-lg flex-shrink-0;
}

.plan-features li.disabled {
    @apply;
    color: #cbd5e1;
}

.plan-features li.disabled::before {
    content: "✗";
    @apply text-slate-600;
}

.plan-action {
    @apply mt-10;
}

.btn-select {
    @apply w-full py-4 px-6 font-bold text-white transition-all duration-300;
    /* exact rectangular button */
    border-radius: 0;
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
    border: none;
    cursor: pointer;
    font-size: 1rem;
    letter-spacing: 0.02em;
}

.btn-select:hover {
    background: linear-gradient(135deg, #1d4ed8 0%, #153e75 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
}

.btn-select:active {
    transform: translateY(0);
}

.btn-current {
    @apply w-full py-4 px-6 font-bold cursor-default;
    border-radius: 0;
    background: #f1f5f9;
    color: #64748b;
    border: 2px solid #cbd5e1;
}

.btn-contact {
    @apply w-full py-4 px-6 font-bold transition-all duration-300;
    border-radius: 0;
    color: #2563eb;
    border: 2px solid #3b82f6;
    background: rgba(59, 130, 246, 0.05);
    cursor: pointer;
}

.btn-contact:hover {
    background: rgba(59, 130, 246, 0.12);
    border-color: #1e40af;
}


.subscription-faq {
    /* Wider FAQ container (approx max-w-3xl) */
    max-width: 48rem; /* ~max-w-3xl */
    margin-left: auto;
    margin-right: auto;
    padding: 2.5rem 2rem; /* py-10 px-8 */
    border-radius: 1rem; /* rounded-2xl */
    background: rgba(255,255,255,0.95);
    box-shadow: 0 6px 20px rgba(2, 6, 23, 0.04);
    border: 1px solid rgba(15,23,42,0.03);
    margin-top: 1.5rem;
}

.subscription-faq h2 {
    font-size: 2rem; /* text-4xl approx */
    font-weight: 700;
    text-align: center;
    margin-bottom: 1.5rem;
    color: #0f172a;
    letter-spacing: -0.02em;
}

.faq-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
}

@media (max-width: 768px) {
    .faq-grid {
        grid-template-columns: 1fr;
    }
}

.faq-item {
    background: #ffffff;
    border-radius: 0.75rem;
    padding: 1rem;
    position: relative;
    transition: all 0.25s ease;
    border: 1px solid rgba(226,232,240,0.8);
    box-shadow: 0 6px 14px rgba(2,6,23,0.035);
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 120px;
}

.faq-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 14px 30px rgba(2,6,23,0.06);
}

.faq-icon {
    @apply w-10 h-10 flex items-center justify-center rounded-full text-lg font-bold;
    background: linear-gradient(90deg,#2563eb,#7c3aed);
    color: white;
    box-shadow: 0 6px 18px rgba(37,99,235,0.12);
    margin-bottom: 1rem;
}

.faq-item h3 {
    font-size: 1.125rem;
    font-weight: 700;
    color: #0f172a;
    margin-top: 0.5rem;
}

.faq-item p {
    color: #475569;
    margin-top: 0.5rem;
}

/* Featured button for Professional plan */
.btn-featured {
    background: linear-gradient(90deg, #7c3aed 0%, #2563eb 100%);
    color: white;
    box-shadow: 0 12px 30px rgba(37, 99, 235, 0.18);
    border: none;
    transform: translateY(0);
}

.btn-featured:hover {
    transform: translateY(-3px);
    box-shadow: 0 20px 40px rgba(37, 99, 235, 0.22);
}

.btn-featured:active {
    transform: translateY(0);
}

.faq-item h3 {
    @apply text-lg font-bold mb-3 mt-4;
    color: #111827;
}

.faq-item p {
    @apply leading-relaxed;
    color: #4b5563;
}

/* Responsive Design */
@media (max-width: 768px) {
    .subscription-title {
        @apply text-3xl md:text-4xl;
    }
    
    .subscription-subtitle {
        @apply text-base md:text-lg;
    }
    
    .plan-card {
        @apply p-6;
    }
    
    .plan-name {
        @apply text-2xl;
    }
    
    .amount {
        @apply text-4xl;
    }
}
</style>
@endsection