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
                            <img src="{{ asset('images/item/default-image.png') }}" alt="Product" class="product-image">
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
                            <img src="{{ asset('images/item/default-image.png') }}" alt="Product" class="product-image">
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
                            <img src="{{ asset('images/item/default-image.png') }}" alt="Product" class="product-image">
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
                            <img src="{{ asset('images/item/default-image.png') }}" alt="Product" class="product-image">
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
                        $isRent = ($item->item_type === 'sewa' || $item->item_type === 'rent');
                    @endphp
                    <div class="product-card" data-category="all">
                        <div class="product-badge {{ $isRent ? 'badge-rent' : 'badge-buy' }}">{{ $isRent ? 'For Rent' : 'Buy Now' }}</div>
                        <img src="{{ $item->first_image_url ?? asset('images/item/default-image.png') }}" alt="{{ $item->item_name }}" class="product-image">
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
                        <a href="#" class="text-2xl">📘</a>
                        <a href="#" class="text-2xl"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="D4C2FF" class="bi bi-instagram" viewBox="0 0 16 16">
  <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/>
</svg></a>
                        <a href="#" class="text-2xl">🐦</a>
                        <a href="#" class="text-2xl">📧</a>
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
