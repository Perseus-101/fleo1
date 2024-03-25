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
              <li><a href="dashboard.html" class="btn">dashboard</a></li>
              <li><a href="personal-info.php" class="btn">personal info</a></li>
              <li><a href="financial-info.php" class="btn">financial info</a></li>
              <li><a href="portfolio.php" class="btn">portfolio</a></li>
              <li><a href="logout.php" class="logout-btn">log out</a></li>
            </ul>
          </div>
        </nav>
      </section>

    <section class="dashboard">
          <div class="sec1">
          <img id="graph" src="graph.png" alt="Graph">
            <p>hello</p>
          </div>
          <div class="sec2">
            <?php

            ?>
            <p>hello</p>
          </div>
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