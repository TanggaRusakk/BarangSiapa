<x-guest-layout>
    <div class="auth-card">
    <!-- Site Title above form -->
    <div class="text-center mb-4">
        <h1 class="h3 fw-bold text-gradient mb-1">BarangSiapa</h1>
              <p class="text-secondary">Sign in to continue to BarangSiapa</p>
    </div>


    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded shadow-sm" name="remember">
                <span class="ms-2 text-sm" style="color: var(--soft-lilac);">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            @if (Route::has('password.request'))
                <a class="text-sm" style="color: var(--soft-lilac); text-decoration: none;" href="{{ route('password.request') }}" onmouseover="this.style.color='var(--neon-pink)';" onmouseout="this.style.color='var(--soft-lilac)';">
                    {{ __('Forgot password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
        
        <div class="mt-6 pt-6 border-top text-center">
            <a class="text-sm" style="color: var(--soft-lilac); text-decoration: none;" href="{{ route('register') }}" onmouseover="this.style.color='var(--neon-pink)';" onmouseout="this.style.color='var(--soft-lilac)';">
                Don't have an account? <strong>Register</strong>
            </a>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ url('/') }}" style="color: var(--soft-lilac); text-decoration: none; font-size: 0.9rem;" 
               onmouseover="this.style.color='var(--neon-pink)';" 
               onmouseout="this.style.color='var(--soft-lilac)';">← Back to Home</a>
        </div>
        
    </form>
    </div>
</x-guest-layout>
