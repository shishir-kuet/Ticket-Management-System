<x-subscription-notice>
    @props(['type' => 'info'])

    <div class="subscription-notice {{ $type }}">
        <div class="subscription-notice-content">
            {{ $slot }}
        </div>
        @if($type === 'warning' || $type === 'danger')
            <a href="{{ route('subscription.plans') }}" class="upgrade-btn">Upgrade Now</a>
        @endif
    </div>

    <style>
        .subscription-notice {
            @apply fixed bottom-4 right-4 p-4 rounded-lg shadow-lg max-w-md z-50 flex items-center justify-between;
            animation: slideIn 0.3s ease-out;
        }

        .subscription-notice.info {
            @apply bg-blue-50 text-blue-700 border border-blue-200;
        }

        .subscription-notice.warning {
            @apply bg-yellow-50 text-yellow-700 border border-yellow-200;
        }

        .subscription-notice.danger {
            @apply bg-red-50 text-red-700 border border-red-200;
        }

        .subscription-notice-content {
            @apply flex-1 mr-4;
        }

        .upgrade-btn {
            @apply px-4 py-2 rounded-md text-white font-semibold text-sm transition-colors;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .upgrade-btn:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</x-subscription-notice>