@extends('layouts.mainlayout')

@section('content')

    <!-- Hero Section -->
    <section id="home" class="hero py-5" style="background: linear-gradient(135deg, rgba(106, 56, 194, 0.1) 0%, rgba(255, 60, 172, 0.1) 100%);">
        <div class="container text-center py-5">
            <h1 class="display-4 fw-bold mb-3" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Welcome to the Future of Shopping</h1>
            <p class="lead mb-4 text-secondary">Buy what you need. Rent what you want. Save like never before.</p>
            <div class="d-flex gap-3 justify-content-center">
                <a href="#products" class="btn btn-lg" style="background: #6A38C2; color: white;">Explore Products</a>
                <a href="{{ route('register') }}" class="btn btn-lg" style="background: #FF3CAC; color: white;">Start Selling</a>
            </div>
        </div>
    </section>

    <!-- About Us -->
    <section id="about" class="py-5">
        <div class="container">
            <h2 class="text-center mb-3" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Tentang BarangSiapa</h2>
            <p class="lead text-center text-secondary">BarangSiapa adalah marketplace lokal yang menggabungkan kemudahan pembelian dan penyewaan barang. Di sini Anda bisa membeli produk baru atau menyewa barang sesuai kebutuhan—praktis untuk pemakaian jangka pendek atau ketika Anda ingin mencoba sebelum membeli. Kami mendukung penjual lokal, menyediakan sistem ulasan terpercaya, dan memastikan transaksi aman untuk semua pengguna.</p>
            <div class="text-center mt-3">
                <a href="{{ route('items.index') }}" class="btn btn-outline-primary">Telusuri Produk</a>
            </div>
        </div>
    </section>

    <!-- Category Filter -->
    <section id="categories" class="py-4">
        <div class="container">
            <h2 class="text-center mb-4" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Browse by Category</h2>
            <div class="d-flex flex-wrap gap-2 justify-content-center">
                <button class="btn btn-outline-primary active" data-category="all">All Products</button>
                <button class="btn btn-outline-primary" data-category="electronics">Electronics</button>
                <button class="btn btn-outline-primary" data-category="fashion">Fashion</button>
                <button class="btn btn-outline-primary" data-category="home">Home & Living</button>
                <button class="btn btn-outline-primary" data-category="sports">Sports</button>
                <button class="btn btn-outline-primary" data-category="toys">Toys & Games</button>
                <button class="btn btn-outline-primary" data-category="books">Books</button>
                <button class="btn btn-outline-primary" data-category="tools">Tools</button>
            </div>
        </div>
    </section>

    <!-- Trending Products Carousel -->
    <section id="trending" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">🔥 Trending Now</h2>
            <div id="trendingCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm">
                                    <div class="position-relative">
                                        <span class="badge position-absolute top-0 start-0 m-2" style="background: #FF3CAC;">Flash Sale</span>
                                        <img src="{{ file_exists(public_path('images/items/item_placeholder.jpg')) ? asset('images/items/item_placeholder.jpg') : asset('images/items/item_placeholder.png') }}" class="card-img-top" alt="Product">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold">Premium Headphones</h5>
                                        <p class="card-text text-secondary">High-quality wireless audio</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="h4 mb-0 fw-bold" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">$199</span>
                                                <small class="text-secondary">/buy</small>
                                            </div>
                                            <button class="btn btn-sm" style="background: #6A38C2; color: white;">View</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#trendingCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#trendingCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>

    <hr class="container">

    <!-- Product Grid -->
    <section id="products" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Explore All Products</h2>

            <div class="row g-4" id="productGrid">
                @foreach($items->take(3) as $item)
                    @php
                        $isRent = ($item->item_type === 'sewa' || $item->item_type === 'rent');
                    @endphp
                    <div class="col-md-4" data-category="all">
                        <div class="card h-100 shadow-sm">
                            <div class="position-relative">
                                <span class="badge position-absolute top-0 start-0 m-2" style="background: {{ $isRent ? '#4ADFFF' : '#6A38C2' }};">{{ $isRent ? 'For Rent' : 'Buy Now' }}</span>
                                <img src="{{ $item->first_image_url ?? (file_exists(public_path('images/items/item_placeholder.jpg')) ? asset('images/items/item_placeholder.jpg') : asset('images/items/item_placeholder.png')) }}" class="card-img-top" alt="{{ $item->item_name }}">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title fw-bold">{{ $item->item_name }}</h5>
                                <p class="card-text text-secondary">{{ \Illuminate\Support\Str::limit($item->item_description ?? '', 80) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h5 mb-0 fw-bold" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                                        @if($isRent) 
                                            {{ $item->item_price }} / {{ $item->rental_duration_unit ?? 'day' }} 
                                        @else 
                                            {{ $item->item_price }} 
                                        @endif
                                    </span>
                                    <a href="{{ route('items.show', $item->id) }}" class="btn btn-sm" style="background: {{ $isRent ? '#4ADFFF' : '#6A38C2' }}; color: white;">
                                        {{ $isRent ? 'Rent Now' : 'View Details' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- View All -->
            <div class="text-center mt-4">
                <a href="{{ route('items.index') }}" class="btn btn-lg" style="background: #6A38C2; color: white;">View All Products</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5" style="background: linear-gradient(135deg, rgba(106, 56, 194, 0.05) 0%, rgba(255, 60, 172, 0.05) 100%);">
        <div class="container">
            <h2 class="text-center mb-4" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Why Choose BarangSiapa?</h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card h-100 shadow-sm text-center p-4">
                        <div class="fs-1 mb-3">🛍️</div>
                        <h5 class="fw-bold mb-2">Buy & Rent</h5>
                        <p class="text-secondary mb-0">Choose to own or rent products based on your needs</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 shadow-sm text-center p-4">
                        <div class="fs-1 mb-3">⚡</div>
                        <h5 class="fw-bold mb-2">Fast Delivery</h5>
                        <p class="text-secondary mb-0">Get your products delivered within 24 hours</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 shadow-sm text-center p-4">
                        <div class="fs-1 mb-3">🔒</div>
                        <h5 class="fw-bold mb-2">Secure Payment</h5>
                        <p class="text-secondary mb-0">Your transactions are protected and encrypted</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 shadow-sm text-center p-4">
                        <div class="fs-1 mb-3">⭐</div>
                        <h5 class="fw-bold mb-2">Verified Reviews</h5>
                        <p class="text-secondary mb-0">Real reviews from real customers</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Floating Action Button (Scroll to Top) -->
    <button class="btn btn-primary position-fixed bottom-0 end-0 m-4 rounded-circle" id="scrollToTop" style="display: none; width: 50px; height: 50px; background: #6A38C2;" onclick="scrollToTop()">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 15l-6-6-6 6"/>
        </svg>
    </button>

@endsection

@push('scripts')
<script>
    // Category Filter
    document.addEventListener('DOMContentLoaded', function () {
        const categoryChips = document.querySelectorAll('[data-category]');
        const productCards = document.querySelectorAll('#productGrid > div');

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
                scrollToTopBtn.style.display = 'block';
            } else {
                scrollToTopBtn.style.display = 'none';
            }
        });
    });

    function scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
</script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('styles/style.css') }}">
@endpush
