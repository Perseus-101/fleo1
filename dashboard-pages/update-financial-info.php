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

    // Retrieve the current salary_expenditure for the logged-in user
    $sql = "SELECT salary_expenditure FROM financial_record WHERE userid = ? ORDER BY dataid DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_SESSION['user_id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $current_salary_expenditure = $result['salary_expenditure'] ?? 0; // Use 0 if salary_expenditure is null

    // Calculate the new salary_expenditure
    $new_salary_expenditure = $current_salary_expenditure - $amount;

    // Update the salary_expenditure in the financial_record table
    $sql = "INSERT INTO financial_record (userid, amount, transaction_date, currency, account_type, category, description, salary_expenditure) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_SESSION['user_id'], $amount, $transaction_date, $currency, $account_type, $category, $description, $new_salary_expenditure]);

    echo "Financial record inserted successfully.";
    header("Location: financial-info.php");
} catch (PDOException $e) {
    // Log or display the error message
    echo "Error: " . $e->getMessage();
}
?>
