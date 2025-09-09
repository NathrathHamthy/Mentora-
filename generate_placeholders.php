
<?php
/**
 * MENTORA - Screenshot Placeholder Generator
 * This script creates placeholder images for documentation
 */

// Create placeholder image function
function createPlaceholder($width, $height, $text, $filename) {
    $image = imagecreate($width, $height);
    
    // Colors
    $bg_color = imagecolorallocate($image, 240, 240, 240);
    $text_color = imagecolorallocate($image, 100, 100, 100);
    $border_color = imagecolorallocate($image, 200, 200, 200);
    
    // Fill background
    imagefill($image, 0, 0, $bg_color);
    
    // Add border
    imagerectangle($image, 0, 0, $width-1, $height-1, $border_color);
    
    // Add text
    $font_size = 12;
    $text_x = ($width - strlen($text) * $font_size * 0.6) / 2;
    $text_y = $height / 2;
    
    imagestring($image, 3, $text_x, $text_y - 10, $text, $text_color);
    imagestring($image, 2, $text_x, $text_y + 10, $filename, $text_color);
    
    // Save image
    imagepng($image, $filename);
    imagedestroy($image);
    
    echo "Created placeholder: $filename\n";
}

// Ensure directories exist
$directories = [
    'test-cases',
    'features', 
    'ui-components',
    'admin',
    'installation',
    'mobile',
    'workflow'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Test case screenshots
$test_cases = [
    'tc001-admin-login-success.png' => 'Admin Login Success',
    'tc002-student-login-success.png' => 'Student Login Success', 
    'tc003-invalid-login-error.png' => 'Invalid Login Error',
    'tc004-registration-success.png' => 'Registration Success',
    'tc005-duplicate-username-error.png' => 'Duplicate Username Error',
    'tc006-file-upload-success.png' => 'File Upload Success',
    'tc007-invalid-file-type-error.png' => 'Invalid File Type Error',
    'tc008-file-size-limit-error.png' => 'File Size Limit Error',
    'tc009-search-success.png' => 'Search Success',
    'tc010-department-filter-success.png' => 'Department Filter Success',
    'tc011-admin-dashboard-access.png' => 'Admin Dashboard Access',
    'tc012-upload-approval-success.png' => 'Upload Approval Success',
    'tc013-upload-rejection-success.png' => 'Upload Rejection Success',
    'tc014-sql-injection-prevention.png' => 'SQL Injection Prevention',
    'tc015-xss-prevention.png' => 'XSS Prevention',
    'tc016-installation-success.png' => 'Installation Success',
    'tc017-invalid-db-config-error.png' => 'Invalid DB Config Error'
];

foreach ($test_cases as $filename => $description) {
    createPlaceholder(1200, 800, $description, "test-cases/$filename");
}

// Feature screenshots
$features = [
    'homepage-overview.png' => 'Homepage Overview',
    'navigation-desktop.png' => 'Desktop Navigation',
    'login-interface.png' => 'Login Interface',
    'registration-form.png' => 'Registration Form',
    'upload-form.png' => 'Upload Form',
    'department-browse.png' => 'Department Browse',
    'search-interface.png' => 'Search Interface',
    'user-dashboard.png' => 'User Dashboard'
];

foreach ($features as $filename => $description) {
    createPlaceholder(1200, 800, $description, "features/$filename");
}

// Admin screenshots
$admin = [
    'admin-dashboard.png' => 'Admin Dashboard',
    'user-management.png' => 'User Management',
    'content-management.png' => 'Content Management',
    'statistics-panel.png' => 'Statistics Panel'
];

foreach ($admin as $filename => $description) {
    createPlaceholder(1200, 800, $description, "admin/$filename");
}

// UI Components
$ui_components = [
    'glass-morphism-cards.png' => 'Glass Morphism Cards',
    'buttons-interactive.png' => 'Interactive Buttons',
    'forms-styling.png' => 'Form Styling',
    'notifications.png' => 'Notifications'
];

foreach ($ui_components as $filename => $description) {
    createPlaceholder(800, 600, $description, "ui-components/$filename");
}

// Mobile screenshots
$mobile = [
    'mobile-homepage.png' => 'Mobile Homepage',
    'mobile-navigation.png' => 'Mobile Navigation',
    'mobile-upload.png' => 'Mobile Upload',
    'mobile-admin.png' => 'Mobile Admin'
];

foreach ($mobile as $filename => $description) {
    createPlaceholder(375, 667, $description, "mobile/$filename");
}

// Installation screenshots
$installation = [
    'installation-form.png' => 'Installation Form',
    'database-config.png' => 'Database Configuration',
    'installation-success.png' => 'Installation Success'
];

foreach ($installation as $filename => $description) {
    createPlaceholder(1200, 800, $description, "installation/$filename");
}

echo "\nPlaceholder images generated successfully!\n";
echo "Replace these with actual screenshots following the SCREENSHOT_GUIDE.md\n";
?>
