<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Error: User not logged in";
    header("../content-pages/login.html");
    exit();
}

require_once('../config.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Info</title>
    <link rel="stylesheet" href="../global.css">
    <link rel="stylesheet" href="financial-info.css">
    <link rel="stylesheet" href="../fonts/stylesheet.css">
</head>
<body>
    <section class="header-dash">
        <nav>
          <h3>Hello, <?php echo $firstname = $conn->query("SELECT firstname FROM users WHERE userid = {$_SESSION['user_id']}")->fetchColumn(); ?> !</h3>
          <div class="nav-links-dash">
            <ul>
              <li><a href="dashboard.php" class="btn">dashboard</a></li>
              <li><a href="personal-info.php" class="btn">personal info</a></li>
              <li><a href="financial-info.php" class="btn">financial info</a></li>
              <li><a href="portfolio.php" class="btn">portfolio</a></li>
              <li><a href="logout.php" class="logout-btn">log out</a></li>
            </ul>
          </div>
        </nav>
      </section>

    <section id="printable-section" class="financial-info">

      <?php
      try {
          // Prepare and execute SQL statement to fetch user's data
          $stmt = $conn->prepare("SELECT dataid, amount, transaction_date, currency, account_type, category, description FROM financial_record WHERE userid = ? AND amount > 0 AND amount IS NOT NULL");
          $stmt->execute([$_SESSION['user_id']]);
          $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

          $stmt2 = $conn->prepare("SELECT salary_expenditure FROM financial_record WHERE userid = ? ORDER BY dataid DESC LIMIT 1");
          $stmt2->execute([$_SESSION['user_id']]);
          $results = $stmt2->fetch(PDO::FETCH_ASSOC); // Use fetch() instead of fetchAll()

          // Check if a row was found
          if ($results) {
              $maximum_allowance = $results['salary_expenditure'];
              echo '<h2><u>Financial Records</u></h2>';
              echo '<h3>Your maximum allowance is $' . $maximum_allowance . '</h3>';
          } else {
              echo 'No data found for the user.';
          }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                die();
            }
            ?>   
    <form action="delete_records.php" method="post">
      <table class="financial-table">
          <tr>
              <th style="width: 8%;">Data ID</th>
              <th style="width: 12%;">Amount</th>
              <th style="width: 7%;">Currency</th>
              <th style="width: 12%;">Transaction Date</th>
              <th style="width: 13%;">Account Type</th>
              <th style="width: 10%;">Category</th>
              <th style="width: 30%;">Description</th>
              <th style="width: 8%;">Delete</th>
          </tr>
          <?php foreach ($records as $record): ?>
          <tr>
              <td><?php echo $record['dataid']; ?></td>
              <td><?php echo $record['amount']; ?></td>
              <td><?php echo $record['currency']; ?></td>
              <td><?php echo $record['transaction_date']; ?></td>
              <td><?php echo $record['account_type']; ?></td>
              <td><?php echo $record['category']; ?></td>
              <td><?php echo $record['description']; ?></td>
              <td><input type="checkbox" name="delete_ids[]" value="<?php echo $record['dataid']; ?>"></td>
          </tr>
          <?php endforeach; ?>
      </table>
      <div class="btn-1">
        <a class="btn-2" href="update-financial-info.html">update</a>
        <button class="btn-2" type="submit">delete</button>
        <button class="btn-2" onclick="printSection()">Print Section</button>
         <script>
        function printSection() {
            // Get the content of the printable section
            var content = document.getElementById("printable-section").innerHTML;
            
            // Create a new temporary window
            var printWindow = window.open();
            
            // Write the content to the new window
            printWindow.document.write(content);
            
            // Print the content from the new window
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            
            // Close the temporary window (optional)
            // printWindow.close();
        }
    </script>
      </div>
    </form>



    </section>

    <section class="footer">
      <div class="cr">
        <p>&copy;2024 FLEO, INC. All Rights Reserved</p>
      </div>
      <div class="foot">
        <div class="pageguide pg1">
          <a href="">about us</a>
        </div>
        <div class="pageguide pg2">
          <a href="">privacy policy</a>
        </div>
        <div class="pageguide pg3">
          <a href="">terms of use</a>
        </div>
      </div>
      <div class="social">
        <a href="#"><img src="../images/twitter.png"></a>
        <a href="#"><img src="../images/youtube.png"></a>
        <a href="#"><img src="../images/facebook.png"></a>
        <a href="#"><img src="../images/instagram.png"></a>
      </div>
    </section>

</body>
</html>