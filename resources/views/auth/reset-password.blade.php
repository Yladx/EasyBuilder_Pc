<x-noheader-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');

        body {
            font-family: "Roboto", sans-serif;
            background: url('{{ asset("css/bg1.jfif") }}') no-repeat center center fixed;
            background-size: cover;
            color: white;
            font-family: Arial, sans-serif;
        }
    </style>

    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg" style="width: 28rem; border-radius: 15px; background-color: #323131; color: white; padding: 20px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);">

                 <!-- Logo -->
            <div class="text-center mb-4">
                <img src="{{ asset('logo.svg') }}" alt="Logo" width="120" height="auto" style="filter:opacity(0.4)">
            </div>

            <!-- Card Content -->
            <div class="card-body">
                <!-- Reset Password Heading -->
                <h5 class="card-title text-center" style="font-weight: bold;">{{ __('Reset Your Password') }}</h5>
                <p class="text-center mt-4" style="font-size: 0.9rem; color: #ccc;">
                    Enter a new password to reset the password on your account. We’ll ask for this password whenever you sign in.
                </p>

                <!-- Password Reset Form -->
                <form method="POST" action="{{ route('password.store') }}" class="mt-4">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email Address (Hidden) -->
                    <div style="display: none;">
                        <label for="email" class="form-label">{{ __('Email') }}</label>
                        <input type="email" id="email" name="email" class="form-control"
                               value="{{ old('email', $request->email) }}" readonly required autofocus>
                    </div>

                    <!-- Password -->
                    <div class="form-group position-relative mb-3">
                        <label for="password" style="font-weight: bold; color: #ccc;">Create New Password</label>
                        <input type="password" class="form-control" name="password" id="password" required
                               style="border: 1px solid #444; border-radius: 5px;">
                        <small class="text-white" style="font-size: 0.8rem; filter:opacity(0.8);">
                            - Your password must be 8–15 characters long.<br>
                            - Must contain uppercase and lowercase letters (A–Z).<br>
                            - Must contain a number.<br>
                            - Must contain a special character (e.g., !, $, @, etc.).
                        </small>
                        @error('password')
                        <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group position-relative mb-4">
                        <label for="password_confirmation" style="font-weight: bold; color: #ccc;">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="form-control" required autocomplete="new-password"
                               style=" border: 1px solid #444; border-radius: 5px;">
                        @error('password_confirmation')
                        <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-dark w-100" style="border-radius: 5px;">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-noheader-layout>
