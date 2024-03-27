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
    $sql = "SELECT salary_allowance, saving FROM portfolio INNER JOIN users ON portfolio.userid = users.userid WHERE portfolio.userid = ? ORDER BY portfolio.portfolioid DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_SESSION['user_id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $current_salary_allowance = $result['salary_allowance'] ?? 0;
    $saving = $result['saving'] ?? 0;

    // Calculate the new salary_allowance
    $new_salary_allowance = $current_salary_allowance - $purchasevalue;

    if ($new_salary_allowance < 0) {
        $new_salary_allowance = 0;
        $remaining_amount = $purchasevalue - $current_salary_allowance;
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
                // Update the salary_allowance in the financial_record table
                <?php
                $sql = 'INSERT INTO portfolio (userid, assetname, quantity, purchasevalue, currentvalue, salary_allowance) VALUES (?, ?, ?, ?, ?, ?)';
                $stmt = $conn->prepare($sql);
                $stmt->execute([$userid, $assetname, $quantity, $purchasevalue, $currentvalue, $new_salary_allowance]);
                ?>

                // Update the savings in the users table
                <?php
                $update_saving_sql = 'UPDATE users SET saving = ? WHERE userid = ?';
                $update_saving_stmt = $conn->prepare($update_saving_sql);
                $update_saving_stmt->execute([$new_saving, $_SESSION['user_id']]);
                ?>
                alert('Financial record updated successfully.');
                window.location.href = 'portfolio.php';
            } else {
                // User clicked 'Cancel'
                window.location.href = 'portfolio.php';
            }
        </script>
        <?php
    } else {
        // Prepare and execute SQL statement for portfolio table
        $sql = "INSERT INTO portfolio (userid, assetname, quantity, purchasevalue, currentvalue, salary_allowance) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userid, $assetname, $quantity, $purchasevalue, $currentvalue, $new_salary_allowance]);

        echo "Portfolio record inserted successfully.";
        header("Location: portfolio.php");
    }

    
} catch (PDOException $e) {
    // Log or display the error message
    echo "Error: " . $e->getMessage();
}
?>