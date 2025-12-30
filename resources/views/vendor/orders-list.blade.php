<x-dashboard-layout>
    <x-slot name="title">Vendor Orders</x-slot>

    <div class="card p-4">
        <h3 class="h4 fw-bold mb-4 text-white">Orders</h3>
        
        @if($orders->count() > 0)
            <div class="d-flex flex-column gap-3">
                @foreach($orders as $o)
                    <div class="p-3 rounded" style="background: rgba(106,56,194,0.1);" x-data="{ expanded: false }">
                        <div class="d-flex justify-content-between align-items-center" @click="expanded = !expanded" style="cursor:pointer;">
                            <div class="d-flex align-items-center gap-3">
                                <div class="text-white fw-bold">#{{ $o->id }}</div>
                                <div>
                                    <div class="text-white">{{ optional($o->user)->name ?? '—' }}</div>
                                    <div class="small text-secondary">{{ $o->created_at ? $o->created_at->format('d M Y H:i') : '—' }}</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="text-end">
                                    <div class="fw-bold text-white">Rp{{ number_format($o->total_amount ?? $o->order_total_amount ?? 0, 0) }}</div>
                                    <span class="badge {{ $o->order_status === 'completed' ? 'bg-success' : ($o->order_status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary') }}">{{ ucfirst($o->order_status ?? '—') }}</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-secondary" :class="expanded ? 'rotate-180' : ''" style="transition: transform 0.2s;">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </div>
                        </div>
                        
                        <div x-show="expanded" x-collapse class="mt-3 pt-3 border-top border-secondary">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="fw-bold text-white mb-2">Order Items:</h6>
                                    @if($o->orderItems && $o->orderItems->count())
                                        <div class="d-flex flex-column gap-2">
                                            @foreach($o->orderItems as $oi)
                                                <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background: rgba(255,255,255,0.05);">
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if($oi->item && $oi->item->first_image_url)
                                                            <img src="{{ $oi->item->first_image_url }}" alt="" class="rounded" style="width:50px;height:50px;object-fit:cover;">
                                                        @endif
                                                        <div>
                                                            <div class="text-white">{{ optional($oi->item)->item_name ?? 'Unknown Item' }}</div>
                                                            <div class="small text-secondary">Qty: {{ $oi->order_item_quantity ?? $oi->quantity ?? 1 }} × Rp{{ number_format($oi->order_item_price ?? $oi->price ?? 0, 0) }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="text-white fw-bold">Rp{{ number_format(($oi->order_item_quantity ?? 1) * ($oi->order_item_price ?? 0), 0) }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-secondary small mb-0">No items in this order</p>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <h6 class="fw-bold text-white mb-2">Customer Info:</h6>
                                    <div class="small text-secondary">
                                        <div class="mb-1"><strong>Name:</strong> {{ optional($o->user)->name ?? '—' }}</div>
                                        <div class="mb-1"><strong>Email:</strong> {{ optional($o->user)->email ?? '—' }}</div>
                                        <div class="mb-1"><strong>Order Type:</strong> {{ ucfirst($o->order_type ?? '—') }}</div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <label class="small text-secondary mb-1">Update Status:</label>
                                        <form method="POST" action="{{ route('vendor.orders.updateStatus', $o->id) }}" class="d-flex gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="form-select form-select-sm" style="background: rgba(255,255,255,0.1); color: white; border-color: rgba(106,56,194,0.5);">
                                                <option value="pending" {{ $o->order_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="processing" {{ $o->order_status === 'processing' ? 'selected' : '' }}>Processing</option>
                                                <option value="shipped" {{ $o->order_status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                                <option value="completed" {{ $o->order_status === 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="cancelled" {{ $o->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <div class="text-4xl mb-2">📦</div>
                <p class="text-secondary mb-0">No orders yet</p>
            </div>
        @endif

        <div class="mt-4">{{ $orders->links() }}</div>
    </div>

    <style>
        .rotate-180 { transform: rotate(180deg); }
        [x-cloak] { display: none !important; }
    </style>
</x-dashboard-layout>
