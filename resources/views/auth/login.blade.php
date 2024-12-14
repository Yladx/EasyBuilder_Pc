<x-app-layout>
    <div class="d-flex justify-content-center align-items-center" style="min-height: 88vh;">
        <style>
            body {
                margin: 0;
                padding: 0;
            }

            body::before {
                content: "";
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: url('{{ asset("css/bg.jfif") }}') no-repeat center center fixed;
                background-size: cover;
                filter: opacity(0.3);
                z-index: -1;
            }

            .login-card {
                border-radius: 15px;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
                padding: 40px 30px;
                background-color: #ffffff;
            }
        </style>

        <div class="login-card" style="width: 100%; max-width: 400px;">
            <!-- Page Title -->
            <h3 class="text-center mb-5 fw-bold">Sign In</h3>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Login Form -->
            <form id="loginForm" method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                @csrf

                <!-- Email Address -->
                <div class="form-group position-relative mb-4">
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control animated-input bg-light text-dark border-secondary @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus>
                    <label for="email" class="floating-label">Email</label>
                   
                </div>
                @error('email')
                <div  class="text-danger mb-3" style="font-size: 10px">>{{ $message }}</div>
            @enderror
                <!-- Password -->
                <div class="form-group position-relative mb-4">
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control animated-input bg-light text-dark border-secondary @error('password') is-invalid @enderror" 
                           required>
                    <label for="password" class="floating-label">Password</label>
                    <button type="button" 
                            class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-2" 
                            style="background: transparent; border: none;" 
                            onclick="togglePasswordVisibility('password', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                   
                </div>
                @error('password')
                <div  class="text-danger mb-3" style="font-size: 10px">>{{ $message }}</div>
            @enderror
                <!-- Remember Me and Forgot Password -->
                <div class="row align-items-center mb-3"  style="font-size: 14px;">
                    <div class="col-lg-6 col-md-6 col-12 text-lg-start text-center">
                        <div class="form-check d-flex align-items-center">
                            <input id="remember_me" type="checkbox" class="form-check-input me-2 border-secondary" name="remember">
                            <label for="remember_me" class="form-check-label text-muted">Remember me</label>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12 text-lg-end text-center mt-3 mt-md-0">
                        @if (Route::has('password.request'))
                            <a class="text-muted" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>
                </div>
 <!-- reCAPTCHA -->
                    <div class="d-flex justify-content-center mb-3">
                        {!! htmlFormSnippet() !!}
                        {!! htmlScriptTagJsApi() !!}
                    </div>


    @error('g-recaptcha-response')
        <div class="text-danger mb-3 text-center" style="font-size: 10px">{{ $message }}</div>
    @enderror
                <div class="d-grid gap-2">
                    <button type="submit" id="loginButton" class="btn btn-dark bg-black">
                        Sign in
                    </button>
                </div>
            </form>

           

            <!-- Sign Up Redirect -->
            <div class="text-center mt-3">
                <p class="text-muted">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-secondary">
                        Sign up now
                    </a>
                </p>
            </div>
        </div>

    </div>

</x-app-layout>
