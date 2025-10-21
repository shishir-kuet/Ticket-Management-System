@extends('layouts.app')

@section('title', 'Billing & Subscription - Resolve AI')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Billing & Subscription</h1>
            <p class="text-gray-600">Manage your subscription and view billing history</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
                {{ session('error') }}
            </div>
        @endif

        @if(session('info'))
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg text-blue-800">
                {{ session('info') }}
            </div>
        @endif

        <!-- Current Subscription -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Current Subscription</h2>
            
            @if($subscription && $subscription->plan_name !== 'free')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <p class="text-gray-600 mb-2">Plan:</p>
                        <p class="text-3xl font-bold text-blue-600 capitalize">{{ $subscription->plan_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 mb-2">Billing Cycle:</p>
                        <p class="text-lg font-semibold text-gray-900 capitalize">{{ $subscription->billing_cycle }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 mb-2">Monthly Price:</p>
                        <p class="text-lg font-semibold text-gray-900">৳{{ number_format($subscription->price, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 mb-2">Renewal Status:</p>
                        <p class="text-lg font-semibold">
                            <span class="@if($subscription->auto_renew) text-green-600 @else text-red-600 @endif">
                                @if($subscription->auto_renew)
                                    ✓ Auto-Renew Enabled
                                @else
                                    ✗ Auto-Renew Disabled
                                @endif
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600 mb-2">Start Date:</p>
                        <p class="text-lg font-semibold text-gray-900">
                            @if($subscription->starts_at)
                                {{ $subscription->starts_at->format('M d, Y') }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600 mb-2">Renewal Date:</p>
                        <p class="text-lg font-semibold text-gray-900">
                            @if($subscription->ends_at)
                                {{ $subscription->ends_at->format('M d, Y') }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>

                <div class="mt-8 flex gap-4">
                    <a href="{{ route('subscription.plans') }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Change Plan
                    </a>
                    @if($subscription->plan_name !== 'free')
                        <form action="{{ route('subscription.cancel') }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to cancel your subscription?');">
                            @csrf
                            <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                Cancel Subscription
                            </button>
                        </form>
                        @if($subscription->plan_name !== 'professional')
                            <form action="{{ route('subscription.downgrade') }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to downgrade to the Free plan?');">
                                @csrf
                                <button type="submit" class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">
                                    Downgrade to Free
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            @else
                <div class="bg-gray-50 p-8 rounded-lg text-center">
                    <p class="text-gray-600 mb-4">You are currently on the <strong>Free Plan</strong></p>
                    <a href="{{ route('subscription.plans') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Upgrade Your Plan
                    </a>
                </div>
            @endif
        </div>

        <!-- Billing History -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Billing History</h2>
            
            @if($transactions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="text-left py-4 px-4 font-semibold text-gray-700">Date</th>
                                <th class="text-left py-4 px-4 font-semibold text-gray-700">Description</th>
                                <th class="text-left py-4 px-4 font-semibold text-gray-700">Amount</th>
                                <th class="text-left py-4 px-4 font-semibold text-gray-700">Status</th>
                                <th class="text-left py-4 px-4 font-semibold text-gray-700">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="py-4 px-4 text-gray-900">{{ $transaction->created_at->format('M d, Y') }}</td>
                                    <td class="py-4 px-4 text-gray-700">{{ $transaction->description }}</td>
                                    <td class="py-4 px-4 font-semibold text-gray-900">৳{{ number_format($transaction->amount, 2) }}</td>
                                    <td class="py-4 px-4">
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                                            @if($transaction->status === 'completed')
                                                bg-green-100 text-green-800
                                            @elseif($transaction->status === 'pending')
                                                bg-yellow-100 text-yellow-800
                                            @else
                                                bg-red-100 text-red-800
                                            @endif
                                        ">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">
                                        @if($transaction->status === 'completed')
                                            <form action="{{ route('subscription.invoice.download', $transaction->id) }}" method="GET" class="inline">
                                                <button type="submit" class="text-blue-600 hover:text-blue-800 font-semibold">
                                                    Download Invoice
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $transactions->links() }}
                </div>
            @else
                <div class="bg-gray-50 p-8 rounded-lg text-center">
                    <p class="text-gray-600">No billing history available</p>
                </div>
            @endif
        </div>

        <!-- Help Section -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-8 mt-8">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">Need Help?</h3>
            <p class="text-blue-800 mb-4">If you have any questions about your subscription or billing, please contact our support team.</p>
            <a href="{{ route('help') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                Visit Help Center →
            </a>
        </div>
    </div>
</div>
@endsection
