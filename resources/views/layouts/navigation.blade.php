<nav class="shadow-sm" style="background: linear-gradient(135deg, rgba(106, 56, 194, 0.95) 0%, rgba(255, 60, 172, 0.95) 100%); backdrop-filter: blur(10px); position: relative; z-index: 1000;">
    <div class="container-fluid px-3 px-md-4">
        <div x-data="{open:false, profileOpen:false}" class="d-flex align-items-center justify-content-between" style="min-height: 64px;">
            <!-- Logo Only -->
            <div>
                <a href="{{ url('/') }}" class="navbar-brand fw-bold mb-0 text-white" style="font-size: 1.25rem; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    BarangSiapa
                </a>
            </div>

            <!-- All Navigation Items to the Right -->
            <div class="d-flex align-items-center gap-4">
                <!-- Main Navigation Links -->
                <div class="d-none d-md-flex align-items-center gap-4">
                    <a href="{{ url('/') }}" class="nav-link text-white fw-medium px-0 {{ request()->is('/') ? 'active' : '' }}" style="position: relative;">
                        Home
                        @if(request()->is('/'))
                            <span style="position: absolute; bottom: -8px; left: 0; right: 0; height: 3px; background: white; border-radius: 2px;"></span>
                        @endif
                    </a>
                    <a href="{{ route('items.index') }}" class="nav-link text-white fw-medium px-0 {{ request()->is('items*') ? 'active' : '' }}" style="position: relative;">
                        Items
                        @if(request()->is('items*'))
                            <span style="position: absolute; bottom: -8px; left: 0; right: 0; height: 3px; background: white; border-radius: 2px;"></span>
                        @endif
                    </a>
                </div>

                @auth
                    <!-- Messages Icon -->
                    <a href="{{ url('/messages') }}" title="Messages" class="btn btn-link text-white d-none d-md-inline-flex p-2" style="position: relative;">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                    </a>

                    <!-- User Dropdown -->
                    <div class="position-relative d-none d-md-block">
                        <button @click="profileOpen = !profileOpen" type="button" class="btn btn-link text-white d-flex align-items-center gap-2 text-decoration-none p-2" style="border: 1px solid rgba(255,255,255,0.2); border-radius: 50px;">
                            <img src="{{ auth()->user()->photo_url }}" alt="avatar" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover; border: 2px solid rgba(255,255,255,0.3);">
                            <span class="fw-medium">{{ Str::limit(auth()->user()->name ?? '', 15) }}</span>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="profileOpen" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             @click.outside="profileOpen = false" 
                             class="position-absolute end-0 mt-2 bg-dark text-white rounded shadow-lg" 
                             style="min-width: 220px; z-index: 9999; border: 1px solid rgba(255,255,255,0.06);"
                             x-cloak>
                            <div class="py-1">
                                <a class="dropdown-item py-2 px-4 d-flex align-items-center gap-2 text-white" href="{{ route('dashboard') }}" style="transition: background-color 0.2s; color: #ffffff;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    <span class="fw-medium">Dashboard</span>
                                </a>
                                <a class="dropdown-item py-2 px-4 d-flex align-items-center gap-2 text-white" href="{{ route('profile.edit') }}" style="transition: background-color 0.2s; color: #ffffff;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="fw-medium">Profile</span>
                                </a>
                                <a class="dropdown-item py-2 px-4 d-flex align-items-center gap-2 text-white" href="{{ route('orders.my-orders') }}" style="transition: background-color 0.2s; color: #ffffff;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    <span class="fw-medium">My Orders</span>
                                </a>
                                @if(auth()->check() && auth()->user()->role === 'vendor')
                                    <a class="dropdown-item py-2 px-4 d-flex align-items-center gap-2 text-white" href="{{ route('vendor.ads.index') }}" style="transition: background-color 0.2s; color: #ffffff;">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                        </svg>
                                        <span class="fw-medium">My Ads</span>
                                    </a>
                                @endif
                                @if(auth()->check() && auth()->user()->image_path)
                                    <div class="dropdown-divider my-1" style="border-color: rgba(255,255,255,0.06);"></div>
                                    <form method="POST" action="{{ route('profile.photo.remove') }}" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item py-2 px-4 text-danger d-flex align-items-center gap-2" onclick="return confirm('Remove profile photo?')" style="transition: background-color 0.2s;">
                                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            <span class="fw-medium">Remove Photo</span>
                                        </button>
                                    </form>
                                @endif
                                <div class="dropdown-divider my-1" style="border-color: rgba(255,255,255,0.06);"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 px-4 d-flex align-items-center gap-2 text-white" style="transition: background-color 0.2s; color: #ffffff;">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        <span class="fw-medium">Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Guest Links -->
                    <div class="d-none d-md-flex align-items-center gap-3">
                        <a class="btn btn-link text-white text-decoration-none fw-medium px-0" href="{{ route('login') }}">Login</a>
                        <a class="btn text-white fw-medium" href="{{ route('register') }}" style="background: rgba(0,0,0,0.2); border-radius: 50px; padding: 8px 24px; border: 1px solid rgba(255,255,255,0.3);">Register</a>
                    </div>
                @endauth

                <!-- Mobile Menu Toggle -->
                <button @click="open = !open" type="button" class="btn btn-link text-white d-md-none p-2"> 
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="open" 
             x-transition
             @click.outside="open=false"
             class="d-md-none border-top border-white border-opacity-25 py-3">
            <a href="{{ url('/') }}" class="d-block py-2 text-white text-decoration-none fw-medium {{ request()->is('/') ? 'fw-bold' : '' }}">Home</a>
            <a href="{{ route('items.index') }}" class="d-block py-2 text-white text-decoration-none fw-medium {{ request()->is('items*') ? 'fw-bold' : '' }}">Items</a>
            @auth
                <a href="{{ url('/messages') }}" class="d-block py-2 text-white text-decoration-none fw-medium">Messages</a>
                <div class="dropdown-divider bg-white opacity-25"></div>
                <a href="{{ route('dashboard') }}" class="d-block py-2 text-white text-decoration-none">Dashboard</a>
                <a href="{{ route('profile.edit') }}" class="d-block py-2 text-white text-decoration-none">Profile</a>
                <a href="{{ route('orders.my-orders') }}" class="d-block py-2 text-white text-decoration-none">My Orders</a>
                @if(auth()->check() && auth()->user()->role === 'vendor')
                    <a href="{{ route('vendor.ads.index') }}" class="d-block py-2 text-white text-decoration-none">My Ads</a>
                @endif
                <div class="dropdown-divider bg-white opacity-25"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link text-white text-decoration-none p-0 d-block py-2 w-100 text-start">Logout</button>
                </form>
            @else
                <div class="dropdown-divider bg-white opacity-25"></div>
                <a href="{{ route('login') }}" class="d-block py-2 text-white text-decoration-none">Login</a>
                <a href="{{ route('register') }}" class="d-block py-2 text-white text-decoration-none">Register</a>
            @endauth
        </div>
    </div>
</nav>
