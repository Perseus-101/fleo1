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
        $stmt = $conn->prepare("SELECT SUM(amount) AS total_amount FROM financial_record WHERE dataid IN ($placeholders)");
        $stmt->execute($delete_ids);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $total_deleted_amount = $result['total_amount'] ?? 0;

        // Retrieve the current salary_expenditure for the logged-in user
        $sql = "SELECT salary_expenditure FROM financial_record WHERE userid = ? ORDER BY dataid DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_SESSION['user_id']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $current_salary_expenditure = $result['salary_expenditure'] ?? 0;

        // Calculate the new salary_expenditure
        $new_salary_expenditure = $current_salary_expenditure + $total_deleted_amount;

        // Delete the selected records
        $stmt = $conn->prepare("DELETE FROM financial_record WHERE dataid IN ($placeholders)");
        $stmt->execute($delete_ids);

        // Insert the new salary_expenditure
        $stmt = $conn->prepare("INSERT INTO financial_record (userid, salary_expenditure) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $new_salary_expenditure]);

        echo "Selected records deleted successfully.";
        header("Location: financial-info.php");
    } else {
        header("Location: financial-info.php");
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>