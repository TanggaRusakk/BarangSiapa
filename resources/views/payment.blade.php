@extends('layouts.mainlayout')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <div class="mb-6 text-sm text-secondary">
        <a href="{{ route('orders.my-orders') }}" class="hover:text-primary" style="color: #6A38C2;">My Orders</a>
        <span class="mx-2">/</span>
        <a href="{{ route('orders.show', $order->id) }}" class="hover:text-primary" style="color: #6A38C2;">Order #{{ $order->id }}</a>
        <span class="mx-2">/</span>
        <span class="fw-bold">Payment</span>
    </div>

    <h1 class="text-3xl fw-bold mb-6" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Complete Your Payment</h1>

    <div class="row g-4">
        <!-- Order Summary -->
        <div class="col-lg-7">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-4" style="color: #6A38C2;">Order Summary</h5>
                    
                    <!-- Order Items -->
                    @foreach($order->orderItems as $orderItem)
                        <div class="d-flex align-items-center gap-3 mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            @php
                                $imageUrl = $orderItem->item->first_image_url ?? (file_exists(public_path('images/items/item_placeholder.jpg')) ? asset('images/items/item_placeholder.jpg') : asset('images/items/item_placeholder.png'));
                            @endphp
                            <img src="{{ $imageUrl }}" alt="{{ $orderItem->item->item_name }}" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">{{ $orderItem->item->item_name }}</h6>
                                <p class="text-muted mb-0">Quantity: {{ $orderItem->quantity }} × Rp {{ number_format($orderItem->price, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-end">
                                <p class="fw-bold mb-0" style="color: #6A38C2;">Rp {{ number_format($orderItem->price * $orderItem->quantity, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach

                    <!-- Total -->
                    @php
                        $subtotal = $order->orderItems->sum(function($item) {
                            return $item->price * $item->quantity;
                        });
                        $serviceFee = $order->order_total_amount - $subtotal;
                    @endphp

                    <div class="border-top pt-3 mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 text-muted">
                            <span>Service Fee</span>
                            <span>Rp {{ number_format($serviceFee, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-top pt-2 mt-2">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold fs-4">Total Amount</span>
                                <span class="fw-bold fs-4" style="color: #6A38C2;">Rp {{ number_format($order->order_total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Section -->
        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-4" style="color: #6A38C2;">Payment Details</h5>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Order ID</span>
                            <span class="fw-bold">#{{ $order->id }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Payment Method</span>
                            <span class="fw-bold">Midtrans</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Status</span>
                            <span class="badge bg-warning">{{ ucfirst($payment->payment_status ?? 'pending') }}</span>
                        </div>
                    </div>

                    <!-- Payment Button -->
                    <button id="pay-button" class="btn btn-lg w-100 text-white" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%);">
                        <svg class="me-2" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Pay Now - Rp {{ number_format($order->order_total_amount, 0, ',', '.') }}
                    </button>

                    <div class="text-center mt-3">
                        <small class="text-muted">Secure payment powered by Midtrans</small>
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3" style="color: #6A38C2;">
                        <svg class="me-2" style="width: 20px; height: 20px; display: inline-block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Payment Information
                    </h6>
                    <ul class="list-unstyled small text-muted mb-0">
                        <li class="mb-2">✓ Secure payment gateway</li>
                        <li class="mb-2">✓ Multiple payment methods available</li>
                        <li class="mb-2">✓ Instant confirmation</li>
                        <li class="mb-0">✓ 24/7 customer support</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Midtrans Snap Script -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

@push('scripts')
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function(){
        // Show loading state
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...';
        
        // Call Midtrans Snap
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                console.log('Payment success:', result);
                window.location.href = '{{ route("payment.success") }}?order_id=' + result.order_id;
            },
            onPending: function(result){
                console.log('Payment pending:', result);
                window.location.href = '{{ route("payment.pending") }}?order_id=' + result.order_id;
            },
            onError: function(result){
                console.log('Payment error:', result);
                window.location.href = '{{ route("payment.error") }}?order_id=' + result.order_id;
            },
            onClose: function(){
                console.log('Payment popup closed');
                // Re-enable button
                const btn = document.getElementById('pay-button');
                btn.disabled = false;
                btn.innerHTML = '<svg class="me-2" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>Pay Now - Rp {{ number_format($order->order_total_amount, 0, ",", ".") }}';
            }
        });
    };
</script>
@endpush
@endsection
