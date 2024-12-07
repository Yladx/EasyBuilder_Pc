<x-app-layout>

    <!-- Session Status -->

    <div class="d-flex justify-content-center align-items-center" style="min-height: 87vh;">
        <div style="max-width: 500px; width: 100%;">

            <form method="POST" action="{{ route('password.email') }}" class="shadow p-4 rounded bg-white dark:bg-gray-800">
                @csrf

                <h3 class="text-center ">{{ __('Reset Password') }}</h3>

                <div class="mb-4 text-muted">
                    {{ __('Forgot your password? Enter your email, and we’ll send you a link to reset it.') }}
                </div>


                <!-- Email Address -->
                <div class="form-group position-relative mb-4">
                    <input type="email" id="email" name="email" class="form-control animated-input bg-light border-secondary" :value="old('email')" required autofocus>
                    <label for="email" class="floating-label text-muted">{{ __('Email') }}</label>
                    @error('email')
                    <div class="text-danger mt-2">
                        {{ $message }}
                    </div>
                    @enderror
                    <x-auth-session-status class="mb-4 text-suc" :status="session('status')" />

                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="btn btn-success w-100">{{ __('Email Password Reset Link') }}</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
