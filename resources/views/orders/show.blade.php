@extends('layouts.mainlayout')

@section('content')
<div class="order-page container mx-auto px-4 sm:px-6 lg:px-8 py-5">
    <!-- Breadcrumb -->
    <div class="mb-4 text-sm">
        <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted hover-purple">Dashboard</a>
        <span class="mx-2 text-secondary">/</span>
        <a href="{{ route('orders.my-orders') }}" class="text-decoration-none text-muted hover-purple">My Orders</a>
        <span class="mx-2 text-secondary">/</span>
        <span class="text-secondary">Order #{{ $order->id }}</span>
    </div>

    <div class="row g-4">
        <!-- Order Details -->
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm mb-4" style="background: #2D2D3A; color: #E5E5E8;">
                <div class="card-header d-flex justify-content-between align-items-center" style="background: #3A3A4A; border-bottom: 1px solid #4A4A5A;">
                    <h5 class="fw-bold mb-0" style="color: #E5E5E8;">Order #{{ $order->id }}</h5>
                    <span class="badge" style="background: {{ 
                        $order->order_status === 'completed' ? '#10B981' : 
                        ($order->order_status === 'paid' ? '#3B82F6' : 
                        ($order->order_status === 'pending' ? '#F59E0B' : '#6B7280')) 
                    }}; color: white; font-weight: 600;">
                        {{ strtoupper($order->order_status ?? 'pending') }}
                    </span>
                </div>
                <div class="card-body" style="background: #2D2D3A;">
                    <p class="mb-4" style="color: #A0A0B0;">Placed on {{ $order->created_at->format('d M Y, H:i') }}</p>

                    <h6 class="fw-bold mb-3" style="color: #E5E5E8;">Order Items</h6>
                    @if($order->orderItems && $order->orderItems->count() > 0)
                        @foreach($order->orderItems as $orderItem)
                            @php
                                $itemQty = $orderItem->order_item_quantity ?? $orderItem->quantity ?? 1;
                                $itemPrice = $orderItem->order_item_price ?? $orderItem->price ?? 0;
                                $itemSubtotal = $itemPrice * $itemQty;
                            @endphp
                            <div class="d-flex gap-3 align-items-center {{ !$loop->last ? 'mb-3 pb-3 border-bottom' : '' }}">
                                @if($orderItem->item)
                                    <img src="{{ $orderItem->item->first_image_url }}" alt="{{ $orderItem->item->item_name }}" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1">
                                            <a href="{{ route('items.show', $orderItem->item->id) }}" class="text-decoration-none" style="color: #A78BFA;">{{ $orderItem->item->item_name }}</a>
                                        </h6>
                                        <p class="small mb-1" style="color: #9CA3AF;">{{ \Illuminate\Support\Str::limit($orderItem->item->item_description ?? '', 80) }}</p>
                                        <div class="d-flex gap-2 small" style="color: #A0A0B0;">
                                            <span>Quantity: {{ $itemQty }}</span>
                                            <span>×</span>
                                            <span>Rp {{ number_format($itemPrice, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <p class="fw-bold mb-0" style="color: #C4B5FD;">Rp {{ number_format($itemSubtotal, 0, ',', '.') }}</p>
                                    </div>
                                @else
                                    <div class="text-muted small">Item not found</div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No items found for this order.</p>
                    @endif

                    <!-- Rental Info (if applicable) -->
                    @if($order->rentalInfos)
                        <div class="mt-4 pt-4" style="border-top: 1px solid #4A4A5A;">
                            <h6 class="fw-bold mb-3" style="color: #A78BFA;">Rental Information</h6>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="small mb-1" style="color: #9CA3AF;">Start Date</div>
                                    <div class="fw-bold" style="color: #E5E5E8;">{{ $order->rentalInfos->start_date ? \Carbon\Carbon::parse($order->rentalInfos->start_date)->format('d M Y') : 'N/A' }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="small mb-1" style="color: #9CA3AF;">End Date</div>
                                    <div class="fw-bold" style="color: #E5E5E8;">{{ $order->rentalInfos->end_date ? \Carbon\Carbon::parse($order->rentalInfos->end_date)->format('d M Y') : 'N/A' }}</div>
                                </div>
                                <div class="col-12">
                                    <div class="small mb-1" style="color: #9CA3AF;">Status</div>
                                    <span class="badge" style="background: #3B82F6; color: white;">{{ strtoupper($order->rentalInfos->rental_status ?? 'pending') }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Payment Info -->
                    <div class="mt-4 pt-4" style="border-top: 1px solid #4A4A5A;">
                        <h6 class="fw-bold mb-3" style="color: #A78BFA;">Payment Information</h6>
                        @if($order->payments)
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="small mb-1" style="color: #9CA3AF;">Payment Method</div>
                                    <div class="fw-bold" style="color: #E5E5E8;">{{ ucfirst($order->payments->payment_method ?? 'N/A') }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="small mb-1" style="color: #9CA3AF;">Payment Status</div>
                                    <span class="badge" style="background: {{ in_array($order->payments->payment_status, ['settlement', 'capture', 'success']) ? '#10B981' : '#F59E0B' }}; color: white; font-weight: 600;">
                                        {{ strtoupper($order->payments->payment_status ?? 'PENDING') }}
                                    </span>
                                </div>
                                @if($order->payments->paid_at)
                                    <div class="col-12">
                                        <div class="small mb-1" style="color: #9CA3AF;">Paid At</div>
                                        <div class="fw-bold" style="color: #E5E5E8;">{{ $order->payments->paid_at->format('d M Y, H:i') }}</div>
                                    </div>
                                @endif
                            </div>
                            @if(in_array($order->payments->payment_status, ['pending', 'failed', 'expire']))
                                <div class="mt-3">
                                    <a href="{{ route('payment.create', $order->id) }}" class="btn btn-lg w-100 text-white" style="background: linear-gradient(135deg, #8B5CF6 0%, #A78BFA 100%); border-radius: 30px; font-weight: 600;">
                                        COMPLETE PAYMENT
                                    </a>
                                </div>
                            @elseif($order->payments->needsRemainingPayment())
                                <div class="mt-3">
                                    <div class="alert alert-warning mb-3" style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); color: #FCD34D;">
                                        <strong><i class="bi bi-exclamation-circle"></i> Pelunasan Diperlukan</strong>
                                        <p class="mb-2 mt-2 small">DP sebesar Rp {{ number_format($order->order_total_amount * 0.30, 0, ',', '.') }} sudah dibayar.</p>
                                        <p class="mb-0 small">Sisa pembayaran: <strong>Rp {{ number_format($order->payments->getRemainingAmount(), 0, ',', '.') }}</strong></p>
                                    </div>
                                    <a href="{{ route('payment.remaining', $order->id) }}" class="btn btn-lg w-100 text-white" style="background: linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%); border-radius: 30px; font-weight: 600;">
                                        <i class="bi bi-wallet2"></i> LUNASI PEMBAYARAN
                                    </a>
                                </div>
                            @endif
                        @else
                            <p class="mb-3" style="color: #9CA3AF;">Payment information not available.</p>
                            <a href="{{ route('payment.create', $order->id) }}" class="btn btn-lg w-100 text-white" style="background: linear-gradient(135deg, #8B5CF6 0%, #A78BFA 100%); border-radius: 30px; font-weight: 600;">
                                PROCEED TO PAYMENT
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm" style="background: #2D2D3A; border: 1px solid #4A4A5A;">
                <div class="card-body">
                    <h5 class="fw-bold mb-3" style="color: #A78BFA;">Order Summary</h5>
                    @php
                        $subtotal = $order->orderItems->sum(function($item) {
                            $qty = $item->order_item_quantity ?? $item->quantity ?? 1;
                            $price = $item->order_item_price ?? $item->price ?? 0;
                            return $price * $qty;
                        });
                        $serviceFee = $order->order_total_amount - $subtotal;
                    @endphp
                    <div class="d-flex justify-content-between mb-2">
                        <span style="color: #9CA3AF;">Subtotal</span>
                        <span class="fw-bold" style="color: #E5E5E8;">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span style="color: #9CA3AF;">Service Fee</span>
                        <span class="fw-bold" style="color: #E5E5E8;">Rp {{ number_format($serviceFee, 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-3" style="border-top: 1px solid #4A4A5A;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold fs-5" style="color: #E5E5E8;">Total</span>
                            <span class="fw-bold fs-4" style="background: linear-gradient(135deg, #8B5CF6 0%, #C4B5FD 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                                Rp {{ number_format($order->order_total_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
