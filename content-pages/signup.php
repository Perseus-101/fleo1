<?php

require_once('../config.php');

try {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $birthdate = $_POST['birthdate'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $regdate = date("Y-m-d");

    // Prepare and execute SQL statement
    $sql = "INSERT INTO users (username, email, password, firstname, lastname, birthdate, address, phone, regdate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username, $email, $password, $firstname, $lastname, $birthdate, $address, $phone, $regdate]);

    // Redirect to login.html after successful registration
    header("Location: login.html");
    exit();
} catch (PDOException $e) {
    // Log or display the error message
    echo "Error: " . $e->getMessage();
}
?>
