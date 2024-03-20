<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Error: User not logged in";
    exit();
}

require_once('../config.php');

try {
    // Get form data
    $amount = $_POST['amount'];
    $transaction_date = $_POST['transaction_date'];
    $currency = $_POST['currency'];
    $account_type = $_POST['account_type'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    // Prepare and execute SQL statement
    $sql = "INSERT INTO financial_record (userid, amount, transaction_date, currency, account_type, category, description) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_SESSION['user_id'], $amount, $transaction_date, $currency, $account_type, $category, $description]);

    echo "Financial record inserted successfully.";
    // Redirecting back to financial-info.html
    header("Location: financial-info.php");
} catch (PDOException $e) {
    // Log or display the error message
    echo "Error: " . $e->getMessage();
}
?>
