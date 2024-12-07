<x-app-layout>
        <style>
               body {
        margin: 0;
        padding: 0;
        position: relative;
        font-family: Arial, sans-serif;
    }

    /* Create a pseudo-element for the background */
    body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('{{ asset("css/bg.jfif") }}') no-repeat center center fixed;
        background-size: cover;
        filter: opacity(0.3) ; /* Apply filters here */
        z-index: -1; /* Ensure the background is behind all content */
    }
        </style>

    <div class="d-flex justify-content-center align-items-center  flex-column p-5">
        <h3 class="text-center mb-5 fw-bold">CREATE ACCOUNT</h3>

        <form id="registerForm" method="POST" action="{{ route('register') }}" style="width: 100%; max-width: 400px;">
            @csrf

            <!-- Username -->
            <div class="form-group position-relative mb-4">
                <input type="text" class="form-control animated-input bg-light text-dark border-secondary" name="name" id="name" value="{{ old('name') }}" required>
                <label for="name" class="floating-label">User Name</label>
               
            </div>
            @error('name')

            <div class="text-danger mb-3" style="font-size: 10px">{{ $message }}</div>
        @enderror

            <!-- First Name and Last Name -->
            <div class="row">
                <div class="form-group position-relative col-6 mb-4">
                    <input type="text" class="form-control animated-input bg-light text-dark border-secondary" name="fname" id="fname" value="{{ old('fname') }}" required>
                    <label for="fname" class="floating-label">First Name</label>
                </div>
                <div class="form-group position-relative col-6 mb-4">
                    <input type="text" class="form-control animated-input bg-light text-dark border-secondary" name="lname" id="lname" value="{{ old('lname') }}" required>
                    <label for="lname" class="floating-label">Last Name</label>
                </div>
            </div>

            <!-- Email -->
            <div class="form-group position-relative mb-4">
                <input type="email" class="form-control animated-input bg-light text-dark border-secondary" name="email" id="email" value="{{ old('email') }}" required>
                <label for="email" class="floating-label">Email</label>
               
            </div>
            @error('email')
            <div  class="text-danger mb-3" style="font-size: 10px">{{ $message }}</div>
        @enderror
            <!-- Password -->
            <div class="form-group position-relative mb-4">
                <input type="password" class="form-control animated-input bg-light text-dark border-secondary pe-5" name="password" id="password" required>
                <label for="password" class="floating-label">Password</label>
                <button type="button" class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-2" style="background: transparent; border: none;" onclick="togglePasswordVisibility('password', this)">
                    <i class="fas fa-eye"></i>
                </button>
               
            </div>
            @error('password')
            <div  class="text-danger mb-3" style="font-size: 10px">{{ $message }}</div>
        @enderror

            <!-- Confirm Password -->
            <div class="form-group position-relative mb-4">
                <input type="password" class="form-control animated-input bg-light text-dark border-secondary pe-5" name="password_confirmation" id="password_confirmation" required>
                <label for="password_confirmation" class="floating-label">Confirm Password</label>
                <button type="button" class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-2" style="background: transparent; border: none;" onclick="togglePasswordVisibility('password_confirmation', this)">
                    <i class="fas fa-eye"></i>
                </button>
              
            </div>
            @error('password_confirmation')
            <div  class="text-danger mb-3" style="font-size: 10px">{{ $message }}</div>
        @enderror

            <!-- Terms and Conditions -->
            <div class="form-check mb-4">
                <input type="checkbox" class="form-check-input" id="terms" required>
                <label class="form-check-label text-muted" for="terms" required>
                    By checking this box, you agree to EasyBuilder's <a href="#" class="text-primary">Terms of Use</a> and <a href="#" class="text-primary">Privacy Policy</a>.
                </label>
            </div>

            <!-- Submit Button -->
            <div class="d-grid mb-3">
                <button id="registerButton " type="submit" class="btn  btn-dark bg-black">Sign up</button>
            </div>

            <!-- Login Redirect -->
            <div class="text-center">
                <p class="text-muted">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-primary">Sign in</a>
                </p>
            </div>
        </form>
    </div>



</x-app-layout>
