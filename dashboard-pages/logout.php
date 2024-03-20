<?php
session_start();

require_once('../config.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: ../content-pages/login.html");
    exit();
}

// Destroy the session to log out the user
session_destroy();

// Redirect to login page after logout
header("Location: ../content-pages/login.html");
exit();
?>
