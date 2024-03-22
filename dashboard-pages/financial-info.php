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
          <h3>Hello, user!</h3>
          <div class="nav-links-dash">
            <ul>
              <li><a href="dashboard.html" class="btn">dashboard</a></li>
              <li><a href="personal-info.php" class="btn">personal info</a></li>
              <li><a href="financial-info.php" class="btn">financial info</a></li>
              <li><a href="portfolio.php" class="btn">portfolio</a></li>
              <li><a href="logout.php" class="logout-btn">log out</a></li>
            </ul>
          </div>
        </nav>
      </section>

    <section class="financial-info">

      <?php
      session_start();
      
      // Check if the user is logged in
      if (!isset($_SESSION['user_id'])) {
          echo "Error: User not logged in";
          exit();
      }
      
      require_once('../config.php');
      
      try {
          // Prepare and execute SQL statement to fetch user's data
          $stmt = $conn->prepare("SELECT dataid, amount, transaction_date, currency, account_type, category, description FROM financial_record WHERE userid = ?");
          $stmt->execute([$_SESSION['user_id']]);
          $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
      } catch (PDOException $e) {
          echo "Error: " . $e->getMessage();
          die();
      }
      ?>      

    <h2>Financial Records</h2><br>
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
        <button class="btn-2" onclick="window.print()">Print to PDF</button>
      </div>
    </form>



    </section>

    <section class="footer">
      <div class="cr">
        <p>©2024 FLEO, INC. All Rights Reserved</p>
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