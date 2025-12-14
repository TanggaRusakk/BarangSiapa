<section>
    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label fw-bold">Name</label>
            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus>
            @error('name')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label fw-bold">Email</label>
            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="alert alert-warning mt-2">
                    <p class="mb-0 small">
                        Your email address is unverified.
                        <button type="button" onclick="document.getElementById('send-verification').submit()" class="btn btn-link p-0 align-baseline">
                            Click here to re-send the verification email.
                        </button>
                    </p>
                </div>

                @if (session('status') === 'verification-link-sent')
                    <div class="alert alert-success mt-2">
                        A new verification link has been sent to your email address.
                    </div>
                @endif
            @endif
        </div>

        <div class="d-flex gap-2 align-items-center">
            <button type="submit" class="btn" style="background: #6A38C2; color: white;">Save Changes</button>

            @if (session('status') === 'profile-updated')
                <span class="text-success small">Saved.</span>
            @endif
        </div>
    </form>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-none">
        @csrf
    </form>
</section>
