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

    // Retrieve the current salary_expenditure and savings for the logged-in user
    $sql = "SELECT salary_expenditure, saving FROM financial_record INNER JOIN users ON financial_record.userid = users.userid WHERE financial_record.userid = ? ORDER BY financial_record.dataid DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_SESSION['user_id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $current_salary_expenditure = $result['salary_expenditure'] ?? 0;
    $saving = $result['saving'] ?? 0;

    // Calculate the new salary_expenditure and update savings if necessary
    $new_salary_expenditure = $current_salary_expenditure - $amount;

    if ($new_salary_expenditure < 0) {
        $new_salary_expenditure = 0;
        $remaining_amount = $amount - $current_salary_expenditure;
        $new_saving = $saving - $remaining_amount;

        if ($new_saving <= 0) {
            echo "Error: Not enough savings to deduct the remaining amount. Redirecting in 3 seconds....";
            echo "<meta http-equiv='refresh' content='3;url=dashboard.php'>";
            exit();
        }

        $message = addslashes("Your Maximum Allowance has gone into negative. The remaining amount is $" . number_format($remaining_amount, 2) . ". Do you want to deduct this amount from your savings?");
        ?>
        <script>
            var confirmResult = confirm('<?php echo $message; ?>');
            if (confirmResult) {
                // User clicked 'OK'
                // Update the salary_expenditure in the financial_record table
                <?php
                $sql = 'INSERT INTO financial_record (userid, amount, transaction_date, currency, account_type, category, description, salary_expenditure) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
                $stmt = $conn->prepare($sql);
                $stmt->execute([$_SESSION['user_id'], $amount, $transaction_date, $currency, $account_type, $category, $description, $new_salary_expenditure]);
                ?>

                // Update the savings in the users table
                <?php
                $update_saving_sql = 'UPDATE users SET saving = ? WHERE userid = ?';
                $update_saving_stmt = $conn->prepare($update_saving_sql);
                $update_saving_stmt->execute([$new_saving, $_SESSION['user_id']]);
                ?>
                alert('Financial record updated successfully.');
                window.location.href = 'financial-info.php';
            } else {
                // User clicked 'Cancel'
                window.location.href = 'financial-info.php';
            }
        </script>
        <?php
    } else {
        // Update the salary_expenditure in the financial_record table
        $sql = "INSERT INTO financial_record (userid, amount, transaction_date, currency, account_type, category, description, salary_expenditure) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_SESSION['user_id'], $amount, $transaction_date, $currency, $account_type, $category, $description, $new_salary_expenditure]);
        echo "Financial record inserted successfully.";
        header("Location: financial-info.php");
    }
} catch (PDOException $e) {
    // Log or display the error message
    echo "Error: " . $e->getMessage();
}
?>