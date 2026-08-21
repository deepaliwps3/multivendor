<div>
    <h4 class="card-title text-danger">Delete Account</h4>
    <h6 class="card-subtitle text-muted mb-4">
        Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your
        account, please download any data or information that you wish to retain.
    </h6>

    <button type="button" class="btn btn-danger rounded-pill px-4" data-bs-toggle="modal"
        data-bs-target="#confirmUserDeletionModal">
        <i data-feather="trash-2" class="feather-icon me-1"></i> Delete Account
    </button>

    <!-- Delete Account Confirmation Modal -->
    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header">
                        <h5 class="modal-title text-danger" id="confirmUserDeletionModalLabel">Are you sure you want to
                            delete your account?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-muted small">
                            Once your account is deleted, all of its resources and data will be permanently deleted.
                            Please enter your password to confirm you would like to permanently delete your account.
                        </p>

                        <div class="mb-3">
                            <label for="delete_account_password" class="form-label font-weight-medium">Password</label>
                            <input type="password" id="delete_account_password" name="password"
                                class="form-control @if (isset($errors) && $errors->userDeletion->has('password')) is-invalid @endif"
                                placeholder="Enter your password" required>
                            @if (isset($errors) && $errors->userDeletion->has('password'))
                                <div class="invalid-feedback d-block">{{ $errors->userDeletion->first('password') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Permanently Delete Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
