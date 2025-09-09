
<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action']) || $_POST['action'] !== 'test_connection') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$host = $_POST['db_host'] ?? 'localhost';
$port = $_POST['db_port'] ?? '3306';
$username = $_POST['db_username'] ?? 'root';
$password = $_POST['db_password'] ?? '';
$dbname = $_POST['db_name'] ?? 'education_portal';

try {
    // Test MySQL connection
    $dsn = "mysql:host=$host;port=$port;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Test database creation/access
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    $pdo->exec("USE `$dbname`");
    
    echo json_encode([
        'success' => true, 
        'message' => "Successfully connected to MySQL server and accessed/created database '$dbname'"
    ]);
    
} catch (PDOException $e) {
    $errorMessage = $e->getMessage();
    
    // Provide helpful error messages
    if (strpos($errorMessage, 'Connection refused') !== false) {
        $errorMessage = "Cannot connect to MySQL server. Make sure XAMPP MySQL service is running.";
    } elseif (strpos($errorMessage, 'Access denied') !== false) {
        $errorMessage = "Access denied. Check your username and password.";
    } elseif (strpos($errorMessage, 'Unknown database') !== false) {
        $errorMessage = "Database access issue. We'll create the database during installation.";
    }
    
    echo json_encode([
        'success' => false, 
        'message' => $errorMessage
    ]);
}
?>
