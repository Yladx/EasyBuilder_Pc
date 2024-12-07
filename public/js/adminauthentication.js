$(document).ready(function() {
    // Disable the button initially
    $("#loginButton").prop("disabled", true);

    // Validate form on submit
    $("#loginForm").validate({
        rules: {
            username: {
                required: true,
            },
            password: {
                required: true,
                minlength: 5,
                maxlength: 9
            }
        },
        messages: {
            username: {
                required: "Please enter your username"
            },
            password: {
                required: "Please enter your password",
                minlength: "Password must be at least 5 characters",
                maxlength: "Password must not exceed 9 characters"
            }
        },
        // Enable the button if the form is valid
        submitHandler: function(form) {
            form.submit(); // Submit the form
        }
    });

    // Monitor input fields for changes
    $("#username, #password").on('input', function() {
        // Check if the form is valid
        if ($("#loginForm").valid()) {
            $("#loginButton").prop("disabled", false);
        } else {
            $("#loginButton").prop("disabled", true);
        }
    });



});
