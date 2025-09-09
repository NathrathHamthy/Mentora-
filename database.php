<?php
// Check if application is installed
if (!file_exists(__DIR__ . '/.installed') && !strpos($_SERVER['REQUEST_URI'], 'install/')) {
    header('Location: install/index.php');
    exit;
}

// Database configuration for MySQL/XAMPP
$host = 'localhost';
$dbname = 'education_portal';
$username = 'root';
$password = '';  // Default XAMPP MySQL password is empty

try {
    // First try to connect without database to create it if needed
    $pdo_temp = new PDO("mysql:host=$host", $username, $password);
    $pdo_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $pdo_temp->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo_temp = null;
    
    // Now connect to the specific database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
} catch(PDOException $e) {
    die("MySQL connection failed: " . $e->getMessage());
}
?>
