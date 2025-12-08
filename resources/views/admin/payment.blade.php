<x-dashboard-layout>
    <x-slot name="title">Payment #{{ $payment->id }}</x-slot>

    <div class="card">
        <h3 class="font-bold mb-3">Payment Details</h3>
        <div class="space-y-2">
            <div><strong>Payment ID:</strong> #{{ $payment->id }}</div>
            <div><strong>User:</strong> {{ optional($payment->user)->name }} ({{ optional($payment->user)->email }})</div>
            <div><strong>Amount:</strong> Rp{{ number_format($payment->payment_total_amount ?? 0, 0) }}</div>
            <div><strong>Method:</strong> {{ $payment->payment_method ?? '—' }}</div>
            <div><strong>Status:</strong> {{ ucfirst($payment->payment_status ?? '—') }}</div>
            <div><strong>Date:</strong> {{ $payment->created_at->toDayDateTimeString() }}</div>
        </div>
    </div>
</x-dashboard-layout>