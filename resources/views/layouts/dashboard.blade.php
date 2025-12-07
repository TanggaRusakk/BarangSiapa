<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} - BarangSiapa</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('styles/style.css') }}">
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-animated">
    
    <!-- Top Navigation -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="/" class="logo">BarangSiapa</a>
            
            <div class="flex items-center gap-4">
                <!-- Search -->
                <div class="search-container" style="max-width: 400px; margin: 0;">
                    <input type="text" class="search-input" placeholder="Search...">
                </div>
                
                <!-- Notifications -->
                <button class="relative p-2" onclick="toggleNotifications()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    <span class="absolute top-0 right-0 w-2 h-2 bg-neon-pink rounded-full"></span>
                </button>
                
                <!-- User Menu -->
                <div class="relative">
                    <button onclick="toggleUserMenu()" class="flex items-center gap-2 p-2">
                        <div class="w-10 h-10 rounded-full bg-gradient-primary flex items-center justify-center font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span class="hidden md:block">{{ Auth::user()->name }}</span>
                    </button>
                    
                    <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 card" style="padding: 0.5rem;">
                        <a href="{{ route('profile.edit') }}" class="block p-2 hover:bg-purple-900 rounded">Profile</a>
                        <a href="#" class="block p-2 hover:bg-purple-900 rounded">Settings</a>
                        <hr class="my-2 border-soft-lilac opacity-20">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left p-2 hover:bg-red-900 rounded">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <ul class="sidebar-menu">
                @if(Auth::user()->role_name === 'admin')
                    <!-- Admin Menu -->
                    <li class="sidebar-item">
                        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <span>📊</span> Overview
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <span>👥</span> Users
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <span>🏪</span> Vendors
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <span>📦</span> Products
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <span>🛒</span> Orders
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <span>📢</span> Advertisements
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <span>💳</span> Payments
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <span>📁</span> Categories
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <span>💬</span> Messages
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <span>⚙️</span> Settings
                        </a>
                    </li>
                    
                @elseif(Auth::user()->role_name === 'vendor')
                    <!-- Vendor Menu -->
                    <li class="sidebar-item">
                        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <span>📊</span> Dashboard
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <span>🛍️</span> My Products
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <span>➕</span> Add Product
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <span>📦</span> Orders
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <span>🔄</span> Rentals
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <span>📢</span> My Ads
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <span>💬</span> Messages
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <span>⭐</span> Reviews
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <span>💰</span> Earnings
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <span>📈</span> Analytics
                        </a>
                    </li>
                    
                @else
                    <!-- Member Menu -->
                    <li class="sidebar-item">
                        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <span>📊</span> Dashboard
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="/" class="sidebar-link">
                            <span>🛍️</span> Browse Products
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <span>📦</span> My Orders
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <span>🔄</span> My Rentals
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <span>💬</span> Messages
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <span>⭐</span> My Reviews
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <span>❤️</span> Wishlist
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('profile.edit') }}" class="sidebar-link">
                            <span>👤</span> Profile
                        </a>
                    </li>
                @endif
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            {{ $slot }}
        </main>
    </div>

    <!-- Scripts -->
    <script>
        function toggleUserMenu() {
            const menu = document.getElementById('userMenu');
            menu.classList.toggle('hidden');
        }
        
        function toggleNotifications() {
            alert('Notifications feature coming soon!');
        }
        
        // Close user menu when clicking outside
        window.addEventListener('click', function(e) {
            const userMenu = document.getElementById('userMenu');
            if (!e.target.closest('button') && !userMenu.contains(e.target)) {
                userMenu.classList.add('hidden');
            }
        });
        
        // Mobile sidebar toggle
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        
        if (menuToggle) {
            menuToggle.addEventListener('click', () => {
                sidebar.classList.toggle('open');
            });
        }
    </script>
</body>
</html>
