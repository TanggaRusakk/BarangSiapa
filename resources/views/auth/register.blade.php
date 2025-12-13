<x-guest-layout>
    @php $role = request('role', 'user'); @endphp
    <div class="mb-4 text-center">
        <a href="{{ route('register') }}?role=user" class="inline-block px-3 py-1 rounded-md mr-2" style="text-decoration:none; border:1px solid transparent; background: {{ $role==='user' ? 'rgba(106,56,194,0.15)' : 'transparent' }};">Register as User</a>
        <a href="{{ route('register') }}?role=vendor" class="inline-block px-3 py-1 rounded-md" style="text-decoration:none; border:1px solid transparent; background: {{ $role==='vendor' ? 'rgba(106,56,194,0.15)' : 'transparent' }};">Register as Vendor</a>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <p class="text-sm text-gray-400 mb-4">
            @if($role === 'vendor')
                Registering as a <strong>vendor</strong> will allow you to create a vendor profile and list products. You'll still need a user account — after registering we'll set your role to <em>vendor</em>.
            @else
                Registering as a <strong>user</strong> lets you browse and rent items. If you want to sell or list items, choose the "Register as Vendor" option.
            @endif
        </p>

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

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm rounded-md" style="color: var(--soft-lilac);" href="{{ route('login') }}" onmouseover="this.style.color='var(--neon-pink)';" onmouseout="this.style.color='var(--soft-lilac)';">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
