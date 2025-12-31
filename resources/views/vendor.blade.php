@extends('layouts.mainlayout')

@section('content')

<!-- Vendor Header -->
<section class="py-5" style="background: linear-gradient(135deg, rgba(106, 56, 194, 0.1) 0%, rgba(255, 60, 172, 0.1) 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-auto">
                <img src="{{ $vendor->logo_url }}" alt="{{ $vendor->vendor_name }}" class="rounded-circle shadow" style="width: 120px; height: 120px; object-fit: cover;">
            </div>
            <div class="col">
                <h1 class="fw-bold mb-2" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                    {{ $vendor->vendor_name }}
                </h1>
                <p class="text-secondary mb-2">{{ $vendor->location ?? 'Location not set' }}</p>
                <p class="text-secondary mb-0">{{ $vendor->vendor_description ?? 'No description available.' }}</p>
            </div>
            <div class="col-auto">
                <div class="d-flex gap-3">
                    <div class="text-center p-3 rounded" style="background: rgba(106,56,194,0.1);">
                        <div class="h4 fw-bold mb-0" style="color: #6A38C2;">{{ $vendor->items->count() ?? 0 }}</div>
                        <small class="text-secondary">Products</small>
                    </div>
                    <div class="text-center p-3 rounded" style="background: rgba(106,56,194,0.1);">
                        <div class="h4 fw-bold mb-0" style="color: #6A38C2;">4.5</div>
                        <small class="text-secondary">Rating</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Products Section -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold" style="color: #6A38C2;">Products</h2>
            
            <!-- Filter -->
            <div class="d-flex gap-2">
                <a href="{{ route('vendors.show', $vendor->id) }}" class="btn {{ !request('type') ? 'btn-primary' : 'btn-outline-secondary' }}" style="{{ !request('type') ? 'background: #6A38C2; border-color: #6A38C2;' : '' }}">All</a>
                <a href="{{ route('vendors.show', ['vendor' => $vendor->id, 'type' => 'jual']) }}" class="btn {{ request('type') === 'jual' ? 'btn-primary' : 'btn-outline-secondary' }}" style="{{ request('type') === 'jual' ? 'background: #6A38C2; border-color: #6A38C2;' : '' }}">For Sale</a>
                <a href="{{ route('vendors.show', ['vendor' => $vendor->id, 'type' => 'sewa']) }}" class="btn {{ request('type') === 'sewa' ? 'btn-primary' : 'btn-outline-secondary' }}" style="{{ request('type') === 'sewa' ? 'background: #4ADFFF; border-color: #4ADFFF; color: #000;' : '' }}">For Rent</a>
            </div>
        </div>

        @if($items->count() > 0)
            <div class="row g-4">
                @foreach($items as $item)
                    @php $isRent = ($item->item_type === 'sewa' || $item->item_type === 'rent'); @endphp
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="card h-100 shadow-sm">
                            <div class="position-relative">
                                <img src="{{ $item->first_image_url }}" alt="{{ $item->item_name }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                <span class="position-absolute top-0 end-0 m-2 badge" style="background: {{ $isRent ? '#4ADFFF' : '#6A38C2' }};">
                                    {{ $isRent ? 'Rent' : 'Sale' }}
                                </span>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="fw-bold mb-2">{{ $item->item_name }}</h5>
                                <p class="text-secondary small mb-3">{{ Str::limit($item->item_description ?? '', 80) }}</p>
                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <span class="h5 fw-bold mb-0" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                                        Rp {{ number_format($item->item_price) }}
                                        @if($isRent)
                                            <small class="text-secondary">/ {{ $item->rental_duration_unit ?? 'day' }}</small>
                                        @endif
                                    </span>
                                    <a href="{{ route('items.show', $item->id) }}" class="btn btn-sm" style="background: {{ $isRent ? '#4ADFFF' : '#6A38C2' }}; color: {{ $isRent ? '#000' : '#fff' }};">
                                        {{ $isRent ? 'Rent Now' : 'View Details' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $items->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <div class="text-6xl mb-4">📦</div>
                <h3 class="h4 fw-bold mb-2">No Products Found</h3>
                <p class="text-secondary">Try adjusting your search or filter criteria</p>
            </div>
        @endif
    </div>
</section>

@endsection
