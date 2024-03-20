<?php

// Configuration
$servername = 'localhost';
$dbname = 'fleo';
$user = 'postgres';
$password = 'percy';
$port = '5432';

// Creating a connection
try {
    $conn = new PDO("pgsql:host=$servername;port=$port;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>