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
    $min_current = $purchasevalue - ($purchasevalue * 0.05);
    $max_current = $purchasevalue + ($purchasevalue * 0.05);
    $currentvalue = mt_rand($min_current * 100, $max_current * 100) / 100;

    // Prepare and execute SQL statement
    $sql = "INSERT INTO portfolio (userid, assetname, quantity, purchasevalue, currentvalue) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$userid, $assetname, $quantity, $purchasevalue, $currentvalue]);

    echo "Portfolio record inserted successfully.";
    header("Location: portfolio.php");
} catch (PDOException $e) {
    // Log or display the error message
    echo "Error: " . $e->getMessage();
}
?>
