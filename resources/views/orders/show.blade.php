@extends('layouts.mainlayout')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <div class="mb-4">
        <a href="{{ route('orders.my-orders') }}" class="text-decoration-none" style="color: #6A38C2;">← Back to My Orders</a>
    </div>

    <!-- Order Header -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h2 class="fw-bold mb-1" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Order #{{ $order->id }}</h2>
                    <p class="text-muted mb-0">Ordered on {{ $order->ordered_at ? $order->ordered_at->format('d M Y, H:i') : 'N/A' }}</p>
                </div>
                <div>
                    <span class="badge {{ $order->order_status === 'completed' ? 'bg-success' : ($order->order_status === 'pending' ? 'bg-warning' : 'bg-secondary') }} px-3 py-2">
                        {{ ucfirst($order->order_status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-4" style="color: #6A38C2;">Order Items</h5>
            @foreach($order->orderItems as $orderItem)
                <div class="d-flex align-items-center gap-3 mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    @php
                        $imageUrl = $orderItem->item->first_image_url ?? (file_exists(public_path('images/items/item_placeholder.jpg')) ? asset('images/items/item_placeholder.jpg') : asset('images/items/item_placeholder.png'));
                    @endphp
                    <img src="{{ $imageUrl }}" alt="{{ $orderItem->item->item_name }}" class="rounded" style="width: 100px; height: 100px; object-fit: cover;">
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1">
                            <a href="{{ route('items.show', $orderItem->item->id) }}" class="text-decoration-none" style="color: #6A38C2;">{{ $orderItem->item->item_name }}</a>
                        </h6>
                        <p class="text-muted mb-1">{{ \Illuminate\Support\Str::limit($orderItem->item->item_description ?? '', 100) }}</p>
                        <div class="d-flex gap-3">
                            <span class="text-muted">Quantity: {{ $orderItem->quantity }}</span>
                            <span class="text-muted">Price: Rp {{ number_format($orderItem->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="text-end">
                        <p class="fw-bold mb-0 fs-5" style="color: #6A38C2;">Rp {{ number_format($orderItem->price * $orderItem->quantity, 0, ',', '.') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Rental Info (if applicable) -->
    @if($order->rentalInfos)
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3" style="color: #6A38C2;">Rental Information</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Start Date:</strong> {{ $order->rentalInfos->rental_start_date ? \Carbon\Carbon::parse($order->rentalInfos->rental_start_date)->format('d M Y') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2"><strong>End Date:</strong> {{ $order->rentalInfos->rental_end_date ? \Carbon\Carbon::parse($order->rentalInfos->rental_end_date)->format('d M Y') : 'N/A' }}</p>
                    </div>
                    <div class="col-12">
                        <p class="mb-0"><strong>Status:</strong> <span class="badge bg-info">{{ ucfirst($order->rentalInfos->rental_status ?? 'pending') }}</span></p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Payment Info -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3" style="color: #6A38C2;">Payment Information</h5>
            @if($order->payments)
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <strong>Payment Method:</strong> {{ ucfirst($order->payments->payment_method ?? 'N/A') }}
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Payment Status:</strong> 
                        <span class="badge {{ $order->payments->payment_status === 'settlement' || $order->payments->payment_status === 'capture' || $order->payments->payment_status === 'success' ? 'bg-success' : 'bg-warning' }}">
                            {{ ucfirst($order->payments->payment_status ?? 'pending') }}
                        </span>
                    </div>
                    @if($order->payments->paid_at)
                        <div class="col-12 mb-2">
                            <strong>Paid At:</strong> {{ $order->payments->paid_at->format('d M Y, H:i') }}
                        </div>
                    @endif
                </div>
                @if(in_array($order->payments->payment_status, ['pending', 'failed']))
                    <div class="mt-3">
                        <a href="{{ route('payment.create', $order->id) }}" class="btn text-white" style="background: #FF3CAC;">Complete Payment</a>
                    </div>
                @endif
            @else
                <p class="text-muted mb-3">Payment information not available.</p>
                <a href="{{ route('payment.create', $order->id) }}" class="btn text-white" style="background: #FF3CAC;">Proceed to Payment</a>
            @endif
        </div>
    </div>

    <!-- Order Summary -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="fw-bold mb-3" style="color: #6A38C2;">Order Summary</h5>
            @php
                $subtotal = $order->orderItems->sum(function($item) {
                    return $item->price * $item->quantity;
                });
                $serviceFee = $order->order_total_amount - $subtotal;
            @endphp
            <div class="d-flex justify-content-between mb-2">
                <span>Subtotal</span>
                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2 text-muted">
                <span>Service Fee</span>
                <span>Rp {{ number_format($serviceFee, 0, ',', '.') }}</span>
            </div>
            <div class="border-top pt-3 mt-3">
                <div class="d-flex justify-content-between">
                    <span class="fw-bold fs-5">Total</span>
                    <span class="fw-bold fs-5" style="color: #6A38C2;">Rp {{ number_format($order->order_total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
