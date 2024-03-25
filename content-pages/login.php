<?php

require_once('../config.php');

try {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute SQL statement to fetch user by username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the user exists and the password is correct
    if ($user && $password === $user['password']) {
        // Start a session and store the user ID
        session_start();
        $_SESSION['user_id'] = $user['userid'];

        // Redirect to dashboard.html after successful login
        header("Location: ../dashboard-pages/dashboard.php");
        exit();
    } else {
        // Invalid credentials
        echo "<script>alert('Invalid credentials');</script>";
    }
} catch (PDOException $e) {
    // Log or display the detailed error message
    echo "Error: " . $e->getMessage();
}
?>
