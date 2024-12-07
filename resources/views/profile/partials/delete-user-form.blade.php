<div class="p-4 bg-white rounded-lg shadow-lg dark:bg-gray-800">
    <h2 class="text-lg font-medium text-danger">
        {{ __('Delete Account') }}
    </h2>

    <p class="mt-1 text-sm text-muted">
        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
    </p>

    <!-- Button to open modal -->
    <button class="btn btn-danger fw-bold" data-bs-toggle="modal" data-bs-target="#confirmDeletionModal">
        <i class="fas fa-trash-alt me-2"></i> Delete Account
    </button>

    <!-- Modal -->
    <div class="modal fade" id="confirmDeletionModal" tabindex="-1" aria-labelledby="confirmDeletionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold" id="confirmDeletionModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i> {{ __('Are you sure you want to delete your account?') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                    </p>
                    <form method="post" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('delete')

                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">{{ __('Password') }}</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control border-danger"
                                placeholder="{{ __('Enter your password') }}"
                                required
                            >
                            @if ($errors->userDeletion->has('password'))
                                <div class="text-danger mt-2">
                                    {{ $errors->userDeletion->first('password') }}
                                </div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash-alt me-2"></i> Delete Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
