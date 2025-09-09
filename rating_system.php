
<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $uploadId = intval($input['upload_id'] ?? 0);
    $rating = intval($input['rating'] ?? 0);
    $userId = $_SESSION['user_id'];
    
    if ($uploadId <= 0 || $rating < 1 || $rating > 5) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid rating or upload ID']);
        exit;
    }
    
    try {
        // Check if user already rated this upload
        $stmt = $pdo->prepare("SELECT id FROM ratings WHERE upload_id = ? AND user_id = ?");
        $stmt->execute([$uploadId, $userId]);
        
        if ($stmt->fetch()) {
            // Update existing rating
            $stmt = $pdo->prepare("UPDATE ratings SET rating = ? WHERE upload_id = ? AND user_id = ?");
            $stmt->execute([$rating, $uploadId, $userId]);
        } else {
            // Insert new rating
            $stmt = $pdo->prepare("INSERT INTO ratings (upload_id, user_id, rating) VALUES (?, ?, ?)");
            $stmt->execute([$uploadId, $userId, $rating]);
        }
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>
