@extends('layouts.mainlayout')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-5">
    <!-- Breadcrumb -->
    <div class="mb-6 text-sm text-secondary">
        <a href="{{ url('/') }}" class="hover:text-primary" style="color: #6A38C2;">Home</a>
        <span class="mx-2">/</span>
        <a href="{{ route('items.show', $item->id) }}" class="hover:text-primary" style="color: #6A38C2;">{{ $item->item_name }}</a>
        <span class="mx-2">/</span>
        <span class="fw-bold">Checkout</span>
    </div>

    <h1 class="display-5 fw-bold mb-4" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Checkout</h1>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="flex flex-wrap -mx-2">
        <!-- Left: Item Details -->
        <div class="w-full lg:w-7/12 px-2">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3" style="color: #6A38C2;">Detail Item</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <img src="{{ $item->first_image_url }}" alt="{{ $item->item_name }}" class="img-fluid rounded">
                        </div>
                        <div class="col-md-8">
                            <h6 class="fw-bold">{{ $item->item_name }}</h6>
                            <p class="text-secondary mb-2">{{ \Illuminate\Support\Str::limit($item->item_description ?? '', 100) }}</p>
                            <div class="mb-2">
                                <span class="badge" style="background: {{ in_array($item->item_type, ['sewa', 'rent']) ? '#4ADFFF' : '#6A38C2' }};">
                                    {{ ucfirst($item->item_type ?? 'sale') }}
                                </span>
                            </div>
                            <div class="fs-3 fw-bold" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
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
        <div class="w-full lg:w-5/12 px-2">
            <div class="bg-white shadow rounded">
                <div class="p-4">
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

                            <div class="mb-3">
                                <div class="p-3 rounded" style="background: rgba(106, 56, 194, 0.1);">
                                    <small class="text-muted d-block mb-1">Rental Period</small>
                                    <div class="fw-bold" style="color: #6A38C2;" id="rentalPeriodDisplay">Please select dates</div>
                                    <small class="text-muted">Price: Rp {{ number_format($item->item_price, 0, ',', '.') }} per {{ $item->rental_duration_value ?? 1 }} {{ $item->rental_duration_unit ?? 'day' }}</small>
                                </div>
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
                        <button type="submit" class="inline-block w-full mt-4 px-6 py-3 rounded text-white" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%);">
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
    const isRental = {{ in_array($item->item_type, ['sewa', 'rent']) ? 'true' : 'false' }};
    const rentalDurationValue = {{ $item->rental_duration_value ?? 1 }};
    const rentalDurationUnit = '{{ $item->rental_duration_unit ?? 'day' }}';
    
    const qtyInput = document.getElementById('quantity');
    const qtyDisplay = document.getElementById('qtyDisplay');
    const subtotalEl = document.getElementById('subtotal');
    const serviceFeeEl = document.getElementById('serviceFee');
    const totalEl = document.getElementById('total');
    const rentalPeriodDisplay = document.getElementById('rentalPeriodDisplay');
    const startDateInput = document.getElementById('rental_start_date');
    const endDateInput = document.getElementById('rental_end_date');

    function calculateRentalUnits(startDate, endDate) {
        if (!startDate || !endDate) return 0;
        
        const start = new Date(startDate);
        const end = new Date(endDate);
        const diffTime = Math.abs(end - start);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays === 0) return 0;
        
        // Calculate how many rental units are needed
        let units = 0;
        if (rentalDurationUnit === 'day') {
            units = Math.ceil(diffDays / rentalDurationValue);
        } else if (rentalDurationUnit === 'week') {
            units = Math.ceil(diffDays / (rentalDurationValue * 7));
        } else if (rentalDurationUnit === 'month') {
            units = Math.ceil(diffDays / (rentalDurationValue * 30));
        }
        
        return Math.max(units, 1); // At least 1 unit
    }

    function updateTotal() {
        const qty = parseInt(qtyInput.value) || 1;
        let priceMultiplier = 1;
        
        if (isRental && startDateInput && endDateInput) {
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;
            
            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                if (diffDays > 0) {
                    priceMultiplier = calculateRentalUnits(startDate, endDate);
                    
                    // Update rental period display
                    if (rentalPeriodDisplay) {
                        rentalPeriodDisplay.innerHTML = `
                            <div>${diffDays} day(s) selected</div>
                            <div class="mt-1">= ${priceMultiplier} × ${rentalDurationValue} ${rentalDurationUnit} period(s)</div>
                        `;
                    }
                } else {
                    if (rentalPeriodDisplay) {
                        rentalPeriodDisplay.textContent = 'Please select valid dates';
                    }
                }
            }
        }
        
        const itemTotal = itemPrice * priceMultiplier;
        const subtotal = itemTotal * qty;
        const serviceFee = subtotal * 0.05;
        const total = subtotal + serviceFee;

        qtyDisplay.textContent = qty;
        subtotalEl.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        serviceFeeEl.textContent = 'Rp ' + serviceFee.toLocaleString('id-ID');
        totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    qtyInput.addEventListener('input', updateTotal);
    
    if (startDateInput) {
        startDateInput.addEventListener('change', function() {
            // Set minimum end date to start date
            if (this.value) {
                endDateInput.min = this.value;
            }
            updateTotal();
        });
    }
    
    if (endDateInput) {
        endDateInput.addEventListener('change', updateTotal);
    }
</script>
@endpush
@endsection
