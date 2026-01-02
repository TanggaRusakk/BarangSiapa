<x-dashboard-layout>
    <x-slot name="title">Ad Payment</x-slot>

    <div class="card max-w-2xl mx-auto">
        <h3 class="text-xl font-bold mb-4">Complete Payment for Advertisement</h3>

        <div class="mb-6 p-4 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac">
            <h4 class="font-bold mb-2">Payment Details</h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Midtrans Order ID:</span>
                    <span class="font-mono">{{ $payment->midtrans_order_id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Amount:</span>
                    <span class="text-green-400 font-bold">Rp {{ number_format($payment->payment_total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Status:</span>
                    <span class="px-2 py-1 rounded text-xs bg-yellow-900 bg-opacity-30 text-yellow-300">
                        {{ ucfirst($payment->payment_status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="mb-6 p-4 rounded bg-cyan-900 bg-opacity-10 border border-cyan-500 border-opacity-30">
            <h4 class="font-bold mb-2">Ad Information</h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Start Date:</span>
                    <span>{{ \Carbon\Carbon::parse($pendingAd['start_date'])->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">End Date:</span>
                    <span>{{ \Carbon\Carbon::parse($pendingAd['end_date'])->format('d M Y, H:i') }}</span>
                </div>
            </div>
        </div>

        <div class="mb-6 p-4 rounded bg-yellow-900 bg-opacity-20 border border-yellow-500">
            <p class="text-yellow-300 text-sm">
                <strong>Demo Mode:</strong> In production, this page would integrate with Midtrans payment gateway. 
                For now, click "Confirm Payment" to simulate successful payment.
            </p>
        </div>

        <form method="POST" action="{{ route('vendor.ads.payment.confirm', $payment->id) }}">
            @csrf
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Confirm Payment (Demo)</button>
                <a href="{{ route('vendor.ads.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>

        <div class="mt-6 text-xs text-gray-500">
            <p>Note: After confirming payment, your ad will be automatically created and activated.</p>
        </div>
    </div>
</x-dashboard-layout>
