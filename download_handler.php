
<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Authentication required']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$action = $_GET['action'] ?? '';
$userId = $_SESSION['user_id'];

try {
    switch ($action) {
        case 'history':
            // Get download history for user
            $stmt = $pdo->prepare("
                SELECT d.*, u.title, u.file_name, u.file_size, us.full_name as uploader_name
                FROM downloads d
                JOIN uploads u ON d.upload_id = u.id
                JOIN users us ON u.user_id = us.id
                WHERE d.user_id = ?
                ORDER BY d.downloaded_at DESC
                LIMIT 50
            ");
            $stmt->execute([$userId]);
            $downloads = $stmt->fetchAll();
            
            echo json_encode([
                'success' => true,
                'downloads' => $downloads
            ]);
            break;
            
        case 'stats':
            // Get download statistics
            $stmt = $pdo->prepare("
                SELECT 
                    COUNT(*) as total_downloads,
                    COUNT(DISTINCT upload_id) as unique_materials,
                    DATE(downloaded_at) as download_date,
                    COUNT(*) as daily_count
                FROM downloads 
                WHERE user_id = ? AND downloaded_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY DATE(downloaded_at)
                ORDER BY download_date DESC
            ");
            $stmt->execute([$userId]);
            $stats = $stmt->fetchAll();
            
            // Get total stats
            $totalStmt = $pdo->prepare("
                SELECT 
                    COUNT(*) as total_downloads,
                    COUNT(DISTINCT upload_id) as unique_materials
                FROM downloads 
                WHERE user_id = ?
            ");
            $totalStmt->execute([$userId]);
            $totals = $totalStmt->fetch();
            
            echo json_encode([
                'success' => true,
                'totals' => $totals,
                'daily_stats' => $stats
            ]);
            break;
            
        case 'popular':
            // Get most popular downloads
            $stmt = $pdo->prepare("
                SELECT u.*, COUNT(d.id) as download_count, us.full_name as uploader_name
                FROM uploads u
                LEFT JOIN downloads d ON u.id = d.upload_id
                JOIN users us ON u.user_id = us.id
                WHERE u.approval_status = 'approved'
                GROUP BY u.id
                ORDER BY download_count DESC, u.created_at DESC
                LIMIT 10
            ");
            $stmt->execute();
            $popular = $stmt->fetchAll();
            
            echo json_encode([
                'success' => true,
                'popular_materials' => $popular
            ]);
            break;
            
        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            break;
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
