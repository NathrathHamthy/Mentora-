
<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Collect installation data
$dbHost = $_POST['db_host'] ?? 'localhost';
$dbPort = $_POST['db_port'] ?? '3306';
$dbName = $_POST['db_name'] ?? 'education_portal';
$dbUsername = $_POST['db_username'] ?? 'root';
$dbPassword = $_POST['db_password'] ?? '';

$adminUsername = $_POST['admin_username'] ?? 'admin';
$adminEmail = $_POST['admin_email'] ?? 'admin@mentora.edu';
$adminPassword = $_POST['admin_password'] ?? '';
$adminPasswordConfirm = $_POST['admin_password_confirm'] ?? '';
$adminFullName = $_POST['admin_fullname'] ?? 'System Administrator';

// Validate inputs
if (empty($adminPassword)) {
    header('Location: index.php?error=' . urlencode('Admin password is required'));
    exit;
}

if ($adminPassword !== $adminPasswordConfirm) {
    header('Location: index.php?error=' . urlencode('Passwords do not match'));
    exit;
}

if (strlen($adminPassword) < 6) {
    header('Location: index.php?error=' . urlencode('Password must be at least 6 characters long'));
    exit;
}

try {
    // Connect to MySQL server
    $dsn = "mysql:host=$dbHost;port=$dbPort;charset=utf8mb4";
    $pdo = new PDO($dsn, $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbName`");

    // Check if tables exist
    $tablesExist = false;
    try {
        $result = $pdo->query("SELECT 1 FROM users LIMIT 1");
        $tablesExist = true;
    } catch (Exception $e) {
        $tablesExist = false;
    }

    // Create database configuration file
    $configContent = "<?php
// Check if application is installed
if (!file_exists(__DIR__ . '/.installed') && !strpos(\$_SERVER['REQUEST_URI'], 'install/')) {
    header('Location: install/index.php');
    exit;
}

// Database configuration for MySQL/XAMPP
\$host = '$dbHost';
\$dbname = '$dbName';
\$username = '$dbUsername';
\$password = '$dbPassword';

try {
    // Connect to the MySQL database
    \$pdo = new PDO(\"mysql:host=\$host;dbname=\$dbname;charset=utf8mb4\", \$username, \$password);
    \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    \$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    \$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
} catch(PDOException \$e) {
    die(\"MySQL connection failed: \" . \$e->getMessage());
}
?>";

    file_put_contents('../config/database.php', $configContent);

    // Initialize database tables if they don't exist
    if (!$tablesExist) {
        $sql = file_get_contents('../config/init_mysql.sql');
        $statements = array_filter(array_map('trim', explode(';', $sql)));

        foreach ($statements as $statement) {
            if (!empty($statement) && !preg_match('/^--/', $statement)) {
                $pdo->exec($statement);
            }
        }
    }

    // Create admin user
    $hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);

    // Remove any existing admin user with same username
    $deleteStmt = $pdo->prepare("DELETE FROM users WHERE username = ? OR is_admin = 1");
    $deleteStmt->execute([$adminUsername]);

    // Create new admin user
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, full_name, department, is_admin, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$adminUsername, $hashedPassword, $adminEmail, $adminFullName, 'Administration', 1]);

    // Create uploads directory structure
    $uploadDirs = [
        '../uploads',
        '../uploads/documents'
    ];

    foreach ($uploadDirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    // Create .htaccess for uploads security
    $htaccessContent = "# Prevent direct access to uploaded files
Options -Indexes
<Files *.php>
    Order Deny,Allow
    Deny from all
</Files>

# Allow common file types
<FilesMatch \"\\.(pdf|doc|docx|xls|xlsx|ppt|pptx|jpg|jpeg|png|gif|zip|txt)$\">
    Order Allow,Deny
    Allow from all
</FilesMatch>";

    file_put_contents('../uploads/.htaccess', $htaccessContent);

    // Create installation lock file
    $installInfo = [
        'installation_date' => date('Y-m-d H:i:s'),
        'database_type' => 'mysql',
        'database_name' => $dbName,
        'admin_username' => $adminUsername,
        'version' => '1.0.0'
    ];
    file_put_contents('../config/.installed', json_encode($installInfo, JSON_PRETTY_PRINT));

    // Success redirect
    $successMessage = "MENTORA has been successfully installed with MySQL database '$dbName'! You can now login with username '$adminUsername'.";
    header('Location: index.php?success=' . urlencode($successMessage));
    exit;

} catch (PDOException $e) {
    $errorMessage = "Database installation failed: " . $e->getMessage();
    
    // Provide helpful error messages
    if (strpos($e->getMessage(), 'Connection refused') !== false) {
        $errorMessage = "Cannot connect to MySQL server. Please make sure XAMPP MySQL service is running.";
    } elseif (strpos($e->getMessage(), 'Access denied') !== false) {
        $errorMessage = "Access denied to MySQL server. Please check your username and password.";
    }
    
    header('Location: index.php?error=' . urlencode($errorMessage));
    exit;
    
} catch (Exception $e) {
    header('Location: index.php?error=' . urlencode('Installation failed: ' . $e->getMessage()));
    exit;
}
?>
