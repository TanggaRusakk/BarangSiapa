@extends('layouts.mainlayout')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-5">
    <!-- Page Header -->
    <div class="mb-4">
        <h1 class="text-3xl font-extrabold mb-3" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">All Items</h1>
        <p class="text-gray-500">Browse and search through our marketplace</p>
    </div>

    <!-- Search & Filters -->
    <div class="flex flex-wrap -mx-2 mb-4">
        <div class="w-full md:w-2/3 px-2">
            <form action="{{ route('items.index') }}" method="GET" class="flex">
                <input type="text" name="search" class="w-full px-3 py-2 border rounded-l" placeholder="Search items..." value="{{ request('search') }}">
                <button class="inline-block px-4 py-2 rounded-r text-white" type="submit" style="background: #6A38C2;"> 
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                </button>
            </form>
        </div>
        <div class="w-full md:w-1/3 px-2">
            <!-- Buy/Rent Filter Toggle -->
            <div class="flex gap-2 w-full">
                <input type="radio" class="hidden" name="item_type" id="filter-all" value="all" checked>
                <label class="inline-block px-3 py-1 border rounded text-[#6A38C2]" for="filter-all">All</label>
                
                <input type="radio" class="hidden" name="item_type" id="filter-buy" value="buy">
                <label class="inline-block px-3 py-1 border rounded text-[#6A38C2]" for="filter-buy">Buy</label>
                
                <input type="radio" class="hidden" name="item_type" id="filter-rent" value="rent">
                <label class="inline-block px-3 py-1 border rounded text-[#6A38C2]" for="filter-rent">Rent</label>
            </div>
        </div>
    </div>

    <!-- Items Grid (3x3) -->
    <div class="grid gap-4 md:grid-cols-3" id="itemsGrid">
        @forelse($items as $item)
            @php
                $isRent = ($item->item_type === 'sewa' || $item->item_type === 'rent');
                $itemTypeClass = $isRent ? 'rent' : 'buy';
            @endphp
            <div class="col-span-1 item-card" data-type="{{ $itemTypeClass }}">
                <div class="bg-white shadow rounded h-full overflow-hidden">
                    <div class="relative">
                        <span class="absolute top-2 left-2 inline-block px-2 py-1 rounded-full text-xs" style="background: {{ $isRent ? '#4ADFFF' : '#6A38C2' }};">{{ $isRent ? 'For Rent' : 'For Sale' }}</span>
                        <img src="{{ $item->first_image_url }}" class="w-full h-48 object-cover" alt="{{ $item->item_name }}">
                    </div>
                    <div class="p-4">
                        <h5 class="text-lg font-bold">{{ $item->item_name }}</h5>
                        <p class="text-gray-500 text-sm">{{ \Illuminate\Support\Str::limit($item->item_description ?? 'No description available', 80) }}</p>
                        <div class="flex justify-between items-center mt-3">
                            <div>
                                <span class="font-bold text-lg" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Rp {{ number_format($item->item_price, 0, ',', '.') }}</span>
                                @if($isRent)
                                    <small class="text-gray-500">/{{ $item->rental_duration_unit ?? 'day' }}</small>
                                @endif
                            </div>
                            <a href="{{ route('items.show', $item->id) }}" class="inline-block px-3 py-1 rounded text-white" style="background: {{ $isRent ? '#4ADFFF' : '#6A38C2' }};">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-1">
                <div class="p-4 rounded bg-blue-50 text-blue-700 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mb-3 mx-auto">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <p class="mb-0">No items found. Try adjusting your filters.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-5">
        <nav class="items-pagination">{{ $items->links() }}</nav>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('input[name="item_type"]');
    const itemCards = document.querySelectorAll('.item-card');
    
    filterButtons.forEach(button => {
        button.addEventListener('change', function() {
            const filterValue = this.value;
            
            itemCards.forEach(card => {
                const cardType = card.dataset.type;
                
                if (filterValue === 'all') {
                    card.style.display = 'block';
                } else if (filterValue === cardType) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endpush
