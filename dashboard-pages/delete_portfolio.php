<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Error: User not logged in";
    exit();
}

require_once('../config.php');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_ids'])) {
        // Delete selected records
        $delete_ids = $_POST['delete_ids'];
        $placeholders = implode(',', array_fill(0, count($delete_ids), '?'));
        $stmt = $conn->prepare("SELECT SUM(purchasevalue) AS total_purchase_value FROM portfolio WHERE portfolioid IN ($placeholders)");
        $stmt->execute($delete_ids);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $total_deleted_purchase_value = $result['total_purchase_value'] ?? 0;

        // Retrieve the current salary_allowance for the logged-in user
        $sql = "SELECT salary_allowance FROM portfolio WHERE userid = ? ORDER BY portfolioid DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_SESSION['user_id']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $current_salary_allowance = $result['salary_allowance'] ?? 0;

        // Calculate the new salary_allowance
        $new_salary_allowance = $current_salary_allowance + $total_deleted_purchase_value;

        // Delete the selected records
        $stmt = $conn->prepare("DELETE FROM portfolio WHERE portfolioid IN ($placeholders)");
        $stmt->execute($delete_ids);

        // Insert the new salary_allowance
        $stmt = $conn->prepare("INSERT INTO portfolio (userid, salary_allowance) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $new_salary_allowance]);

        echo "Selected records deleted successfully.";
        header("Location: portfolio.php");
    } else {
        header("Location: portfolio.php");
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>