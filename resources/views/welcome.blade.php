<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BarangSiapa - Concert & Event Equipment Marketplace</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
    
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
                <li><a href="#home" class="nav-link active">HOME</a></li>
                <li><a href="#products" class="nav-link">GEAR</a></li>
                <li><a href="#categories" class="nav-link">CATEGORIES</a></li>
                <li><a href="#trending" class="nav-link">HOT DEALS</a></li>
                
                @auth
                    <li><a href="{{ url('/dashboard') }}" class="btn btn-primary btn-sm">DASHBOARD</a></li>
                @else
                    <li><a href="{{ route('login') }}" class="btn btn-secondary btn-sm">LOGIN</a></li>
                    @if (Route::has('register'))
                        <li><a href="{{ route('register') }}" class="btn btn-primary btn-sm">REGISTER</a></li>
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
            <h1 class="hero-title">
                <span class="text-gradient">GEAR UP</span> FOR<br>
                THE ULTIMATE SHOW
            </h1>
            <p class="hero-subtitle">Professional concert & event equipment | Buy or rent stage gear, sound systems, lighting rigs & more</p>
            
            <!-- Waveform Visualizer -->
            <div class="waveform mb-4">
                <div class="waveform-bar"></div>
                <div class="waveform-bar"></div>
                <div class="waveform-bar"></div>
                <div class="waveform-bar"></div>
                <div class="waveform-bar"></div>
                <div class="waveform-bar"></div>
                <div class="waveform-bar"></div>
                <div class="waveform-bar"></div>
            </div>
            
            <div class="hero-cta">
                <a href="#products" class="btn btn-primary btn-lg">Browse Equipment</a>
                <a href="{{ route('register') }}" class="btn btn-accent btn-lg">List Your Gear</a>
            </div>
        </div>
    </section>

    <!-- Search Bar -->
    <div class="container">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search stage platforms, microphones, lighting, sound systems...">
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
            <h2 class="section-title text-gradient">EQUIPMENT CATEGORIES</h2>
            <div class="category-filter">
                <button class="category-chip active" data-category="all">ALL GEAR</button>
                <button class="category-chip" data-category="sound">🔊 SOUND SYSTEMS</button>
                <button class="category-chip" data-category="lighting">💡 LIGHTING</button>
                <button class="category-chip" data-category="stage">🎪 STAGES</button>
                <button class="category-chip" data-category="rigging">⚙️ RIGGING</button>
                <button class="category-chip" data-category="power">⚡ POWER/GENERATORS</button>
                <button class="category-chip" data-category="controllers">🎛️ CONTROLLERS</button>
                <button class="category-chip" data-category="crew">👷 CREW SERVICES</button>
            </div>
        </div>
    </section>

    <!-- Trending Products Carousel -->
    <section id="trending" class="section">
        <div class="container">
            <h2 class="section-title text-gradient">🔥 HOT GEAR THIS MONTH</h2>
            <div class="carousel">
                <button class="carousel-btn prev" onclick="scrollCarousel(-1)">‹</button>
                    <div class="carousel-track" id="trendingCarousel">
                    <!-- Trending items from database -->
                    @foreach($trending as $t)
                        <div class="carousel-item">
                            <div class="product-card">
                                @if($t->item_type === 'rent')
                                    <div class="product-badge badge-rent">RENT</div>
                                @elseif($t->item_status === 'flash')
                                    <div class="product-badge badge-flash">FLASH DEAL</div>
                                @else
                                    <div class="product-badge badge-buy">BUY</div>
                                @endif

                                <img src="{{ $t->first_image_url ?? asset('images/item/default_image.png') }}" alt="{{ $t->item_name }}" class="product-image">
                                <div class="p-4">
                                    <h3 class="text-lg font-bold mb-2 uppercase">{{ $t->item_name }}</h3>
                                    <p class="text-sm text-soft-lilac mb-3">{{ Str::limit($t->item_description, 80) }}</p>
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <span class="text-2xl font-bold text-gradient">${{ number_format($t->item_price, 0) }}</span>
                                            <span class="text-sm text-soft-lilac">{{ $t->item_type === 'rent' ? '/day' : '' }}</span>
                                        </div>
                                        <button class="btn btn-primary btn-sm">VIEW</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                <button class="carousel-btn next" onclick="scrollCarousel(1)">›</button>
            </div>
        </div>
    </section>

    <div class="divider container"></div>

    <!-- Product Grid -->
    <section id="products" class="section">
        <div class="container">
            <h2 class="section-title text-gradient">ALL CONCERT EQUIPMENT</h2>
            
            <div class="product-grid" id="productGrid">
                @foreach($items as $item)
                    <div class="product-card" data-category="all">
                        @php
                            $isRent = ($item->item_type === 'sewa');
                        @endphp
                        <div class="product-badge {{ $isRent ? 'badge-rent' : 'badge-buy' }}">{{ $isRent ? 'RENT' : 'BUY' }}</div>
                        <img src="{{ $item->first_image_url ?? asset('images/item/default_image.png') }}" alt="{{ $item->item_name }}" class="product-image">
                        <div class="p-4">
                            <h3 class="text-lg font-bold mb-2 uppercase">{{ $item->item_name }}</h3>
                            <p class="text-sm text-soft-lilac mb-3">{{ \Illuminate\Support\Str::limit($item->item_description ?? '', 80) }}</p>
                            <div class="flex flex-wrap justify-between items-center gap-2">
                                <div>
                                    <span class="text-2xl font-bold text-gradient">${{ number_format($item->item_price, 0) }}</span>
                                    @if($isRent)
                                        <span class="text-sm text-soft-lilac">/day</span>
                                    @endif
                                </div>
                                <button class="btn {{ $isRent ? 'btn-accent' : 'btn-primary' }} btn-sm" onclick="showProductModal({{ $item->id }})">{{ $isRent ? 'RENT' : 'DETAILS' }}</button>
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
            <h2 class="section-title text-gradient">WHY RENT WITH US?</h2>
            <div class="stat-grid">
                <div class="stat-card">
                    <div class="text-center">
                        <div class="text-4xl mb-3">🎸</div>
                        <h3 class="text-xl font-bold mb-2 uppercase">Pro-Grade Equipment</h3>
                        <p class="text-soft-lilac">Industry-standard gear from top brands</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="text-center">
                        <div class="text-4xl mb-3">⚡</div>
                        <h3 class="text-xl font-bold mb-2 uppercase">Quick Deployment</h3>
                        <p class="text-soft-lilac">Setup and delivery within 24-48 hours</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="text-center">
                        <div class="text-4xl mb-3">👷</div>
                        <h3 class="text-xl font-bold mb-2 uppercase">Crew Included</h3>
                        <p class="text-soft-lilac">Optional professional crew for setup & operation</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="text-center">
                        <div class="text-4xl mb-3">💰</div>
                        <h3 class="text-xl font-bold mb-2 uppercase">Flexible Pricing</h3>
                        <p class="text-soft-lilac">Daily, weekly, and monthly rental packages</p>
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
                    <h3 class="text-gradient text-xl font-bold mb-3 uppercase">BarangSiapa</h3>
                    <p class="text-soft-lilac">Professional concert & event equipment marketplace. Buy or rent stage gear, sound systems, lighting rigs & more.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-3 uppercase">Quick Links</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li class="mb-2"><a href="#" class="text-soft-lilac hover:text-neon-pink transition">About Us</a></li>
                        <li class="mb-2"><a href="#" class="text-soft-lilac hover:text-neon-pink transition">Contact</a></li>
                        <li class="mb-2"><a href="#" class="text-soft-lilac hover:text-neon-pink transition">FAQ</a></li>
                        <li class="mb-2"><a href="#" class="text-soft-lilac hover:text-neon-pink transition">Terms of Service</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-3 uppercase">Equipment</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li class="mb-2"><a href="#" class="text-soft-lilac hover:text-neon-pink transition">Sound Systems</a></li>
                        <li class="mb-2"><a href="#" class="text-soft-lilac hover:text-neon-pink transition">Lighting</a></li>
                        <li class="mb-2"><a href="#" class="text-soft-lilac hover:text-neon-pink transition">Stages & Platforms</a></li>
                        <li class="mb-2"><a href="#" class="text-soft-lilac hover:text-neon-pink transition">Crew Services</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-3 uppercase">Connect With Us</h4>
                    <div style="display: flex; gap: 1rem;">
                        <a href="#" class="text-2xl hover:text-neon-pink transition">📘</a>
                        <a href="#" class="text-2xl hover:text-neon-pink transition">📷</a>
                        <a href="#" class="text-2xl hover:text-neon-pink transition">🐦</a>
                        <a href="#" class="text-2xl hover:text-neon-pink transition">📧</a>
                    </div>
                </div>
            </div>
            <div class="divider"></div>
            <p class="text-center text-soft-lilac">© 2025 BarangSiapa. All rights reserved. Built with ❤️ for the concert industry.</p>
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
                try {
                    fetch('/product/viewed', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ id: productId })
                    });
                } catch (e) { }
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
                } catch (e) { }
                alert('Rental modal for: ' + productId + '\n(Will be implemented in member dashboard)');
            @endguest
        }
    </script>
</body>
</html>
