@extends('layouts.app')

@section('title', 'Invoices - Resolve AI')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Invoices</h1>
            <p class="text-gray-600">Download and view all your invoices</p>
        </div>

        <!-- Invoices Table -->
        @if($invoices->count() > 0)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b-2 border-gray-200">
                            <tr>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Invoice Date</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Description</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Amount</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Status</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $invoice)
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                    <td class="py-4 px-6 text-gray-900 font-medium">{{ $invoice->created_at->format('M d, Y') }}</td>
                                    <td class="py-4 px-6 text-gray-700">{{ $invoice->description }}</td>
                                    <td class="py-4 px-6 font-semibold text-gray-900">৳{{ number_format($invoice->amount, 2) }}</td>
                                    <td class="py-4 px-6">
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                            Paid
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <form action="{{ route('subscription.invoice.download', $invoice->id) }}" method="GET" class="inline">
                                            <button type="submit" class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
                                                ⬇ Download
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $invoices->links() }}
            </div>
        @else
            <div class="bg-gray-50 rounded-lg p-12 text-center">
                <div class="mb-4">
                    <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <p class="text-gray-600 text-lg">No invoices available yet</p>
                <p class="text-gray-500 mt-2">Once you upgrade to a paid plan, your invoices will appear here</p>
            </div>

            <!-- Upgrade CTA -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-8 text-center">
                <h3 class="text-lg font-semibold text-blue-900 mb-3">Ready to upgrade?</h3>
                <p class="text-blue-800 mb-4">Choose from our professional or enterprise plans to get started</p>
                <a href="{{ route('subscription.plans') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    View Plans
                </a>
            </div>
        @endif

        <!-- Back Link -->
        <div class="mt-8">
            <a href="{{ route('subscription.billing') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                ← Back to Billing
            </a>
        </div>
    </div>
</div>
@endsection
