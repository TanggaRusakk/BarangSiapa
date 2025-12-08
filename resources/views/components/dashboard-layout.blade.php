<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'BarangSiapa') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('styles/style.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-animated cyber-grid">
    <!-- Top Navigation -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="/" class="logo">BarangSiapa</a>

            <ul class="nav-links">
                <li><a href="/" class="nav-link">Home</a></li>
                <li><a href="{{ url('/dashboard') }}" class="nav-link active">Dashboard</a></li>
            </ul>

            <div class="flex items-center gap-3">
                @auth
                    <div class="text-sm text-soft-lilac">{{ auth()->user()->name }}</div>
                    <div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm">Logout</button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <main class="container section">
        <header class="mb-6">
            @isset($title)
                <h1 class="text-3xl font-bold text-gradient">{{ $title }}</h1>
            @endisset
        </header>

        <div>
            {{ $slot }}
        </div>
    </main>

    <footer class="section" style="background: rgba(9,9,15,0.9);">
        <div class="container text-center text-soft-lilac">© {{ date('Y') }} BarangSiapa</div>
    </footer>
</body>
</html>
