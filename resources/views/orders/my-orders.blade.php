@extends('layouts.mainlayout')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-5">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="display-5 fw-bold mb-2" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">My Orders</h1>
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

    <!-- Orders Filter -->
    <div class="d-flex gap-2 mb-4 flex-wrap">
        <a href="{{ route('orders.my-orders') }}" class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }}" style="{{ !request('status') ? 'background: #6A38C2; border-color: #6A38C2;' : '' }}">All</a>
        <a href="{{ route('orders.my-orders', ['status' => 'pending']) }}" class="btn {{ request('status') === 'pending' ? 'btn-warning' : 'btn-outline-secondary' }}">Pending</a>
        <a href="{{ route('orders.my-orders', ['status' => 'paid']) }}" class="btn {{ request('status') === 'paid' ? 'btn-info' : 'btn-outline-secondary' }}">Paid</a>
        <a href="{{ route('orders.my-orders', ['status' => 'completed']) }}" class="btn {{ request('status') === 'completed' ? 'btn-success' : 'btn-outline-secondary' }}">Completed</a>
        <a href="{{ route('orders.my-orders', ['status' => 'cancelled']) }}" class="btn {{ request('status') === 'cancelled' ? 'btn-danger' : 'btn-outline-secondary' }}">Cancelled</a>
    </div>

    @if(isset($orders) && $orders->count() > 0)
        <div class="d-flex flex-column gap-4">
            @foreach($orders as $order)
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background: rgba(106,56,194,0.05);">
                        <div>
                            <span class="fw-bold">Order #{{ $order->id }}</span>
                            <span class="text-secondary ms-3">{{ $order->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <span class="badge {{ 
                            $order->order_status === 'completed' ? 'bg-success' : 
                            ($order->order_status === 'paid' ? 'bg-info' : 
                            ($order->order_status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary')) 
                        }}">
                            {{ ucfirst($order->order_status ?? 'pending') }}
                        </span>
                    </div>
                    <div class="card-body">
                        @if($order->orderItems && $order->orderItems->count() > 0)
                            @foreach($order->orderItems as $orderItem)
                                <div class="d-flex gap-3 align-items-center {{ !$loop->last ? 'mb-3 pb-3 border-bottom' : '' }}">
                                    @if($orderItem->item)
                                        <img src="{{ $orderItem->item->first_image_url }}" alt="{{ $orderItem->item->item_name }}" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1">
                                                <a href="{{ route('items.show', $orderItem->item->id) }}" class="text-decoration-none" style="color: #6A38C2;">
                                                    {{ $orderItem->item->item_name }}
                                                </a>
                                            </h6>
                                            <p class="text-secondary small mb-0">Quantity: {{ $orderItem->quantity }}</p>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold" style="color: #6A38C2;">Rp {{ number_format($orderItem->price * $orderItem->quantity, 0, ',', '.') }}</div>
                                        </div>
                                    @else
                                        <div class="text-secondary">Item no longer available</div>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center" style="background: rgba(106,56,194,0.02);">
                        <div>
                            <span class="text-secondary">Total:</span>
                            <span class="fw-bold fs-5" style="color: #6A38C2;">Rp {{ number_format($order->order_total_amount, 0, ',', '.') }}</span>
                            @php
                                $payment = \App\Models\Payment::where('order_id', $order->id)->where('payment_type', 'dp')->first();
                            @endphp
                            @if($payment && $payment->needsRemainingPayment())
                                <div class="mt-2">
                                    <small class="text-warning">
                                        <i class="bi bi-exclamation-circle"></i> DP sudah dibayar. Sisa: Rp {{ number_format($payment->getRemainingAmount(), 0, ',', '.') }}
                                    </small>
                                </div>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm">View Details</a>
                            @if($order->order_status === 'pending')
                                <a href="{{ route('payment.create', $order->id) }}" class="btn btn-sm" style="background: #6A38C2; color: white;">Pay Now</a>
                            @elseif($order->order_status === 'paid' && $payment && $payment->needsRemainingPayment())
                                <a href="{{ route('payment.remaining', $order->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-wallet2"></i> Lunasi Sisa
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-4" style="font-size: 64px;">📦</div>
            <h3 class="fw-bold mb-2">No Orders Yet</h3>
            <p class="text-secondary mb-4">You haven't placed any orders yet. Start shopping!</p>
            <a href="{{ route('items.index') }}" class="btn btn-lg" style="background: #6A38C2; color: white;">Browse Products</a>
        </div>
    @endif
</div>
@endsection
