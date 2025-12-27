@extends('layouts.mainlayout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl fw-bold mb-2" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">My Orders</h1>
        <p class="text-secondary">Track and manage your orders</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($orders->count() > 0)
        <div class="row g-4">
            @foreach($orders as $order)
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <!-- Order Header -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="fw-bold mb-1">Order #{{ $order->id }}</h5>
                                    <p class="text-muted mb-0">{{ $order->ordered_at ? $order->ordered_at->format('d M Y, H:i') : 'N/A' }}</p>
                                </div>
                                <div class="text-end">
                                    <span class="badge {{ $order->order_status === 'completed' ? 'bg-success' : ($order->order_status === 'pending' ? 'bg-warning' : 'bg-secondary') }} px-3 py-2">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                    @if($order->payments)
                                        <span class="badge {{ $order->payments->payment_status === 'settlement' || $order->payments->payment_status === 'capture' || $order->payments->payment_status === 'success' ? 'bg-success' : 'bg-warning' }} px-3 py-2 ms-2">
                                            Payment: {{ ucfirst($order->payments->payment_status ?? 'pending') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Order Items -->
                            <div class="mb-3">
                                @foreach($order->orderItems as $orderItem)
                                    <div class="d-flex align-items-center gap-3 mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                        @php
                                            $imageUrl = $orderItem->item->first_image_url ?? (file_exists(public_path('images/items/item_placeholder.jpg')) ? asset('images/items/item_placeholder.jpg') : asset('images/items/item_placeholder.png'));
                                        @endphp
                                        <img src="{{ $imageUrl }}" alt="{{ $orderItem->item->item_name }}" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1">{{ $orderItem->item->item_name }}</h6>
                                            <p class="text-muted mb-0">Quantity: {{ $orderItem->quantity }}</p>
                                            <p class="text-muted mb-0">Price: Rp {{ number_format($orderItem->price, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-end">
                                            <p class="fw-bold mb-0" style="color: #6A38C2;">Rp {{ number_format($orderItem->price * $orderItem->quantity, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Order Total -->
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <div>
                                    <span class="fw-bold">Total Amount:</span>
                                    <span class="fs-5 fw-bold ms-2" style="color: #6A38C2;">Rp {{ number_format($order->order_total_amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm">View Details</a>
                                    @if($order->payments && in_array($order->payments->payment_status, ['pending', 'failed']))
                                        <a href="{{ route('payment.create', $order->id) }}" class="btn btn-sm text-white" style="background: #FF3CAC;">Pay Now</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @else
        <div class="card shadow-sm text-center py-5">
            <div class="card-body">
                <svg class="mx-auto mb-3" style="width: 80px; height: 80px; color: #6A38C2;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <h4 class="fw-bold mb-2">No Orders Yet</h4>
                <p class="text-muted mb-4">You haven't placed any orders yet. Start shopping now!</p>
                <a href="{{ route('items.index') }}" class="btn btn-lg text-white" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%);">Browse Products</a>
            </div>
        </div>
    @endif
</div>
@endsection
