
<?php
// Reset database utility
$dbPath = '../database/education_portal.db';

if (file_exists($dbPath)) {
    unlink($dbPath);
    echo "Database file deleted successfully.\n";
} else {
    echo "Database file not found.\n";
}

// Remove installation lock
$lockFile = '../config/.installed';
if (file_exists($lockFile)) {
    unlink($lockFile);
    echo "Installation lock removed.\n";
}

echo "Database reset complete. You can now reinstall.\n";
?>
