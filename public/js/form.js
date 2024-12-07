// Immediately invoke the styling for inputs with values
function initializeInputs() {
    const inputs = document.querySelectorAll('.animated-input');
    inputs.forEach(input => {
        const label = input.nextElementSibling;
        if (input.value && input.value.trim() !== '') {
            label.classList.add('active');
            input.classList.add('has-value');
        }
    });
}

// Call initialization immediately
initializeInputs();

document.addEventListener('DOMContentLoaded', function () {
    // Call initialization again after DOM is fully loaded
    initializeInputs();

    const errorMessages = document.querySelectorAll('.text-danger');

    errorMessages.forEach(error => {
        setTimeout(() => {
            error.style.transition = 'opacity 0.5s ease-out';
            error.style.opacity = '0';
            setTimeout(() => error.remove(), 500);
        }, 10000);
    });

    const inputs = document.querySelectorAll('.animated-input');

    inputs.forEach(input => {
        const label = input.nextElementSibling;

        // Function to update input state
        const updateInputState = () => {
            if (input.value && input.value.trim() !== '') {
                label.classList.add('active');
                input.classList.add('has-value');
            } else {
                label.classList.remove('active');
                input.classList.remove('has-value');
            }
        };

        // Add event listeners
        input.addEventListener('input', updateInputState);
        input.addEventListener('focus', () => {
            label.classList.add('active');
        });
        input.addEventListener('blur', updateInputState);
    });
});

function togglePasswordVisibility(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
