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
    $email = $_POST['email'];
    $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $birthdate = $_POST['birthdate'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $regdate = date("Y-m-d");

    // Prepare and execute SQL statement
    $sql = "INSERT INTO users (username, email, passwordhash, firstname, lastname, birthdate, address, phone, regdate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username, $email, $passwordHash, $firstname, $lastname, $birthdate, $address, $phone, $regdate]);

    // Redirect to login.html after successful registration
    header("Location: login.html");
    exit();
} catch (PDOException $e) {
    // Log or display the error message
    echo "Error: " . $e->getMessage();
}
?>
