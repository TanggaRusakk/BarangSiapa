@extends('layouts.mainlayout')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <div class="mb-6 text-sm text-secondary">
        <a href="{{ url('/') }}" class="hover:text-primary" style="color: #6A38C2;">Home</a>
        <span class="mx-2">/</span>
        <a href="{{ route('items.show', $item->id) }}" class="hover:text-primary" style="color: #6A38C2;">{{ $item->item_name }}</a>
        <span class="mx-2">/</span>
        <span class="fw-bold">Checkout</span>
    </div>

    <h1 class="text-3xl fw-bold mb-6" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Checkout</h1>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Left: Item Details -->
        <div class="col-lg-7">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3" style="color: #6A38C2;">Detail Item</h5>
                    <div class="row">
                        <div class="col-md-4">
                            @php
                                $imageUrl = $item->first_image_url ?? (file_exists(public_path('images/items/item_placeholder.jpg')) ? asset('images/items/item_placeholder.jpg') : asset('images/items/item_placeholder.png'));
                            @endphp
                            <img src="{{ $imageUrl }}" alt="{{ $item->item_name }}" class="img-fluid rounded">
                        </div>
                        <div class="col-md-8">
                            <h6 class="fw-bold">{{ $item->item_name }}</h6>
                            <p class="text-secondary mb-2">{{ \Illuminate\Support\Str::limit($item->item_description ?? '', 100) }}</p>
                            <div class="mb-2">
                                <span class="badge" style="background: {{ in_array($item->item_type, ['sewa', 'rent']) ? '#4ADFFF' : '#6A38C2' }};">
                                    {{ ucfirst($item->item_type ?? 'sale') }}
                                </span>
                            </div>
                            <div class="text-2xl fw-bold" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                                Rp {{ number_format($item->item_price, 0, ',', '.') }}
                                @if(in_array($item->item_type, ['sewa', 'rent']))
                                    <small class="text-muted">/ {{ $item->rental_duration_unit ?? 'day' }}</small>
                                @endif
                            </div>
                            <p class="text-muted mt-2">Stock: {{ $item->item_stock }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Order Form -->
        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-4" style="color: #6A38C2;">Order Details</h5>
                    
                    <form action="{{ route('orders.store') }}" method="POST" id="checkoutForm">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item->id }}">

                        <!-- Quantity -->
                        <div class="mb-3">
                            <label for="quantity" class="form-label fw-bold">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="{{ $item->item_stock }}" required>
                            <small class="text-muted">Max: {{ $item->item_stock }}</small>
                        </div>

                        <!-- Rental Dates (if item is for rent) -->
                        @if(in_array($item->item_type, ['sewa', 'rent']))
                            <div class="mb-3">
                                <label for="rental_start_date" class="form-label fw-bold">Start Date</label>
                                <input type="date" class="form-control" id="rental_start_date" name="rental_start_date" min="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="rental_end_date" class="form-label fw-bold">End Date</label>
                                <input type="date" class="form-control" id="rental_end_date" name="rental_end_date" min="{{ date('Y-m-d') }}" required>
                            </div>
                        @endif

                        <!-- Order Summary -->
                        <div class="border-top pt-3 mt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Item Price</span>
                                <span id="itemPrice" class="fw-bold">Rp {{ number_format($item->item_price, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Quantity</span>
                                <span id="qtyDisplay">1</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span id="subtotal" class="fw-bold">Rp {{ number_format($item->item_price, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 text-muted">
                                <span>Service Fee (5%)</span>
                                <span id="serviceFee">Rp {{ number_format($item->item_price * 0.05, 0, ',', '.') }}</span>
                            </div>
                            <div class="border-top pt-2 mt-2">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold fs-5">Total</span>
                                    <span id="total" class="fw-bold fs-5" style="color: #6A38C2;">Rp {{ number_format($item->item_price * 1.05, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-lg w-100 mt-4 text-white" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%);">
                            Proceed to Payment
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const itemPrice = {{ $item->item_price }};
    const qtyInput = document.getElementById('quantity');
    const qtyDisplay = document.getElementById('qtyDisplay');
    const subtotalEl = document.getElementById('subtotal');
    const serviceFeeEl = document.getElementById('serviceFee');
    const totalEl = document.getElementById('total');

    function updateTotal() {
        const qty = parseInt(qtyInput.value) || 1;
        const subtotal = itemPrice * qty;
        const serviceFee = subtotal * 0.05;
        const total = subtotal + serviceFee;

        qtyDisplay.textContent = qty;
        subtotalEl.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        serviceFeeEl.textContent = 'Rp ' + serviceFee.toLocaleString('id-ID');
        totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    qtyInput.addEventListener('input', updateTotal);
</script>
@endpush
@endsection
