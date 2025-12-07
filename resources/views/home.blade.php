<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BarangSiapa - Buy & Rent Marketplace</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('styles/style.css') }}">
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-animated cyber-grid">
    
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="/" class="logo">BarangSiapa</a>
            
            <ul class="nav-links" id="navLinks">
                <li><a href="#home" class="nav-link active">Home</a></li>
                <li><a href="#products" class="nav-link">Products</a></li>
                <li><a href="#categories" class="nav-link">Categories</a></li>
                <li><a href="#trending" class="nav-link">Trending</a></li>
                
                @auth
                    <li><a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a></li>
                @else
                    <li><a href="{{ route('login') }}" class="btn btn-secondary">Login</a></li>
                    @if (Route::has('register'))
                        <li><a href="{{ route('register') }}" class="btn btn-primary">Register</a></li>
                    @endif
                @endauth
            </ul>
            
            <div class="menu-toggle" id="menuToggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

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
            <input type="text" class="search-input" placeholder="Search for products, categories, vendors...">
            <button class="search-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
            </button>
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
                    <!-- Carousel items will be populated by JavaScript -->
                    <div class="carousel-item">
                        <div class="product-card">
                            <div class="product-badge badge-flash">Flash Sale</div>
                            <img src="{{ asset('images/item/default_image.png') }}" alt="Product" class="product-image">
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
                    
                    <div class="carousel-item">
                        <div class="product-card">
                            <div class="product-badge badge-rent">For Rent</div>
                            <img src="{{ asset('images/item/default_image.png') }}" alt="Product" class="product-image">
                            <div class="p-4">
                                <h3 class="text-lg font-bold mb-2">Professional Camera</h3>
                                <p class="text-sm text-soft-lilac mb-3">4K video, perfect for events</p>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="text-2xl font-bold text-gradient">$29</span>
                                        <span class="text-sm text-soft-lilac">/day</span>
                                    </div>
                                    <button class="btn btn-accent">Rent</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="carousel-item">
                        <div class="product-card">
                            <div class="product-badge badge-buy">Best Seller</div>
                            <img src="{{ asset('images/item/default_image.png') }}" alt="Product" class="product-image">
                            <div class="p-4">
                                <h3 class="text-lg font-bold mb-2">Smart Watch</h3>
                                <p class="text-sm text-soft-lilac mb-3">Track your fitness goals</p>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="text-2xl font-bold text-gradient">$149</span>
                                        <span class="text-sm text-soft-lilac">/buy</span>
                                    </div>
                                    <button class="btn btn-primary">View</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="carousel-item">
                        <div class="product-card">
                            <div class="product-badge badge-rent">For Rent</div>
                            <img src="{{ asset('images/item/default_image.png') }}" alt="Product" class="product-image">
                            <div class="p-4">
                                <h3 class="text-lg font-bold mb-2">Gaming Console</h3>
                                <p class="text-sm text-soft-lilac mb-3">Latest gen with controllers</p>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="text-2xl font-bold text-gradient">$15</span>
                                        <span class="text-sm text-soft-lilac">/day</span>
                                    </div>
                                    <button class="btn btn-accent">Rent</button>
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
                        $firstImage = optional($item->itemGalleries->first())->image_path;
                        $isRent = ($item->item_type === 'sewa' || $item->item_type === 'rent');
                    @endphp
                    <div class="product-card" data-category="all">
                        <div class="product-badge {{ $isRent ? 'badge-rent' : 'badge-buy' }}">{{ $isRent ? 'For Rent' : 'Buy Now' }}</div>
                        <img src="{{ $firstImage ? asset('storage/' . $firstImage) : asset('images/item/default_image.png') }}" alt="{{ $item->item_name }}" class="product-image">
                        <div class="p-4">
                            <h3 class="text-lg font-bold mb-2">{{ $item->item_name }}</h3>
                            <p class="text-sm text-soft-lilac mb-3">{{ \Illuminate\Support\Str::limit($item->item_description ?? '', 80) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-2xl font-bold text-gradient">@if($isRent) {{ $item->item_price }} / {{ $item->rental_duration_unit ?? 'day' }} @else {{ $item->item_price }} @endif</span>
                                <button class="btn {{ $isRent ? 'btn-accent' : 'btn-primary' }} btn-sm" onclick="showProductModal({{ $item->id }})">{{ $isRent ? 'Rent Now' : 'View Details' }}</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <button class="page-btn active">1</button>
                <button class="page-btn">2</button>
                <button class="page-btn">3</button>
                <button class="page-btn">4</button>
                <button class="page-btn">→</button>
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

    <!-- Footer -->
    <footer class="section" style="background: rgba(9, 9, 15, 0.95); padding: 3rem 0;">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
                <div>
                    <h3 class="text-gradient text-xl font-bold mb-3">BarangSiapa</h3>
                    <p class="text-soft-lilac">The future of buying and renting. Premium quality, affordable prices.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-3">Quick Links</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li class="mb-2"><a href="#" class="text-soft-lilac hover:text-neon-pink">About Us</a></li>
                        <li class="mb-2"><a href="#" class="text-soft-lilac hover:text-neon-pink">Contact</a></li>
                        <li class="mb-2"><a href="#" class="text-soft-lilac hover:text-neon-pink">FAQ</a></li>
                        <li class="mb-2"><a href="#" class="text-soft-lilac hover:text-neon-pink">Terms of Service</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-3">Categories</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li class="mb-2"><a href="#" class="text-soft-lilac hover:text-neon-pink">Electronics</a></li>
                        <li class="mb-2"><a href="#" class="text-soft-lilac hover:text-neon-pink">Fashion</a></li>
                        <li class="mb-2"><a href="#" class="text-soft-lilac hover:text-neon-pink">Home & Living</a></li>
                        <li class="mb-2"><a href="#" class="text-soft-lilac hover:text-neon-pink">Sports</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-3">Connect With Us</h4>
                    <div style="display: flex; gap: 1rem;">
                        <a href="#" class="text-2xl hover:text-neon-pink transition">📘</a>
                        <a href="#" class="text-2xl hover:text-neon-pink transition">📷</a>
                        <a href="#" class="text-2xl hover:text-neon-pink transition">🐦</a>
                        <a href="#" class="text-2xl hover:text-neon-pink transition">📧</a>
                    </div>
                </div>
            </div>
            <div class="divider"></div>
            <p class="text-center text-soft-lilac">© 2025 BarangSiapa. All rights reserved. Built with ❤️ using Laravel.</p>
        </div>
    </footer>

    <!-- Floating Action Button (Scroll to Top) -->
    <button class="fab" id="scrollToTop" style="display: none;" onclick="scrollToTop()">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 15l-6-6-6 6"/>
        </svg>
    </button>

    <!-- JavaScript -->
    <script>
        // Mobile Menu Toggle
        const menuToggle = document.getElementById('menuToggle');
        const navLinks = document.getElementById('navLinks');
        
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('open');
        });

        // Carousel Functionality
        let carouselPosition = 0;
        function scrollCarousel(direction) {
            const carousel = document.getElementById('trendingCarousel');
            const itemWidth = 320; // card width + gap
            carouselPosition += direction * itemWidth;
            const maxScroll = -(carousel.children.length - 3) * itemWidth;
            carouselPosition = Math.max(maxScroll, Math.min(0, carouselPosition));
            carousel.style.transform = `translateX(${carouselPosition}px)`;
        }

        // Category Filter
        const categoryChips = document.querySelectorAll('.category-chip');
        const productCards = document.querySelectorAll('.product-card');
        
        categoryChips.forEach(chip => {
            chip.addEventListener('click', () => {
                // Remove active class from all chips
                categoryChips.forEach(c => c.classList.remove('active'));
                // Add active class to clicked chip
                chip.classList.add('active');
                
                const category = chip.dataset.category;
                
                // Filter products
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
            if (window.pageYOffset > 300) {
                scrollToTopBtn.style.display = 'flex';
            } else {
                scrollToTopBtn.style.display = 'none';
            }
        });
        
        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Smooth Scroll for Navigation Links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // Active Nav Link on Scroll
        const sections = document.querySelectorAll('section[id]');
        const navLinksArray = document.querySelectorAll('.nav-link');
        
        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= sectionTop - 200) {
                    current = section.getAttribute('id');
                }
            });
            
            navLinksArray.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        });

        // Modal Functions (placeholder for future implementation)
        function showProductModal(productId) {
            @guest
                alert('Please login to view product details');
                window.location.href = '{{ route("login") }}';
            @else
                // record last viewed via AJAX
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
</body>
</html>
