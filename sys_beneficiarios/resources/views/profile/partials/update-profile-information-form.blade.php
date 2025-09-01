<section>
    <header class="mb-3">
        <h2 class="h5 mb-1">{{ __('Profile Information') }}</h2>
        <p class="text-muted small mb-0">{{ __("Update your account's profile information and email address.") }}</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-none">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" novalidate>
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus
                   class="form-control @error('name') is-invalid @enderror">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                   class="form-control @error('email') is-invalid @enderror">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <div class="alert alert-warning py-2 mb-2" role="alert">
                        {{ __('Your email address is unverified.') }}
                    </div>
                    <button form="send-verification" class="btn btn-sm btn-outline-primary">{{ __('Click here to re-send the verification email.') }}</button>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success mt-2 py-2" role="alert">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>

            @if (session('status') === 'profile-updated')
                <span class="text-muted small">{{ __('Saved.') }}</span>
            @endif
        </div>
    </form>
</section>
