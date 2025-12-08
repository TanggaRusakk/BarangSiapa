<x-dashboard-layout>
    <x-slot name="title">Vendor Orders</x-slot>

    <div class="card">
        <h3 class="text-xl font-bold mb-3">Orders</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $o)
                        <tr>
                            <td>#{{ $o->id }}</td>
                            <td>{{ optional($o->user)->name ?? '—' }}</td>
                            <td>Rp{{ number_format($o->order_total_amount ?? 0, 0) }}</td>
                            <td>{{ ucfirst($o->order_status ?? '—') }}</td>
                            <td><a href="#" class="btn btn-primary btn-sm">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $orders->links() }}</div>
    </div>

</x-dashboard-layout>
