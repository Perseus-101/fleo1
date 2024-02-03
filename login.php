<?php
$servername = "your_server_name";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create connection
$conn = new PDO("pgsql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get form data
$username = $_POST['username'];
$password = $_POST['password'];

// Prepare and execute SQL statement
$sql = "SELECT * FROM users WHERE username = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the user exists and the password is correct
if ($user && password_verify($password, $user['passwordhash'])) {
    // Return JSON response
    $response = ["success" => true];
} else {
    $response = ["success" => false];
}

echo json_encode($response);
?>
