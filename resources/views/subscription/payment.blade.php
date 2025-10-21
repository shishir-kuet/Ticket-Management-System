@extends('layouts.app')

@section('title', 'Payment - Resolve AI')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-12 text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Complete Your Payment</h1>
            <p class="text-gray-600">Choose your preferred payment method</p>
        </div>

        <!-- Payment Summary -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Order Summary</h2>
            
            <div class="space-y-4 mb-6 pb-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="text-gray-700">Plan:</span>
                    <span class="font-semibold text-gray-900 capitalize">{{ ucfirst($plan) }} Plan</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-700">Billing Cycle:</span>
                    <span class="font-semibold text-gray-900">Monthly</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-700">Duration:</span>
                    <span class="font-semibold text-gray-900">1 Month</span>
                </div>
            </div>

            <div class="flex justify-between items-center mb-6">
                <span class="text-lg font-semibold text-gray-900">Total Amount:</span>
                <span class="text-3xl font-bold text-blue-600">৳{{ number_format($amount, 2) }}</span>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-800">
                    <strong>Note:</strong> After successful payment, your {{ ucfirst($plan) }} plan will be activated immediately and you can start using all premium features.
                </p>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Select Payment Method</h2>

            <form id="paymentForm" action="{{ route('subscription.process-payment') }}" method="POST">
                @csrf
                <input type="hidden" name="plan" value="{{ $plan }}">
                <input type="hidden" name="transaction_id" value="{{ $transactionId }}">
                <input type="hidden" name="amount" value="{{ $amount }}">

                <div class="space-y-4 mb-8">
                    <!-- bKash -->
                    <label class="relative flex items-center p-6 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition" onclick="selectPaymentMethod('bkash')">
                        <input type="radio" name="payment_method" value="bkash" class="w-5 h-5 text-blue-600" required>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center gap-3">
                                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor">
                                    <circle cx="12" cy="12" r="10" fill="#E2136E"/>
                                    <text x="12" y="15" font-size="14" font-weight="bold" fill="white" text-anchor="middle">B</text>
                                </svg>
                                <span class="font-bold text-lg text-gray-900">bKash</span>
                            </div>
                            <p class="text-gray-600 text-sm mt-1">Pay using bKash mobile app or USSD</p>
                        </div>
                    </label>

                    <!-- Nagad -->
                    <label class="relative flex items-center p-6 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition" onclick="selectPaymentMethod('nagad')">
                        <input type="radio" name="payment_method" value="nagad" class="w-5 h-5 text-blue-600" required>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center gap-3">
                                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor">
                                    <circle cx="12" cy="12" r="10" fill="#FF6B00"/>
                                    <text x="12" y="15" font-size="14" font-weight="bold" fill="white" text-anchor="middle">N</text>
                                </svg>
                                <span class="font-bold text-lg text-gray-900">Nagad</span>
                            </div>
                            <p class="text-gray-600 text-sm mt-1">Pay using Nagad mobile banking</p>
                        </div>
                    </label>

                    <!-- Rocket -->
                    <label class="relative flex items-center p-6 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition" onclick="selectPaymentMethod('rocket')">
                        <input type="radio" name="payment_method" value="rocket" class="w-5 h-5 text-blue-600" required>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center gap-3">
                                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor">
                                    <circle cx="12" cy="12" r="10" fill="#005DAA"/>
                                    <text x="12" y="15" font-size="14" font-weight="bold" fill="white" text-anchor="middle">R</text>
                                </svg>
                                <span class="font-bold text-lg text-gray-900">Rocket</span>
                            </div>
                            <p class="text-gray-600 text-sm mt-1">Pay using Dutch-Bangla Rocket</p>
                        </div>
                    </label>

                    <!-- Bank Transfer -->
                    <label class="relative flex items-center p-6 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition" onclick="selectPaymentMethod('bank_transfer')">
                        <input type="radio" name="payment_method" value="bank_transfer" class="w-5 h-5 text-blue-600" required>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center gap-3">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-8 h-8 text-gray-900">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18M3 10h18M5 14h14a2 2 0 012 2v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4a2 2 0 012-2z"></path>
                                </svg>
                                <span class="font-bold text-lg text-gray-900">Bank Transfer</span>
                            </div>
                            <p class="text-gray-600 text-sm mt-1">Direct bank transfer (manual verification)</p>
                        </div>
                    </label>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                        Continue to Payment
                    </button>
                    <a href="{{ route('subscription.plans') }}" class="flex-1 px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- FAQ Section -->
        <div class="mt-12 bg-gray-50 rounded-lg p-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Payment FAQ</h3>
            
            <div class="space-y-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Is my payment secure?</h4>
                    <p class="text-gray-600">Yes, all payments are processed through secure and encrypted channels. We use industry-standard security protocols.</p>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">What happens after payment?</h4>
                    <p class="text-gray-600">After successful payment, your subscription will be activated immediately and you'll have access to all premium features.</p>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Can I change my plan after payment?</h4>
                    <p class="text-gray-600">Yes, you can upgrade, downgrade, or cancel your plan anytime from your billing dashboard.</p>
                </div>

                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">What if payment fails?</h4>
                    <p class="text-gray-600">If your payment fails, you can retry with the same or different payment method. No charges will be made.</p>
                </div>
            </div>
        </div>

        <!-- Support -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
            <p class="text-blue-900 mb-3">Need help with payment?</p>
            <a href="{{ route('help') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                Contact Support →
            </a>
        </div>
    </div>
</div>

<script>
function selectPaymentMethod(method) {
    document.querySelector(`input[value="${method}"]`).checked = true;
}

document.getElementById('paymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    
    // Show a confirmation message
    alert(`Payment method selected: ${paymentMethod.toUpperCase()}\n\nIn a production environment, you would be redirected to ${paymentMethod} payment gateway.\n\nFor now, proceeding with payment processing...`);
    
    // Submit the form
    this.submit();
});
</script>
@endsection
