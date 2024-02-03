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
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$birthdate = $_POST['birthdate'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$regdate = date("Y-m-d");

// Prepare and execute SQL statement
$sql = "INSERT INTO users (username, email, passwordhash, firstname, lastname, birthdate, address, phone, regdate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->execute([$username, $email, $password, $firstname, $lastname, $birthdate, $address, $phone, $regdate]);

// Return JSON response
$response = ["success" => true];
echo json_encode($response);
?>
