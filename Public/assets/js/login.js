$(document).ready(function() {
    // Show sign-up form
    $('#signup-toggle').click(function() {
        $('.login-wrapper').fadeOut(300, function() {
            $('.sign-up-form').fadeIn(300);
        });
    });

    // Show sign-in form
    $('#signin-toggle').click(function() {
        $('.sign-up-form').fadeOut(300, function() {
            $('.login-wrapper').fadeIn(300);
            $('#error-message').hide(); // Hide error message when switching back
        });
    });

    // Hide empty field error message on input
    $('input').on('input', function() {
        $(this).next('.text-danger').hide(); // Hide error message for the current field
    });

    // Sign Up button click event
    

    // Login button click event
    $('#login-btn').click(function(event) {
        event.preventDefault();

        // Clear previous error messages
        $('.text-danger').hide();

        // Validation flags
        let isValid = true;

        // Check for empty fields
        if ($('#email1').val().trim() === '') {
            $('#email-error1').text('Email cannot be empty').show();
            isValid = false;
        }

        if ($('#password1').val().trim() === '') {
            $('#password-error1').text('Password cannot be empty').show();
            isValid = false;
        }

        // If all validations pass, submit the form
        if (isValid) {
            $('#login-form').submit(); // Submits the login form
        }
    });
    
});
