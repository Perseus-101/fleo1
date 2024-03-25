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
    $salary = $_POST['salary'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $regdate = date("Y-m-d");

    // Prepare and execute SQL statement
    $sql = "INSERT INTO users (username, email, password, firstname, lastname, birthdate, salary, address, phone, regdate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username, $email, $password, $firstname, $lastname, $birthdate, $salary, $address, $phone, $regdate]);

    // Get the last inserted user ID
    $userid = $conn->lastInsertId();

    // Calculate 30% of the salary
    $salary_expenditure = round($salary * 0.3);
    $salary_allowance = $salary_expenditure;

    // Insert data into the financial_record table
    $stmt = $conn->prepare("INSERT INTO financial_record (userid, salary_expenditure) VALUES (?, ?)");
    $stmt->execute([$userid, $salary_expenditure]);

    // Insert data into the portfolio table
    $stmt = $conn->prepare("INSERT INTO portfolio (userid, salary_allowance) VALUES (?, ?)");
    $stmt->execute([$userid, $salary_allowance]);

    // Redirect to login.html after successful registration
    header("Location: login.html");
    exit();
} catch (PDOException $e) {
    // Log or display the error message
    echo "Error: " . $e->getMessage();
}
?>
