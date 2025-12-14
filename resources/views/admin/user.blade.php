<x-dashboard-layout>
    @php
        use Illuminate\Support\Str;
        $user = $user ?? null;
        $orders = $orders ?? collect();
        $reviews = $reviews ?? collect();
        $payments = $payments ?? collect();
    @endphp

    <x-slot name="title">Admin — User: {{ $user->name ?? '—' }}</x-slot>

    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-4">
            <div class="card">
                <h3 class="font-bold mb-2">Profile</h3>
            <div class="space-y-2">
                <div><strong>Name:</strong> {{ $user->name ?? '—' }}</div>
                <div><strong>Email:</strong> {{ $user->email ?? '—' }}</div>
                <div><strong>Role:</strong> {{ ucfirst($user->role ?? 'user') }}</div>
                <div><strong>Joined:</strong> {{ $user && $user->created_at ? $user->created_at->toDayDateTimeString() : '—' }}</div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="card">
                <h3 class="font-bold mb-2">Recent Orders</h3>
                @if($orders->isEmpty())
                <p class="text-soft-lilac">No orders found for this user.</p>
            @else
                <div class="space-y-3">
                    @foreach($orders as $order)
                            <div class="p-3 bg-purple-900 bg-opacity-10 rounded d-flex justify-content-between align-items-center">
                            <div>
                                <div class="font-bold">Order #{{ $order->id }}</div>
                                <div class="text-sm text-soft-lilac">
                                    @foreach($order->orderItems as $oi)
                                        <span>{{ optional($oi->item)->item_name }} (x{{ $oi->order_item_quantity }})</span>@if(! $loop->last), @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold">Rp{{ number_format($order->order_total_amount ?? 0, 0) }}</div>
                                <div class="text-sm text-soft-lilac">Status: {{ $order->order_status ?? '—' }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <hr class="my-4">

            <h3 class="font-bold mb-2">Recent Reviews</h3>
            @if($reviews->isEmpty())
                <p class="text-soft-lilac">No reviews</p>
            @else
                <div class="space-y-2">
                    @foreach($reviews as $rev)
                            <div class="p-3 bg-purple-900 bg-opacity-10 rounded">
                            <div class="font-bold">{{ optional($rev->item)->item_name ?? '—' }}</div>
                            <div class="text-sm text-soft-lilac">{{ Str::limit($rev->review_text ?? '', 180) }}</div>
                        </div>
                    @endforeach
                </div>
            @endif

            <hr class="my-4">

            <h3 class="font-bold mb-2">Recent Payments</h3>
            @if($payments->isEmpty())
                <p class="text-soft-lilac">No payments</p>
            @else
                <div class="space-y-2">
                    @foreach($payments as $p)
                            <div class="d-flex justify-content-between align-items-center p-3 bg-purple-900 bg-opacity-10 rounded">
                            <div>
                                <div class="font-bold">Payment #{{ $p->id }}</div>
                                <div class="text-sm text-soft-lilac">Method: {{ $p->payment_method ?? '—' }}</div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold">Rp{{ number_format($p->payment_total_amount ?? 0, 0) }}</div>
                                <div class="text-sm text-soft-lilac">Status: {{ $p->payment_status ?? '—' }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>
