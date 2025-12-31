@extends('layouts.mainlayout')

@section('content')

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <h1 class="hero-title">Welcome to the Future of Shopping</h1>
            <p class="hero-subtitle">Buy what you need. Rent what you want. Save like never before.</p>
            <div class="hero-cta">
                <a href="#products" class="btn btn-primary">Explore Products</a>
                <a href="{{ route('register') }}" class="btn btn-accent">Start Selling</a>
            </div>
        </div>
    </section>

    <!-- Search Bar -->
    <div class="container">
        <div class="search-container">
            <form action="{{ route('items.index') }}" method="GET" class="w-full flex">
                <input type="text" name="search" class="search-input flex-1" placeholder="Search for products, categories, vendors..." value="{{ request('search') }}">
                <button class="search-btn" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Category Filter -->
    <section id="categories" class="section">
        <div class="container">
            <h2 class="section-title text-gradient">Browse by Category</h2>
            <div class="category-filter">
                <button class="category-chip active" data-category="all">All Products</button>
                <button class="category-chip" data-category="electronics">Electronics</button>
                <button class="category-chip" data-category="fashion">Fashion</button>
                <button class="category-chip" data-category="home">Home & Living</button>
                <button class="category-chip" data-category="sports">Sports</button>
                <button class="category-chip" data-category="toys">Toys & Games</button>
                <button class="category-chip" data-category="books">Books</button>
                <button class="category-chip" data-category="tools">Tools</button>
            </div>
        </div>
    </section>

    <!-- Trending Products Carousel -->
    <section id="trending" class="section">
        <div class="container">
            <h2 class="section-title text-gradient">🔥 Trending Now</h2>
            <div class="carousel">
                <button class="carousel-btn prev" onclick="scrollCarousel(-1)">‹</button>
                <div class="carousel-track" id="trendingCarousel">
                    <!-- Carousel items will be populated by JavaScript or server-side rendering -->
                    <div class="carousel-item">
                        <div class="product-card">
                            <div class="product-badge badge-flash">Flash Sale</div>
                            <img src="{{ asset('images/products/product_placeholder.jpg') }}" alt="Product" class="product-image">
                            <div class="p-4">
                                <h3 class="text-lg font-bold mb-2">Premium Headphones</h3>
                                <p class="text-sm text-soft-lilac mb-3">High-quality wireless audio</p>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="text-2xl font-bold text-gradient">$199</span>
                                        <span class="text-sm text-soft-lilac">/buy</span>
                                    </div>
                                    <button class="btn btn-primary">View</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-btn next" onclick="scrollCarousel(1)">›</button>
            </div>
        </div>
    </section>

    <div class="divider container"></div>

    <!-- Product Grid -->
    <section id="products" class="section">
        <div class="container">
            <h2 class="section-title text-gradient">Explore All Products</h2>

            <div class="product-grid" id="productGrid">
                @foreach($items as $item)
                    @php
                        $isRent = ($item->item_type === 'sewa' || $item->item_type === 'rent');
                    @endphp
                    <div class="product-card" data-category="all">
                        <div class="product-badge {{ $isRent ? 'badge-rent' : 'badge-buy' }}">{{ $isRent ? 'For Rent' : 'Buy Now' }}</div>
                        <img src="{{ $item->first_image_url }}" alt="{{ $item->item_name }}" class="product-image">
                        <div class="p-4">
                            <h3 class="text-lg font-bold mb-2">{{ $item->item_name }}</h3>
                            <p class="text-sm text-soft-lilac mb-3">{{ \Illuminate\Support\Str::limit($item->item_description ?? '', 80) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-2xl font-bold text-gradient">@if($isRent) {{ $item->item_price }} / {{ $item->rental_duration_unit ?? 'day' }} @else {{ $item->item_price }} @endif</span>
                                <a href="{{ route('items.show', $item->id) }}" class="btn {{ $isRent ? 'btn-accent' : 'btn-primary' }} btn-sm">{{ $isRent ? 'Rent Now' : 'View Details' }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination (use Laravel paginator when available) -->
            <div class="mt-6">
                {{ $items->links() ?? '' }}
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="section bg-animated">
        <div class="container">
            <h2 class="section-title text-gradient">Why Choose BarangSiapa?</h2>
            <div class="stat-grid">
                <div class="stat-card">
                    <div class="text-center">
                        <div class="text-4xl mb-3">🛍️</div>
                        <h3 class="text-xl font-bold mb-2">Buy & Rent</h3>
                        <p class="text-soft-lilac">Choose to own or rent products based on your needs</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="text-center">
                        <div class="text-4xl mb-3">⚡</div>
                        <h3 class="text-xl font-bold mb-2">Fast Delivery</h3>
                        <p class="text-soft-lilac">Get your products delivered within 24 hours</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="text-center">
                        <div class="text-4xl mb-3">🔒</div>
                        <h3 class="text-xl font-bold mb-2">Secure Payment</h3>
                        <p class="text-soft-lilac">Your transactions are protected and encrypted</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="text-center">
                        <div class="text-4xl mb-3">⭐</div>
                        <h3 class="text-xl font-bold mb-2">Verified Reviews</h3>
                        <p class="text-soft-lilac">Real reviews from real customers</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Floating Action Button (Scroll to Top) -->
    <button class="fab" id="scrollToTop" style="display: none;" onclick="scrollToTop()">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 15l-6-6-6 6"/>
        </svg>
    </button>

@endsection

@push('scripts')
<script>
    // Carousel Functionality
    let carouselPosition = 0;
    function scrollCarousel(direction) {
        const carousel = document.getElementById('trendingCarousel');
        if (!carousel) return;
        const itemWidth = 320; // card width + gap
        carouselPosition += direction * itemWidth;
        const maxScroll = -(carousel.children.length - 3) * itemWidth;
        carouselPosition = Math.max(maxScroll, Math.min(0, carouselPosition));
        carousel.style.transform = `translateX(${carouselPosition}px)`;
    }

    // Category Filter
    document.addEventListener('DOMContentLoaded', function () {
        const categoryChips = document.querySelectorAll('.category-chip');
        const productCards = document.querySelectorAll('.product-card');

        categoryChips.forEach(chip => {
            chip.addEventListener('click', () => {
                categoryChips.forEach(c => c.classList.remove('active'));
                chip.classList.add('active');
                const category = chip.dataset.category;
                productCards.forEach(card => {
                    if (category === 'all' || card.dataset.category === category) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Scroll to Top Button
        const scrollToTopBtn = document.getElementById('scrollToTop');
        window.addEventListener('scroll', () => {
            if (!scrollToTopBtn) return;
            if (window.pageYOffset > 300) {
                scrollToTopBtn.style.display = 'flex';
            } else {
                scrollToTopBtn.style.display = 'none';
            }
        });

        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        window.scrollToTop = scrollToTop;
    });

    // Modal placeholders
    function showProductModal(productId) {
        @guest
            alert('Please login to view product details');
            window.location.href = '{{ route("login") }}';
        @else
            try {
                fetch('/product/viewed', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ id: productId })
                });
            } catch (e) { /* ignore */ }
            alert('Product modal for: ' + productId + '\n(Will be implemented in member dashboard)');
        @endguest
    }

    function showRentalModal(productId) {
        @guest
            alert('Please login to rent this product');
            window.location.href = '{{ route("login") }}';
        @else
            try {
                fetch('/product/viewed', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ id: productId })
                });
            } catch (e) { /* ignore */ }
            alert('Rental modal for: ' + productId + '\n(Will be implemented in member dashboard)');
        @endguest
    }
</script>
@endpush
