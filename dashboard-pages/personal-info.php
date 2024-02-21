<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Info</title>
    <link rel="stylesheet" href="global.css">
    <link rel="stylesheet" href="personal-info.css">
    <link rel="stylesheet" href="fonts/stylesheet.css">
</head>
<body>
    <section class="header-dash">
        <nav>
          <h3>Hello, user!</h3>
          <div class="nav-links-dash">
            <ul>
              <li><a href="" class="btn">dashboard</a></li>
              <li><a href="" class="btn">personal info</a></li>
              <li><a href="" class="btn">financial info</a></li>
              <li><a href="" class="btn">portfolio</a></li>
              <li><a href="" class="btn">preferences</a></li>
              <li><a href="" class="logout-btn">log out</a></li>
            </ul>
          </div>
        </nav>
      </section>

    <section class="personal-info">
        

        <?php
      
      session_start();

      // Check if the user is logged in
      if (!isset($_SESSION['user_id'])) {
          echo "Error: User not logged in";
          exit();
      }

      require_once('config.php');

      try {
          // Prepare and execute SQL statement to fetch user's data
          $stmt = $conn->prepare("SELECT firstname, lastname, email, birthdate, phone, address FROM users WHERE userid = ?");
          $stmt->execute([$_SESSION['user_id']]);
          $user = $stmt->fetch(PDO::FETCH_ASSOC);

          // Calculate user's age based on birthdate
          $birthdate = new DateTime($user['birthdate']);
          $today = new DateTime();
          $age = $birthdate->diff($today)->y;

          echo "<div class='user-data'>";
          // Display the user's data
          echo "<h2>Personal Info</h2>";
          echo "<p><span class='label'>First Name:</span> <span class='value'>" . $user['firstname'] . "</span></p>";
          echo "<p><span class='label'>Last Name:</span> <span class='value'>" . $user['lastname'] . "</span></p>";
          echo "<p><span class='label'>Email:</span> <span class='value'>" . $user['email'] . "</span></p>";
          echo "<p><span class='label'>Age:</span> <span class='value'>" . $age . "</span></p>";
          echo "<p><span class='label'>Phone Number:</span> <span class='value'>" . $user['phone'] . "</span></p>";
          echo "<p><span class='label'>Address:</span> <span class='value'>" . $user['address'] . "</span></p>";
          echo "</div>";

      } catch (PDOException $e) {
          // Log or display the detailed error message
          echo "Error: " . $e->getMessage();
      }
      ?>

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
        <a href="#"><img src="images/twitter.png"></a>
        <a href="#"><img src="images/youtube.png"></a>
        <a href="#"><img src="images/facebook.png"></a>
        <a href="#"><img src="images/instagram.png"></a>
      </div>
    </section>

</body>
</html>