$(document).ready(function() {
    // Disable the button initially
    $("#loginButton").pro
      // Disable the button initially
      $("#adminLoginForm").validate({
        rules: {
            adminUsername: {
                required: true,
            },
            adminPassword: {
                required: true,
                minlength: 5,
                maxlength: 9
            }
        },
        messages: {
            adminUsername: {
                required: "Please enter your username"
            },
            adminPassword: {
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
    $("#adminUsername, #adminPassword").on('input', function() {
        // Check if the form is valid
        if ($("#adminLoginForm").valid()) {
            $("#loginButton").prop("disabled", false); // Enable button
        } else {
            $("#loginButton").prop("disabled", true); // Disable button
        }
    });
});
