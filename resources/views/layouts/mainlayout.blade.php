<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BarangSiapa') }}</title>
    <!-- Include Bootstrap CSS from CDN, then Tailwind (Tailwind set to important) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>tailwind = window.tailwind || {}; tailwind.config = tailwind.config || {}; tailwind.config.important = true;</script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('styles/style.css') }}">
    <style>
        /* Static Background with Gradient */
        .static-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8e0f0 50%, #f0e6f6 100%);
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Static Background -->
    <div class="static-bg"></div>

    @include('layouts.navigation')

    <main class="min-vh-100" style="position: relative; z-index: 1;">
        @yield('content')
    </main>

    @include('layouts.footer')

    <!-- CDN scripts: Axios, Alpine, Bootstrap bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        /* Dropdown styling */
        .dropdown-item:hover {
            background-color: #f8f9fa !important;
        }
        [x-cloak] {
            display: none !important;
        }
    </style>

    <script>
        // Axios default CSRF header from Laravel meta tag
        (function(){
            var token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (token && window.axios) {
                window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
            }
        })();

        // Ensure Alpine starts when loaded
        window.addEventListener('alpine:init', () => {
            if (window.Alpine) {
                window.Alpine.start && window.Alpine.start();
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
