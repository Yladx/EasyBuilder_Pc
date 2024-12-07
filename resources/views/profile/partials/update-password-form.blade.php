<hr>
<h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
    {{ __('Update Password') }}
</h2>

<p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
    {{ __('Ensure your account is using a long, random password to stay secure.') }}
</p>

<form method="post" action="{{ route('password.update') }}" class="mt-3">
    @csrf
    @method('put')

    <!-- Current Password -->
    <div class="form-group position-relative mb-4">
        <input type="password" id="update_password_current_password" name="current_password"
               class="form-control animated-input dark:bg-gray-700 dark:text-gray-200 @error('current_password') is-invalid @enderror"
               autocomplete="current-password" required>
        <label for="update_password_current_password" class="floating-label text-gray-700 dark:text-gray-300">
            {{ __('Current Password') }}
        </label>
        @error('current_password')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
<!-- New Password -->
<div class="form-group position-relative mb-4">
    <input type="password" id="update_password_password" name="password"
           class="form-control animated-input dark:bg-gray-700 dark:text-gray-200 @error('password') is-invalid @enderror"
           autocomplete="new-password" required>
    <label for="update_password_password" class="floating-label text-gray-700 dark:text-gray-300">
        {{ __('New Password') }}
    </label>
    <button type="button" class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-2"
            style="background: transparent; border: none;"
            onclick="togglePasswordVisibility('update_password_password', this)">
        <i class="fas fa-eye"></i>
    </button>
    @error('password')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>

<!-- Confirm Password -->
<div class="form-group position-relative mb-4">
    <input type="password" id="update_password_password_confirmation" name="password_confirmation"
           class="form-control animated-input dark:bg-gray-700 dark:text-gray-200 @error('password_confirmation') is-invalid @enderror"
           autocomplete="new-password" required>
    <label for="update_password_password_confirmation" class="floating-label text-gray-700 dark:text-gray-300">
        {{ __('Confirm Password') }}
    </label>
    <button type="button" class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-2"
            style="background: transparent; border: none;"
            onclick="togglePasswordVisibility('update_password_password_confirmation', this)">
        <i class="fas fa-eye"></i>
    </button>
    @error('password_confirmation')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>

    <!-- Submit Button -->
    <div class="text-end mt-4">
        <button type="submit" class=" easypc-btn" style="width: 100%; max-width: 150px;">
            Update Password
        </button>
    </div>


    <!-- Success Message -->
    @if (session('status') === 'password-updated')
        <div class="mt-3 alert alert-success" role="alert">
            {{ __('Password updated successfully.') }}
        </div>
    @endif
</form>
