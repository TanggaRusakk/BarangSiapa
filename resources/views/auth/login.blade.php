<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - BarangSiapa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('styles/style.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-animated cyber-grid">
    <main class="hero light-hero">
        <div class="hero-content">
            <div class="card" style="max-width:640px; margin: 0 auto;">
                <div style="padding:2rem; display:flex; flex-direction:column; justify-content:center;">
                    <h1 class="text-4xl font-bold text-gradient mb-2">Welcome Back</h1>
                    <p class="text-soft-lilac mb-4">Sign in to continue shopping, renting, and managing your account.</p>

                    @if (session('status'))
                        <div class="notification">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group">
                            <label class="form-label" for="email">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="form-input">
                            @error('email') <div class="text-sm text-neon-pink mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password">Password</label>
                            <input id="password" name="password" type="password" required class="form-input">
                            @error('password') <div class="text-sm text-neon-pink mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-top:1rem;">
                            <label style="display:flex; align-items:center; gap:0.5rem;">
                                <input type="checkbox" name="remember">
                                <span class="text-soft-lilac">Remember me</span>
                            </label>

                            <div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-sm text-soft-lilac underline">Forgot your password?</a>
                                @endif
                            </div>
                        </div>

                        <div style="margin-top:1.25rem; display:flex; gap:0.75rem;">
                            <button type="submit" class="btn btn-primary">Log in</button>
                            <a href="{{ route('register') }}" class="btn btn-secondary">Create account</a>
                        </div>

                        <div style="margin-top:1rem; text-align:center;">
                            <a href="{{ url('/') }}" class="text-soft-lilac" style="text-decoration:underline;">Back to home</a>
                        </div>
                    </form>
                </div>

                <!-- right-side info panel removed per request -->
            </div>
        </div>
    </main>
</body>
</html>
