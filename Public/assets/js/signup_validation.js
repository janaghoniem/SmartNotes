document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector(".sign-up-form form");
    const username = document.getElementById("username");
    const password = document.getElementById("signup-password");
    const confirmPassword = document.getElementById("confirm-password");
    const firstname = document.getElementById("first_name");
    const lastname = document.getElementById("last_name");
    const email = document.getElementById("signup-email");
    const country = document.getElementById("country");
    const termsCheckbox = document.getElementById("terms-checkbox");
    const signupBtn = document.getElementById("signup-btn");

    let originalUsername = '';
    let originalEmail = '';

    const showError = (input, message) => {
        input.style.borderColor = "red";
        let errorElement = document.getElementById(`${input.id}-error`);
        if (!errorElement) {
            errorElement = document.createElement("p");
            errorElement.id = `${input.id}-error`;
            errorElement.className = "error-message";
            errorElement.style.color = "red";
            input.parentNode.appendChild(errorElement);
        }
        errorElement.textContent = message;
    };

    const clearError = (input) => {
        input.style.borderColor = "";
        const errorElement = document.getElementById(`${input.id}-error`);
        if (errorElement) {
            errorElement.remove();
        }
    };

    const checkUsernameEmailExists = (input) => {
        return new Promise((resolve) => {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "../pages/check_user_email.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        if (xhr.responseText === "exists") {
                            showError(input, `${input.name.charAt(0).toUpperCase() + input.name.slice(1)} already exists.`);
                            resolve(false);
                        } else {
                            clearError(input);
                            resolve(true);
                        }
                    } else {
                        resolve(false);
                    }
                }
            };
            xhr.send(`${input.name}=${input.value}`);
        });
    };

    const validateForm = async () => {
        let isValid = true;

        if (username.value.trim() === "") {
            showError(username, "Username cannot be empty.");
            isValid = false;
        } else if (username.value !== originalUsername) {
            const usernameValid = await checkUsernameEmailExists(username);
            if (!usernameValid) isValid = false;
        }

        const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
        if (password.value.trim() === "") {
            showError(password, "Password cannot be empty.");
            isValid = false;
        } else if (!passwordPattern.test(password.value)) {
            showError(password, "Password must contain at least 8 characters, including letters and numbers.");
            isValid = false;
        } else {
            clearError(password);
        }

        if (confirmPassword.value !== password.value) {
            showError(confirmPassword, "Passwords do not match.");
            isValid = false;
        } else {
            clearError(confirmPassword);
        }

        if (firstname.value.trim() === "") {
            showError(firstname, "First name cannot be empty.");
            isValid = false;
        } else if (/\d/.test(firstname.value)) {
            showError(firstname, "First name cannot contain numbers.");
            isValid = false;
        } else {
            clearError(firstname);
        }

        if (lastname.value.trim() === "") {
            showError(lastname, "Last name cannot be empty.");
            isValid = false;
        } else if (/\d/.test(lastname.value)) {
            showError(lastname, "Last name cannot contain numbers.");
            isValid = false;
        } else {
            clearError(lastname);
        }

        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email.value.trim() === "") {
            showError(email, "Email cannot be empty.");
            isValid = false;
        } else if (!emailPattern.test(email.value)) {
            showError(email, "Invalid email format.");
            isValid = false;
        } else if (email.value !== originalEmail) {
            const emailValid = await checkUsernameEmailExists(email);
            if (!emailValid) isValid = false;
        }

        if (country.value === "") {
            showError(country, "Please select a country.");
            isValid = false;
        } else {
            clearError(country);
        }

        if (!termsCheckbox.checked) {
            if (!document.getElementById("terms-error")) {
                const termsError = document.createElement("p");
                termsError.id = "terms-error";
                termsError.className = "error-message";
                termsError.style.color = "red";
                termsError.textContent = "You must agree to the terms and conditions.";
                termsCheckbox.parentNode.appendChild(termsError);
            }
            isValid = false;
        } else {
            const termsError = document.getElementById("terms-error");
            if (termsError) termsError.remove();
        }

        return isValid;
    };

    signupBtn.addEventListener("click", async function(event) {
        event.preventDefault();
        const isValid = await validateForm();
        if (isValid) {
            form.submit();
        }
    });

    const inputs = [username, password, confirmPassword, firstname, lastname, email, country];
    inputs.forEach(input => {
        input.addEventListener("input", function() {
            if (input.value.trim() !== "") {
                clearError(input);
            }
        });
    });
});
