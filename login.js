document.getElementById("loginForm").addEventListener("submit", function (event) {
    event.preventDefault();

    // Function to validate username length
    function validateUsername(username) {
        return username.length >= 4;
    }

    // Function to validate password format
    function validatePassword(password) {
        // You can add more password validation rules if needed
        return password.length >= 6; // For example, requiring a minimum length
    }

    // Validate username length
    const username = document.getElementById("username").value;
    if (!validateUsername(username)) {
        alert("Username should be at least 4 characters long.");
        return;
    }

    // Validate password format
    const password = document.getElementById("password").value;
    if (!validatePassword(password)) {
        alert("Password should be at least 6 characters long.");
        return;
    }

    // If all validations pass, proceed with form submission
    var formData = new FormData(this);

    fetch("login.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Login successful!");
            // You can redirect the user or perform other actions here
        } else {
            alert("Login failed. Please check your username and password.");
        }
    })
    .catch(error => console.error("Error:", error));
});
