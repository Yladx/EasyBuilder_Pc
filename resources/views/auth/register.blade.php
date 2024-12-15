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
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="acceptTerms" name="accept_terms">
                    <label class="form-check-label" for="acceptTerms">I agree to the Terms and Conditions</label>
                </div>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const acceptTermsCheckbox = document.getElementById('acceptTerms');
        const registrationForm = document.getElementById('registerForm');

        // Read Terms and Conditions content
        const termsContent = `{!! file_get_contents(storage_path('terms_and_conditions.txt')) !!}`;

        // Prevent default checkbox behavior
        acceptTermsCheckbox.addEventListener('click', function(event) {
            // Prevent the checkbox from being checked
            event.preventDefault();
            
            // Show SweetAlert with Terms and Conditions
            Swal.fire({
                title: 'Terms and Conditions',
                html: `
                    <div class="terms-content-wrapper">
                        <pre class="terms-content">${termsContent}</pre>
                    </div>
                `,
                icon: false,
                showConfirmButton: false,
                showCancelButton: false,
                width: '50%',
                maxWidth: '800px',
                padding: '15px',
                customClass: {
                    popup: 'terms-popup',
                    title: 'terms-title',
                    htmlContainer: 'terms-html-container'
                },
                footer: `
                    <div class="terms-footer">
                        <div class="terms-footer-content row">
                            <div class="terms-agreement-wrapper col-md-6 col-12">
                                <input type="checkbox" id="termsAgreementCheckbox" class="swal2-checkbox">
                                <label for="termsAgreementCheckbox" class="terms-agreement-label">
                                    I have read and understand the Terms and Conditions
                                </label>
                            </div>
                            <div class="terms-footer-buttons col-md-6 col-12">
                                <button type="button" class="swal2-cancel swal2-styled" id="termsModalCancel">Cancel</button>
                                <button type="button" class="swal2-confirm swal2-styled" id="termsModalAccept" disabled>Accept</button>
                            </div>
                        </div>
                    </div>
                `,
                didRender: (popup) => {
                    const htmlContainer = popup.querySelector('.swal2-html-container');
                    htmlContainer.style.maxHeight = '50vh';
                    htmlContainer.style.overflowY = 'auto';
                    htmlContainer.style.overflowX = 'hidden';
                    htmlContainer.style.textAlign = 'left';
                    htmlContainer.style.padding = '10px';

                    // Add event listeners to custom buttons
                    const termsAgreementCheckbox = document.getElementById('termsAgreementCheckbox');
                    const termsModalAccept = document.getElementById('termsModalAccept');
                    const termsModalCancel = document.getElementById('termsModalCancel');

                    // Enable/disable accept button based on checkbox
                    termsAgreementCheckbox.addEventListener('change', function() {
                        termsModalAccept.disabled = !this.checked;
                    });

                    // Cancel button functionality
                    termsModalCancel.addEventListener('click', () => {
                        Swal.close();
                    });

                    // Accept button functionality
                    termsModalAccept.addEventListener('click', () => {
                        // If confirmed, check the checkbox and enable submit
                        acceptTermsCheckbox.checked = true;
                        
                        // Enable submit button
                        const submitButton = registrationForm.querySelector('button[type="submit"]');
                        if (submitButton) {
                            submitButton.disabled = false;
                        }
                        
                        Swal.close();
                    });
                }
            });
        });

        // Prevent form submission if terms are not accepted
        registrationForm.addEventListener('submit', function(event) {
            if (!acceptTermsCheckbox.checked) {
                event.preventDefault();
                
                // Show warning if trying to submit without accepting terms
                Swal.fire({
                    title: 'Terms Not Accepted',
                    text: 'Please read and accept the Terms and Conditions to continue.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
    </script>

    <style>
        /* SweetAlert custom styling */
        .swal2-popup {
            font-size: 1.2rem;
            padding: 2em;
            max-width: 95vw;
            width: auto !important;
            box-sizing: border-box;
        }
        
        .swal2-title {
            font-size: 1.8em;
            margin-bottom: 0.8em;
            word-wrap: break-word;
        }
        
        .swal2-content {
            font-size: 1.2em;
            padding: 0 1em;
            word-wrap: break-word;
        }
        
        .swal2-confirm {
            padding: 0.8em 2em;
            font-size: 1.2em;
            white-space: normal;
            height: auto;
            word-wrap: break-word;
            max-width: 100%;
        }

        .swal2-checkbox {
            margin: 1em;
            transform: scale(1.2);
        }
        
        @media screen and (max-width: 480px) {
            .swal2-popup {
                padding: 1.5em;
                width: 95vw !important;
                margin: 0.5em;
            }
            
            .swal2-title {
                font-size: 1.6em;
                line-height: 1.3;
            }
            
            .swal2-content {
                font-size: 1.1em;
                padding: 0 0.5em;
            }
            
            .swal2-confirm {
                padding: 0.7em 1.5em;
                font-size: 1.1em;
                margin: 0.5em auto;
                max-width: 90%;
            }

            .swal2-checkbox {
                margin: 0.8em;
                transform: scale(1.1);
            }
        }

        /* Ensure content doesn't overflow */
        .swal2-container {
            padding: 10px;
        }

        /* Better touch targets for checkboxes */
        .swal2-checkbox input[type='checkbox'] {
            min-width: 20px;
            min-height: 20px;
            cursor: pointer;
        }
    </style>

    <style>
    /* Terms and Conditions styling */
    .terms-popup {
        font-family: Arial, sans-serif;
    }

    .terms-title {
        font-size: 1em;
        font-weight: bold;
        color: #333;
    }

    .terms-html-container {
        width: 100%;
        max-width: 100%;
        padding: 5px;
        box-sizing: border-box;
    }

    .terms-content-wrapper {
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
    }

    .terms-content {
        white-space: pre-wrap;
        word-wrap: break-word;
        text-align: left;
        font-family: Arial, sans-serif;
        font-size: 12px;
        line-height: 1.5;
        max-width: 100%;
        overflow-x: hidden;
        padding: 5px;
        background-color: #f9f9f9;
        border-radius: 3px;
        border: 1px solid #e0e0e0;
    }

    .terms-footer {
        position: sticky;
        bottom: 0;
        background: white;
        padding: 10px;
        z-index: 10;
        width: 100%;
        left: 0;
        right: 0;
        box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
    }

    .terms-footer-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .terms-agreement-wrapper {
        display: flex;
        align-items: center;
    }

    .terms-footer-buttons {
        display: flex;
        gap: 10px;
    }

    .terms-agreement-label {
        margin-left: 10px;
        font-size: 12px;
        color: #333;
        user-select: none;
    }

    .swal2-checkbox {
        margin-right: 10px;
    }

    .swal2-styled {
        padding: 6px 12px;
        font-size: 12px;
    }

    .swal2-cancel {
        background-color: #f0f0f0 !important;
        color: #333 !important;
    }

    .swal2-confirm {
        background-color: #3085d6 !important;
        color: white !important;
    }

    .swal2-confirm:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    </style>

</x-app-layout>
