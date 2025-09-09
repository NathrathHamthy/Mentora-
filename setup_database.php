
<?php
// Database setup script for MySQL
require_once 'database.php';

try {
    // Read and execute the MySQL SQL file
    $sql = file_get_contents(__DIR__ . '/init_mysql.sql');
    
    // Split the SQL into individual statements
    $statements = explode(';', $sql);
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement) && !preg_match('/^--/', $statement)) {
            $pdo->exec($statement);
        }
    }
    
    echo "MySQL Database initialized successfully!\n";
    
    // Verify that tables were created
    $tables = $pdo->query("SHOW TABLES")->fetchAll();
    echo "Created tables: " . implode(', ', array_column($tables, 'Tables_in_education_portal')) . "\n";
    
    // Check if admin user was created
    $adminUser = $pdo->query("SELECT username FROM users WHERE is_admin = 1")->fetch();
    if ($adminUser) {
        echo "Admin user created: " . $adminUser['username'] . "\n";
    }
    
    // Mark as installed
    file_put_contents(__DIR__ . '/.installed', date('Y-m-d H:i:s'));
    
} catch (Exception $e) {
    echo "Error setting up database: " . $e->getMessage() . "\n";
}
?>
