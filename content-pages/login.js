document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("loginForm");

    loginForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent the default form submission

        // Validate form data
        const username = document.getElementById("username").value;
        const password = document.getElementById("password").value;

        // Validate username and password
        if (!username || !password) {
            alert("Please enter both username and password.");
            return;
        }

        // If all validations pass, submit the form
        loginForm.submit();
    });
});
