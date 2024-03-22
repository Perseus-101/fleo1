<?php
      
      session_start();

      // Check if the user is logged in
      if (!isset($_SESSION['user_id'])) {
          echo "Error: User not logged in";
          exit();
      }

      require_once('../config.php');

      try {

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_ids'])) {
        // Delete selected records
        $delete_ids = $_POST['delete_ids'];
        $placeholders = implode(',', array_fill(0, count($delete_ids), '?'));

        $stmt = $conn->prepare("DELETE FROM financial_record WHERE dataid IN ($placeholders)");
        $stmt->execute($delete_ids);

        echo "Selected records deleted successfully.";
        header("Location: financial-info.php");
    } else {
        header("Location: financial-info.php");
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>
