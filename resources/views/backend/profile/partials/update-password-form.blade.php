<div>
    <h4 class="card-title">Update Password</h4>
    <h6 class="card-subtitle text-muted mb-4">Ensure your account is using a long, random password to stay secure.</h6>

    @if (session('status') === 'password-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Password updated successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label font-weight-medium">Current Password</label>
            <input type="password" id="update_password_current_password" name="current_password"
                class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                autocomplete="current-password" required>
            @if (isset($errors) && $errors->updatePassword->has('current_password'))
                <div class="invalid-feedback d-block">{{ $errors->updatePassword->first('current_password') }}</div>
            @endif
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="form-label font-weight-medium">New Password</label>
            <input type="password" id="update_password_password" name="password"
                class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                autocomplete="new-password" required>
            @if (isset($errors) && $errors->updatePassword->has('password'))
                <div class="invalid-feedback d-block">{{ $errors->updatePassword->first('password') }}</div>
            @endif
        </div>

        <div class="mb-3">
            <label for="update_password_password_confirmation" class="form-label font-weight-medium">Confirm
                Password</label>
            <input type="password" id="update_password_password_confirmation" name="password_confirmation"
                class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                autocomplete="new-password" required>
            @if (isset($errors) && $errors->updatePassword->has('password_confirmation'))
                <div class="invalid-feedback d-block">{{ $errors->updatePassword->first('password_confirmation') }}
                </div>
            @endif
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary rounded-pill px-4">
                <i data-feather="key" class="feather-icon me-1"></i> Update Password
            </button>
        </div>
    </form>
</div>
