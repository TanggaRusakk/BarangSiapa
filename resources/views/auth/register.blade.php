<x-guest-layout>
    <div class="auth-card">
    @php $role = request('role', 'user'); @endphp
    
    <!-- Site Title above form -->
    <div class="text-center mb-4">
        <h1 class="h3 fw-bold text-gradient mb-1">BarangSiapa</h1>
    </div>

    <!-- Page Title -->
    <div class="text-center mb-4">
        <h2 class="text-3xl font-bold text-gradient mb-2">Create Account</h2>
        <p class="text-secondary">Join BarangSiapa marketplace</p>
    </div>

    <!-- Role Selector -->
    <div class="mb-4 text-center">
        <div class="btn-group w-100" role="group">
            <a href="{{ route('register') }}?role=user" class="btn {{ $role==='user' ? 'btn-primary' : 'btn-outline-secondary' }}" style="text-decoration:none;">👤 User</a>
            <a href="{{ route('register') }}?role=vendor" class="btn {{ $role==='vendor' ? 'btn-primary' : 'btn-outline-secondary' }}" style="text-decoration:none;">🏪 Vendor</a>
        </div>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="alert {{ $role === 'vendor' ? 'alert-info' : 'alert-light' }} mb-4 small">
            @if($role === 'vendor')
                <strong>🏪 Vendor Account:</strong> Create a store and list products for sale or rent.
            @else
                <strong>👤 User Account:</strong> Browse and rent items from the marketplace.
            @endif
        </div>

        <input type="hidden" name="role" value="{{ $role }}">

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="d-grid gap-2 mt-4">
            <x-primary-button class="w-100">
                {{ __('Register') }}
            </x-primary-button>
        </div>
        
        <div class="mt-4 text-center">
            <a class="text-sm" style="color: var(--soft-lilac); text-decoration: none;" href="{{ route('login') }}" onmouseover="this.style.color='var(--neon-pink)';" onmouseout="this.style.color='var(--soft-lilac)';">
                Already have an account? <strong>Sign in</strong>
            </a>
        </div>

        
    </form>
    </div>
</x-guest-layout>
