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
    $userid = $_SESSION['user_id'];
    $assetname = $_POST['assetname'];
    $quantity = $_POST['quantity'];
    $purchasevalue = $_POST['purchasevalue'];

    // Calculate random current value within +/- 5% of the purchase value
    $min_current = $purchasevalue - ($purchasevalue * 0.5);
    $max_current = $purchasevalue + ($purchasevalue * 0.5);
    $currentvalue = mt_rand($min_current * 100, $max_current * 100) / 100;

    // Retrieve the current salary_allowance for the logged-in user
    $sql = "SELECT salary_allowance FROM portfolio WHERE userid = ? ORDER BY portfolioid DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$userid]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $current_salary_allowance = $result['salary_allowance'] ?? 0; // Use 0 if salary_allowance is null

    // Calculate the new salary_allowance
    $new_salary_allowance = $current_salary_allowance - $purchasevalue;

    // Prepare and execute SQL statement for portfolio table
    $sql = "INSERT INTO portfolio (userid, assetname, quantity, purchasevalue, currentvalue, salary_allowance) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$userid, $assetname, $quantity, $purchasevalue, $currentvalue, $new_salary_allowance]);

    echo "Portfolio record inserted successfully.";
    header("Location: portfolio.php");
} catch (PDOException $e) {
    // Log or display the error message
    echo "Error: " . $e->getMessage();
}
?>