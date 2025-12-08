<x-dashboard-layout>
    <x-slot name="title">Admin — Payments</x-slot>

    <div class="card">
        <h3 class="text-xl font-bold mb-3">All Payments</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td>#{{ $payment->id }}</td>
                            <td>{{ optional($payment->user)->name }}</td>
                            <td>Rp{{ number_format($payment->payment_total_amount ?? 0, 0) }}</td>
                            <td>{{ $payment->payment_method ?? '—' }}</td>
                            <td><span class="badge badge-success">{{ ucfirst($payment->payment_status ?? '—') }}</span></td>
                            <td>{{ $payment->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-primary btn-sm">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $payments->links() }}
        </div>
    </div>
</x-dashboard-layout>