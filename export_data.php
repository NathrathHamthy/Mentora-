<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

$type = $_GET['type'] ?? '';
$format = $_GET['format'] ?? 'csv';
$userId = $_SESSION['user_id'];

if ($type === 'downloads') {
    // Export download history
    $stmt = $pdo->prepare("
        SELECT d.downloaded_at, u.title, u.file_name, u.department, u.category, 
               u.file_size, us.full_name as uploader_name
        FROM downloads d
        JOIN uploads u ON d.upload_id = u.id
        JOIN users us ON u.user_id = us.id
        WHERE d.user_id = ?
        ORDER BY d.downloaded_at DESC
    ");
    $stmt->execute([$userId]);
    $downloads = $stmt->fetchAll();

    if ($format === 'csv') {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="download_history_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Download Date', 'Title', 'File Name', 'Department', 'Category', 'File Size', 'Uploader']);

        foreach ($downloads as $download) {
            fputcsv($output, [
                $download['downloaded_at'],
                $download['title'],
                $download['file_name'],
                $download['department'],
                $download['category'],
                formatFileSize($download['file_size']),
                $download['uploader_name']
            ]);
        }
        fclose($output);
    } else {
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="download_history_' . date('Y-m-d') . '.json"');
        echo json_encode($downloads, JSON_PRETTY_PRINT);
    }
    exit;
}
?>