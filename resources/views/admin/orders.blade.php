<x-dashboard-layout>
    <x-slot name="title">Admin — Orders</x-slot>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-900 bg-opacity-30 rounded-lg border border-green-500">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <h3 class="text-xl font-bold mb-3">All Orders</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ optional($order->user)->name }}</td>
                            <td>Rp{{ number_format($order->order_total_amount ?? 0, 0) }}</td>
                            <td><span class="badge badge-info">{{ ucfirst($order->order_status ?? '—') }}</span></td>
                            <td>{{ $order->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-primary btn-sm">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
</x-dashboard-layout>