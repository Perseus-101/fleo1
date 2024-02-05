<?php

require_once('config.php');

$servername = HOST;
$dbname = DB;
$user = USER;
$password = PASSWORD;

try {
    // Create connection
    $conn = new PDO("pgsql:host=$servername;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute SQL statement to fetch user by username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Debug: Check the fetched user data and password verification
    var_dump($user);
    var_dump(password_verify($password, $user['passwordhash']));

    // Check if the user exists and the password is correct
    if ($user && password_verify($password, $user['passwordhash'])) {
        // Start a session and store the user ID
        session_start();
        $_SESSION['user_id'] = $user['userid'];

        // Redirect to dashboard.html after successful login
        header("Location: dashboard.html");
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
