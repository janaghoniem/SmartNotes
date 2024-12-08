document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("form");
    const userIdInput = document.getElementById("user_id");
    const username = document.querySelector("input[name='username']");
    const password = document.querySelector("input[name='password']");
    const firstname = document.querySelector("input[name='firstname']");
    const lastname = document.querySelector("input[name='lastname']");
    const email = document.querySelector("input[name='email']");
    const country = document.querySelector("select[name='country']");
    const submit_btn = document.getElementById("submit_button");
    const reset_btn = document.querySelector("button[type='reset']");

    let originalUsername = '';
    let originalEmail = '';

    const showError = (input, message) => {
        input.style.borderColor = "red";
        const errorElement = document.getElementById(`${input.name}-error`);
        errorElement.textContent = message;
    };

    const clearError = (input) => {
        input.style.borderColor = "";
        const errorElement = document.getElementById(`${input.name}-error`);
        errorElement.textContent = "";
    };

    const checkUsernameEmailExists = (input) => {
        return new Promise((resolve) => {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "../pages/check_user_email.php", true); // Ensure the path is correct
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    console.log("XHR Status: " + xhr.status); // Debugging statement
                    if (xhr.status === 200) {
                        console.log("Response: " + xhr.responseText); // Debugging statement
                        if (xhr.responseText === "exists") {
                            showError(input, `${input.name.charAt(0).toUpperCase() + input.name.slice(1)} already exists.`);
                            resolve(false);
                        } else {
                            clearError(input);
                            resolve(true);
                        }
                    } else {
                        console.error("Error: " + xhr.status); // Debugging statement
                        resolve(false);
                    }
                }
            };
            xhr.send(`${input.name}=${input.value}`);
        });
    };

    const validateForm = async () => {
        let isValid = true;

        // Validate username
        if (username.value.trim() === "") {
            showError(username, "This field cannot be empty.");
            isValid = false;
        } else if (username.value !== originalUsername) {
            const usernameValid = await checkUsernameEmailExists(username);
            if (!usernameValid) isValid = false;
        }

        // Validate password
        const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
        if (password.value.trim() === "") {
            showError(password, "This field cannot be empty.");
            isValid = false;
        } else if (!passwordPattern.test(password.value)) {
            showError(password, "Password must be at least 8 characters long and contain both letters and numbers.");
            isValid = false;
        } else {
            clearError(password);
        }

        // Validate first name
        if (firstname.value.trim() === "") {
            showError(firstname, "This field cannot be empty.");
            isValid = false;
        } else if (/\d/.test(firstname.value)) {
            showError(firstname, "First name cannot contain numbers.");
            isValid = false;
        } else {
            clearError(firstname);
        }

        // Validate last name
        if (lastname.value.trim() === "") {
            showError(lastname, "This field cannot be empty.");
            isValid = false;
        } else if (/\d/.test(lastname.value)) {
            showError(lastname, "Last name cannot contain numbers.");
            isValid = false;
        } else {
            clearError(lastname);
        }

        // Validate email
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email.value.trim() === "") {
            showError(email, "This field cannot be empty.");
            isValid = false;
        } else if (!emailPattern.test(email.value)) {
            showError(email, "Invalid email format.");
            isValid = false;
        } else if (email.value !== originalEmail) {
            const emailValid = await checkUsernameEmailExists(email);
            if (!emailValid) isValid = false;
        }

        // Validate country
        if (country.value === "") {
            showError(country, "This field cannot be empty.");
            isValid = false;
        } else {
            clearError(country);
        }

        console.log(isValid);
        return isValid;
    };

    if(submit_btn) {
        submit_btn.addEventListener("click", async function(event) {
            console.log("Submit button clicked");
            event.preventDefault(); // Prevent form submission
    
            const isValid = await validateForm();
            console.log("Validation result:", isValid);
        
            if (isValid) {
                console.log("Form is valid, submitting now...");
                form.submit(); // Submit the form
            } else {
                console.log("Form is invalid, submission prevented.");
            }
        });
    }

    // Real-time validation for username and email
    [username, email].forEach(input => {
        input.addEventListener("input", function() {
            if (input.value.trim() !== "") {
                checkUsernameEmailExists(input).then(() => {});
            } else {
                showError(input, "This field cannot be empty.");
            }
        });
    });

    // Remove validation styles on input
    const inputs = [username, password, firstname, lastname, email, country];
    inputs.forEach(input => {
        input.addEventListener("input", function() {
            if (input.value.trim() !== "") {
                clearError(input);
            }
        });
    });

    // Clear validation styles and messages on reset
    if(reset_btn) {
        reset_btn.addEventListener("click", function() {
            inputs.forEach(input => {
                clearError(input);
            });
        });
    }
 
    // Edit user function
    window.editUser = function(id, firstName, lastName, usernameValue, emailValue, countryValue, userType) {
        userIdInput.value = id;
        firstname.value = firstName;
        lastname.value = lastName;
        username.value = usernameValue;
        email.value = emailValue;
        country.value = countryValue;
        password.value = ''; // Clear the password field for security reasons
        if(userType == '1') {
            submit_btn.innerText = 'Update Admin';
        }

        // Store original values
        originalUsername = usernameValue;
        originalEmail = emailValue;
    };

    window.saveChanges = async function(usernameValue, emailValue){
        // Store original values
        originalUsername = usernameValue;
        originalEmail = emailValue;

        const isValid = await validateForm();
        console.log("Validation result:", isValid);
    
        if (isValid) {
            console.log("Form is valid, submitting now...");
            form.submit(); // Submit the form
        } else {
            console.log("Form is invalid, submission prevented.");
        }
    }
});
