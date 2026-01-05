<x-dashboard-layout>
    @php
        $user = $user ?? null;
        $orders = $orders ?? collect();
        $reviews = $reviews ?? collect();
        $payments = $payments ?? collect();
    @endphp

    <x-slot name="title">Admin — User: {{ $user->name ?? '—' }}</x-slot>

    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-4">
            <div class="card p-4">
                <h3 class="fw-bold mb-3 text-black">Profile</h3>
                <div class="d-flex flex-column gap-2">
                    <div><strong>Name:</strong> {{ $user->name ?? '—' }}</div>
                    <div><strong>Email:</strong> {{ $user->email ?? '—' }}</div>
                    <div><strong>Role:</strong> {{ ucfirst($user->role ?? 'user') }}</div>
                    <div><strong>Joined:</strong> {{ $user && $user->created_at ? $user->created_at->toDayDateTimeString() : '—' }}</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="card p-4">
                <h3 class="fw-bold mb-3 text-black">Recent Orders</h3>
                @if($orders->isEmpty())
                    <p class="text-secondary">No orders found for this user.</p>
                @else
                    <div class="d-flex flex-column gap-3">
                        @foreach($orders as $order)
                            <div class="p-3 rounded" style="background: rgba(106,56,194,0.1);" x-data="{ expanded: false }">
                                <div class="d-flex justify-content-between align-items-center cursor-pointer" @click="expanded = !expanded" style="cursor:pointer;">
                                    <div>
                                        <div class="fw-bold text-black">Order #{{ $order->id }}</div>
                                        <div class="small text-secondary">{{ $order->created_at ? $order->created_at->format('d M Y H:i') : '—' }}</div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="text-end">
                                            <div class="fw-bold text-black">Rp{{ number_format($order->total_amount ?? $order->order_total_amount ?? 0, 0) }}</div>
                                            <span class="badge {{ $order->order_status === 'completed' ? 'bg-success' : ($order->order_status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary') }}">{{ ucfirst($order->order_status ?? '—') }}</span>
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-secondary" :class="expanded ? 'rotate-180' : ''" style="transition: transform 0.2s;">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </div>
                                </div>
                                
                                <div x-show="expanded" x-collapse class="mt-3 pt-3 border-top border-secondary">
                                    <h6 class="fw-bold text-black mb-2">Order Items:</h6>
                                    @if($order->orderItems && $order->orderItems->count())
                                        <div class="d-flex flex-column gap-2">
                                            @foreach($order->orderItems as $oi)
                                                <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background: rgba(255,255,255,0.05);">
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if($oi->item && $oi->item->first_image_url)
                                                            <img src="{{ $oi->item->first_image_url }}" alt="" class="rounded" style="width:40px;height:40px;object-fit:cover;">
                                                        @endif
                                                        <div>
                                                            <div class="text-black">{{ optional($oi->item)->item_name ?? 'Unknown Item' }}</div>
                                                            <div class="small text-secondary">Qty: {{ $oi->order_item_quantity ?? $oi->quantity ?? 1 }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="text-black">Rp{{ number_format($oi->order_item_price ?? $oi->price ?? 0, 0) }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-secondary small mb-0">No items in this order</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <hr class="my-4 border-secondary">

                <h3 class="fw-bold mb-3 text-black">Recent Reviews</h3>
                @if($reviews->isEmpty())
                    <p class="text-secondary">No reviews</p>
                @else
                    <div class="d-flex flex-column gap-2">
                        @foreach($reviews as $rev)
                            <div class="p-3 rounded" style="background: rgba(106,56,194,0.1);">
                                <div class="fw-bold text-black">{{ optional($rev->item)->item_name ?? '—' }}</div>
                                <div class="d-flex align-items-center gap-1 my-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span style="color: {{ $i <= ($rev->rating ?? 0) ? '#FFD700' : '#555' }};">★</span>
                                    @endfor
                                </div>
                                <div class="small text-secondary">{{ \Illuminate\Support\Str::limit($rev->review_text ?? $rev->comment ?? '', 180) }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <hr class="my-4 border-secondary">

                <h3 class="fw-bold mb-3 text-black">Recent Payments</h3>
                @if($payments->isEmpty())
                    <p class="text-secondary">No payments</p>
                @else
                    <div class="d-flex flex-column gap-2">
                        @foreach($payments as $p)
                            <div class="d-flex justify-content-between align-items-center p-3 rounded" style="background: rgba(106,56,194,0.1);">
                                <div>
                                    <div class="fw-bold text-black">Payment #{{ $p->id }}</div>
                                    <div class="small text-secondary">Method: {{ $p->payment_method ?? '—' }}</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-black">Rp{{ number_format($p->payment_total_amount ?? $p->amount ?? 0, 0) }}</div>
                                    <span class="badge {{ $p->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">{{ ucfirst($p->payment_status ?? '—') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-dashboard-layout>
