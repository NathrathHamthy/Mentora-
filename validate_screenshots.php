
<?php
/**
 * MENTORA Screenshot Validation Script
 * Checks for missing screenshots and broken links
 */

echo "🔍 MENTORA Screenshot Validation\n";
echo "================================\n\n";

$base_dir = __DIR__;
$missing_files = [];
$found_files = [];

// Define expected screenshots
$expected_screenshots = [
    // Test Cases
    'test-cases/tc001-admin-login-success.png',
    'test-cases/tc002-student-login-success.png', 
    'test-cases/tc003-invalid-login-error.png',
    'test-cases/tc004-registration-success.png',
    'test-cases/tc005-duplicate-username-error.png',
    'test-cases/tc006-file-upload-success.png',
    'test-cases/tc007-invalid-file-type-error.png',
    'test-cases/tc008-file-size-limit-error.png',
    'test-cases/tc009-search-success.png',
    'test-cases/tc010-department-filter-success.png',
    'test-cases/tc011-admin-dashboard-access.png',
    'test-cases/tc012-upload-approval-success.png',
    'test-cases/tc013-upload-rejection-success.png',
    'test-cases/tc014-sql-injection-prevention.png',
    'test-cases/tc015-xss-prevention.png',
    'test-cases/tc016-installation-success.png',
    'test-cases/tc017-invalid-db-config-error.png',
    
    // Features
    'features/homepage-overview.png',
    'features/navigation-desktop.png',
    'features/login-interface.png',
    'features/registration-form.png',
    'features/upload-form.png',
    'features/department-browse.png',
    'features/search-interface.png',
    'features/user-dashboard.png',
    
    // Admin
    'admin/admin-dashboard.png',
    'admin/user-management.png',
    'admin/content-management.png',
    'admin/statistics-panel.png',
    
    // UI Components
    'ui-components/glass-morphism-cards.png',
    'ui-components/buttons-interactive.png',
    'ui-components/forms-styling.png',
    'ui-components/notifications.png',
    
    // Mobile
    'mobile/mobile-homepage.png',
    'mobile/mobile-navigation.png',
    'mobile/mobile-upload.png',
    'mobile/mobile-admin.png',
    
    // Installation
    'installation/installation-form.png',
    'installation/database-config.png',
    'installation/installation-success.png'
];

// Check each expected file
foreach ($expected_screenshots as $file) {
    $full_path = $base_dir . '/' . $file;
    if (file_exists($full_path)) {
        $found_files[] = $file;
        $size = filesize($full_path);
        echo "✅ Found: $file (" . formatBytes($size) . ")\n";
    } else {
        $missing_files[] = $file;
        echo "❌ Missing: $file\n";
    }
}

echo "\n📊 Summary\n";
echo "==========\n";
echo "Total expected: " . count($expected_screenshots) . "\n";
echo "Found: " . count($found_files) . "\n";
echo "Missing: " . count($missing_files) . "\n";
echo "Completion: " . round((count($found_files) / count($expected_screenshots)) * 100, 1) . "%\n\n";

if (!empty($missing_files)) {
    echo "🚨 Missing Screenshots:\n";
    foreach ($missing_files as $file) {
        echo "   - $file\n";
    }
    echo "\n💡 To create missing screenshots:\n";
    echo "   1. Follow the SCREENSHOT_GUIDE.md instructions\n";
    echo "   2. Run: php generate_placeholders.php (for placeholders)\n";
    echo "   3. Replace placeholders with actual screenshots\n";
}

function formatBytes($size, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB');
    for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
        $size /= 1024;
    }
    return round($size, $precision) . ' ' . $units[$i];
}

echo "\n✨ Validation complete!\n";
?>
