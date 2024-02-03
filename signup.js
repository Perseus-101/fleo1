document.getElementById("signupForm").addEventListener("submit", function (event) {
    event.preventDefault();

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

    // Validate username length
    const username = document.getElementById("username").value;
    if (username.length < 4) {
        alert("Username should be at least 4 characters long.");
        return;
    }

    // Validate email format
    const email = document.getElementById("email").value;
    if (!validateEmail(email)) {
        alert("Invalid email format.");
        return;
    }

    // Validate password format
    const password = document.getElementById("password").value;
    if (!validatePassword(password)) {
        alert("Password should contain both alphabets and numbers.");
        return;
    }

    // Validate phone number format
    const phone = document.getElementById("phone").value;
    if (!validatePhoneNumber(phone)) {
        alert("Phone number should be in the format of 10 digits.");
        return;
    }

    // If all validations pass, proceed with form submission
    var formData = new FormData(this);

    fetch("signup.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Signup successful! Redirecting to login page.");
            // Redirect to login.html
            window.location.href = "login.html";
        } else {
            alert("Signup failed. Please try again.");
        }
    })
    .catch(error => console.error("Error:", error));
});
