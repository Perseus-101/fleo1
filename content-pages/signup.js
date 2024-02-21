document.addEventListener("DOMContentLoaded", function () {
    const signUpForm = document.getElementById("signupForm");

    signUpForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent the default form submission

        // Validate form data
        const username = document.getElementById("username").value;
        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;
        const phone = document.getElementById("phone").value;

        // Validate username length
        if (username.length < 4) {
            alert("Username should be at least 4 characters long.");
            return;
        }

        // Validate email format
        if (!validateEmail(email)) {
            alert("Invalid email format.");
            return;
        }

        // Validate password format
        if (!validatePassword(password)) {
            alert("Password should contain both alphabets and numbers.");
            return;
        }

        // Validate phone number format
        if (!validatePhoneNumber(phone)) {
            alert("Phone number should be in the format of 10 digits.");
            return;
        }

        // If all validations pass, submit the form
        signUpForm.submit();
    });

    // Function to validate email format
    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Function to validate password format
    function validatePassword(password) {
        // Password should contain both alphabets and numbers
        const hasAlphabets = /[a-zA-Z]/.test(password);
        const hasNumbers = /\d/.test(password);
        return hasAlphabets && hasNumbers;
    }

    // Function to validate phone number format
    function validatePhoneNumber(phone) {
        // Phone number should be 10 digits
        const phoneRegex = /^\d{10}$/;
        return phoneRegex.test(phone);
    }
});
