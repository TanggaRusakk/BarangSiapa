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
                    <span class="px-2 py-1 rounded text-xs {{ $payment->payment_status === 'settlement' ? 'bg-success text-white' : 'bg-yellow-900 bg-opacity-30 text-yellow-300' }}">
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
                    <span>{{ \Carbon\Carbon::parse($ad->start_date)->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">End Date:</span>
                    <span>{{ \Carbon\Carbon::parse($ad->end_date)->format('d M Y, H:i') }}</span>
                </div>
            </div>
        </div>

        @if(isset($snapToken) && $snapToken)
            <div class="mb-6">
                <button id="pay-button" class="btn btn-lg w-100 text-white" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); font-weight: 600;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Pay Now - Rp {{ number_format($payment->payment_total_amount, 0, ',', '.') }}
                </button>
                <a href="{{ route('vendor.ads.index') }}" class="btn btn-secondary mt-3 w-100">Cancel</a>
            </div>

            <!-- Midtrans Snap Script -->
            <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
            <script>
                document.getElementById('pay-button').onclick = function(){
                    // Show loading state
                    this.disabled = true;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...';
                    
                    // Call Midtrans Snap
                    snap.pay('{{ $snapToken }}', {
                        onSuccess: function(result){
                            console.log('Payment success:', result);
                            window.location.href = '{{ route('payment.success') }}?order_id=' + result.order_id;
                        },
                        onPending: function(result){
                            console.log('Payment pending:', result);
                            window.location.href = '{{ route('payment.pending') }}?order_id=' + result.order_id;
                        },
                        onError: function(result){
                            console.log('Payment error:', result);
                            window.location.href = '{{ route('payment.error') }}?order_id=' + result.order_id;
                        },
                        onClose: function(){
                            console.log('Payment popup closed');
                            // Re-enable button
                            const btn = document.getElementById('pay-button');
                            btn.disabled = false;
                            btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>Pay Now - Rp {{ number_format($payment->payment_total_amount, 0, ',', '.') }}';
                        }
                    });
                };
            </script>
        @else
            <div class="alert alert-danger">
                <strong>Error:</strong> Failed to initialize payment gateway. Please try again or contact support.
            </div>
            <a href="{{ route('vendor.ads.create') }}" class="btn btn-secondary">Back to Create Ad</a>
        @endif

        <div class="mt-6 p-3 rounded bg-cyan-900 bg-opacity-10 border border-cyan-500 border-opacity-30">
            <p class="text-cyan-300 text-sm mb-0">
                <strong>💳 Secure Payment:</strong> Your payment is processed securely through Midtrans payment gateway.
            </p>
        </div>
    </div>
</x-dashboard-layout>
