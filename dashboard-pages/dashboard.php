<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Error: User not logged in";
    header("../content-pages/login.html");
    exit();
}

require_once('../config.php');

// Execute the Python script to generate a new graph image
$user_id = $_SESSION['user_id'];
exec('python generate_graph.py ' . $user_id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../global.css">
    <link rel="stylesheet" href="dashboard.css">
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

      <section class="money-info">
          <div class="info-row">
              <div class="info-group">
                  <h5>Your Salary: <?php $salary = $conn->query("SELECT salary FROM users WHERE userid = {$_SESSION['user_id']}")->fetchColumn(); echo number_format($salary, 2); ?></h5>
                  <h5>Savings: <?php $savings = $conn->query("SELECT salary * 0.4 FROM users WHERE userid = {$_SESSION['user_id']}")->fetchColumn(); echo number_format($savings, 2); ?></h5>
              </div>
          </div>
          <div class="info-row">
              <div class="info-group">
                  <h5>Maximum Allowance [Expenditure]: <?php $allowance = $conn->query("SELECT salary * 0.3 FROM users WHERE userid = {$_SESSION['user_id']}")->fetchColumn(); echo number_format($allowance, 2); ?></h5>
                  <h5>Maximum Allowance [Portfolio]: <?php $allowance = $conn->query("SELECT salary * 0.3 FROM users WHERE userid = {$_SESSION['user_id']}")->fetchColumn(); echo number_format($allowance, 2); ?></h5>
              </div>
          </div>
          <div class="info-row">
              <div class="info-group">
                  <h5>Remaining Allowance [Expenditure]: <?php $allowance = $conn->query("SELECT salary_expenditure FROM financial_record WHERE userid = {$_SESSION['user_id']} ORDER BY dataid DESC LIMIT 1")->fetchColumn(); echo number_format($allowance, 2); ?></h5>
                  <h5>Remaining Allowance [Portfolio]: <?php $allowance = $conn->query("SELECT salary_allowance FROM portfolio WHERE userid = {$_SESSION['user_id']} ORDER BY portfolioid DESC LIMIT 1 ")->fetchColumn(); echo number_format($allowance, 2); ?></h5>
              </div>
          </div>
      </section>
    <section class="dashboard">
          <div class="sec1">
            <img id="graph" src="graph.png" alt="Graph">
          </div>
          <div class="sec2">
            <?php
                    $sql = "SELECT description, amount, transaction_date FROM financial_record WHERE userid = ? AND category = 'Subscription' ORDER BY dataid DESC LIMIT 1";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$_SESSION['user_id']]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                    if ($result) {
                        $description = $result['description'];
                        $amount = $result['amount'];
                        $transaction_date = $result['transaction_date'];
                        $next_due_date = date('Y-m-d', strtotime($transaction_date . ' +1 year'));
                        ?>
                        <table class="reminder-table">
                            <tr>
                                <th colspan="3"style="background-color: pink;">Reminder</th>
                            </tr>
                            <tr>
                                <th style="width: 50%;">Description</th>
                                <th style="width: 20%;">Amount Due</th>
                                <th style="width: 30%;">Next Due Date</th>
                            </tr>
                            <tr>
                                <td><?php echo $description; ?></td>
                                <td><?php echo $amount; ?></td>
                                <td><?php echo $next_due_date; ?></td>
                            </tr>
                        </table>
                        <?php
                    } else {
                        echo 'No subscription record found for the logged-in user.';
                    }
            ?>
          </div>
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