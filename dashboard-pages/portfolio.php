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
    <title>Personal Info</title>
    <link rel="stylesheet" href="../global.css">
    <link rel="stylesheet" href="portfolio.css">
    <link rel="stylesheet" href="../fonts/stylesheet.css">
    <script src="portfolio.js"></script>
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

    <section class="portfolio">
     
      <?php
      
      try {
          // Prepare and execute SQL statement to fetch user's data
          $stmt = $conn->prepare("SELECT portfolioid, assetname, quantity, purchasevalue, currentvalue FROM portfolio WHERE userid = ? AND purchasevalue > 0 AND purchasevalue IS NOT NULL");
          $stmt->execute([$_SESSION['user_id']]);
          $portfolio = $stmt->fetchAll(PDO::FETCH_ASSOC);

          $stmt2 = $conn->prepare("SELECT salary_allowance FROM portfolio WHERE userid = ? ORDER BY portfolioid DESC LIMIT 1");
          $stmt2->execute([$_SESSION['user_id']]);
          $results = $stmt2->fetch(PDO::FETCH_ASSOC);

          $stmt3 = $conn->prepare("SELECT saving FROM users WHERE userid = ? LIMIT 1");
          $stmt3->execute([$_SESSION['user_id']]);
          $results2 = $stmt3->fetch(PDO::FETCH_ASSOC);

          // Check if a row was found
          if ($results) {
              $maximum_allowance = $results['salary_allowance'];
              $savings = $results2['saving'];
              echo '<h2><u>Portfolio</u></h2>';
              echo '<h3>Your maximum allowance is $' . $maximum_allowance . ' and your saving is $' . $savings . '</h3>';
          } else {
              echo 'No data found for the user.';
          }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                die();
            }
            ?>   
    <form action="delete_portfolio.php" method="post">
      <table class="portfolio-table">
          <tr>
              <th style="width: 10%;">Portfolio ID</th>
              <th style="width: 30%;">Asset Name</th>
              <th style="width: 10%;">Quantity</th>
              <th style="width: 15%;">Purchase Value</th>
              <th style="width: 15%;">Current Value</th>
              <th style="width: 10%;">Delete</th>
          </tr>
          <?php foreach ($portfolio as $item): ?>
          <tr>
              <td><?php echo $item['portfolioid']; ?></td>
              <td><?php echo $item['assetname']; ?></td>
              <td><?php echo $item['quantity']; ?></td>
              <td><?php echo $item['purchasevalue']; ?></td>
              <td><?php echo $item['currentvalue']; ?></td>
              <td><input type="checkbox" name="delete_ids[]" value="<?php echo $item['portfolioid']; ?>"></td>
          </tr>
          <?php endforeach; ?>
      </table>
      <div class="btn-1">
        <a class="btn-2" href="update-portfolio.html">update</a>
        <button class="btn-2" type="submit">delete</button>
        <button class="btn-2" onclick="window.print()">Print to PDF</button>
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