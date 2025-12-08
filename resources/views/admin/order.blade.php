<x-dashboard-layout>
    <x-slot name="title">Order #{{ $order->id }}</x-slot>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-900 bg-opacity-30 rounded-lg border border-green-500">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="card">
            <h3 class="font-bold mb-2">Order Info</h3>
            <div class="space-y-2">
                <div><strong>Order ID:</strong> #{{ $order->id }}</div>
                <div><strong>Customer:</strong> {{ optional($order->user)->name }}</div>
                <div><strong>Total:</strong> Rp{{ number_format($order->order_total_amount ?? 0, 0) }}</div>
                <div><strong>Status:</strong> {{ ucfirst($order->order_status ?? '—') }}</div>
                <div><strong>Date:</strong> {{ $order->created_at->toDayDateTimeString() }}</div>
            </div>

            <hr class="my-4">

            <h3 class="font-bold mb-2">Update Status</h3>
            <form method="POST" action="{{ route('admin.orders.update', $order->id) }}">
                @csrf
                @method('PATCH')
                <select name="order_status" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac mb-2">
                    <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Update Status</button>
            </form>
        </div>

        <div class="card lg:col-span-2">
            <h3 class="font-bold mb-3">Order Items</h3>
            <div class="space-y-3">
                @foreach($order->orderItems as $oi)
                    <div class="p-3 bg-purple-900 bg-opacity-10 rounded flex justify-between items-center">
                        <div>
                            <div class="font-bold">{{ optional($oi->item)->item_name }}</div>
                            <div class="text-sm text-soft-lilac">Quantity: {{ $oi->order_item_quantity }} × Rp{{ number_format($oi->order_item_price ?? 0, 0) }}</div>
                        </div>
                        <div class="text-right font-bold">
                            Rp{{ number_format(($oi->order_item_quantity ?? 1) * ($oi->order_item_price ?? 0), 0) }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-dashboard-layout>