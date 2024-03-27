<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Error: User not logged in";
    exit();
}

require_once('../config.php');

if (isset($_POST['confirmed']) && $_POST['confirmed'] === 'true') {
    $remaining_amount = $_POST['remaining_amount'];
    $new_saving = $_POST['new_saving'];
    $table = $_POST['table'];
    $record_id = $_POST['record_id'];

    // Check if the new saving is negative
    if ($new_saving < 0) {
        echo "Insufficient funds. Transaction cannot be completed.";
        exit();
    }

    // Update the saving in the users table
    $sql = "UPDATE users SET saving = ? WHERE userid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$new_saving, $_SESSION['user_id']]);

    if ($table === 'financial_record') {
        // Update the salary_expenditure to the remaining amount
        $sql = "UPDATE financial_record SET salary_expenditure = ? WHERE dataid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$remaining_amount, $record_id]);
        echo "Financial record updated successfully. The remaining amount has been deducted from your savings.";
    } elseif ($table === 'portfolio') {
        // Update the salary_allowance to the remaining amount
        $sql = "UPDATE portfolio SET salary_allowance = ? WHERE portfolioid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$remaining_amount, $record_id]);
        echo "Portfolio record updated successfully. The remaining amount has been deducted from your savings.";
    }
} else {
    // Display the confirmation dialog
    echo "<script>
            var confirmed = confirm('The remaining amount will be deducted from your savings. Do you want to continue?');
            if (confirmed) {
                var form = document.createElement('form');
                form.method = 'post';
                form.action = 'confirm_transaction.php';

                var confirmedInput = document.createElement('input');
                confirmedInput.type = 'hidden';
                confirmedInput.name = 'confirmed';
                confirmedInput.value = 'true';
                form.appendChild(confirmedInput);

                var remainingAmountInput = document.createElement('input');
                remainingAmountInput.type = 'hidden';
                remainingAmountInput.name = 'remaining_amount';
                remainingAmountInput.value = '" . $_POST['remaining_amount'] . "';
                form.appendChild(remainingAmountInput);

                var newSavingInput = document.createElement('input');
                newSavingInput.type = 'hidden';
                newSavingInput.name = 'new_saving';
                newSavingInput.value = '" . $_POST['new_saving'] . "';
                form.appendChild(newSavingInput);

                var tableInput = document.createElement('input');
                tableInput.type = 'hidden';
                tableInput.name = 'table';
                tableInput.value = '" . $_POST['table'] . "';
                form.appendChild(tableInput);

                var recordIdInput = document.createElement('input');
                recordIdInput.type = 'hidden';
                recordIdInput.name = 'record_id';
                recordIdInput.value = '" . $_POST['record_id'] . "';
                form.appendChild(recordIdInput);

                document.body.appendChild(form);
                form.submit();
            } else {
                window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
            }
          </script>";
}
?>