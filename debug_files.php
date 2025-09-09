
<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAdmin();

echo "<h2>File Path Debug Tool</h2>";

// Check uploads directory
$uploadDir = '../uploads';
echo "<h3>Upload Directory Check</h3>";
echo "Upload directory path: " . realpath($uploadDir) . "<br>";
echo "Directory exists: " . (is_dir($uploadDir) ? 'YES' : 'NO') . "<br>";
echo "Directory writable: " . (is_writable($uploadDir) ? 'YES' : 'NO') . "<br>";

// List actual files
echo "<h3>Files in Upload Directory</h3>";
if (is_dir($uploadDir)) {
    $files = scandir($uploadDir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..' && !is_dir($uploadDir . '/' . $file)) {
            echo "File: $file (" . filesize($uploadDir . '/' . $file) . " bytes)<br>";
        }
    }
}

// Check database entries
echo "<h3>Database Upload Records</h3>";
try {
    $stmt = $pdo->query("SELECT id, title, file_name, file_path, approval_status FROM uploads ORDER BY id DESC LIMIT 10");
    $uploads = $stmt->fetchAll();
    
    foreach ($uploads as $upload) {
        echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>";
        echo "<strong>ID: {$upload['id']} - {$upload['title']}</strong><br>";
        echo "File name: {$upload['file_name']}<br>";
        echo "Stored path: {$upload['file_path']}<br>";
        echo "Status: {$upload['approval_status']}<br>";
        
        // Check if file exists
        $projectRoot = dirname(__DIR__);
        $storedPath = $upload['file_path'];
        $fileName = basename($storedPath);
        
        $possiblePaths = [
            $projectRoot . '/' . $storedPath,
            $projectRoot . '/uploads/' . $fileName,
            $storedPath
        ];
        
        echo "Checking paths:<br>";
        $found = false;
        foreach ($possiblePaths as $path) {
            $exists = file_exists($path);
            echo "- $path: " . ($exists ? 'EXISTS' : 'NOT FOUND') . "<br>";
            if ($exists && !$found) {
                $found = true;
                echo "<span style='color: green;'>✓ File found at: $path</span><br>";
            }
        }
        
        if (!$found) {
            echo "<span style='color: red;'>✗ File not found in any location</span><br>";
        }
        
        echo "</div>";
    }
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage();
}
?>
