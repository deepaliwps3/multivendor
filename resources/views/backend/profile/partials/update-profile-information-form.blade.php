<div>
    <h4 class="card-title">Profile Information</h4>
    <h6 class="card-subtitle text-muted mb-4">Update your account's profile information and email address.</h6>

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Profile updated successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label font-weight-medium">Full Name</label>
            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label font-weight-medium">Email Address</label>
            <input type="email" id="email" name="email" readonly
                class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}"
                required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-warning small mb-1">Your email address is unverified.</p>
                    <button form="send-verification" class="btn btn-sm btn-outline-primary">
                        Click here to re-send the verification email.
                    </button>
                    @if (session('status') === 'verification-link-sent')
                        <p class="text-success small mt-2 mb-0">A new verification link has been sent to your email
                            address.</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary rounded-pill px-4">
                <i data-feather="save" class="feather-icon me-1"></i> Save Changes
            </button>
        </div>
    </form>
</div>
