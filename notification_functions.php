
<?php
function createNotification($userId, $message, $type = 'info') {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, message, type, created_at) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$userId, $message, $type, date('Y-m-d H:i:s')]);
    } catch (Exception $e) {
        return false;
    }
}

function getNotifications($userId, $limit = 10) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?");
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function markNotificationAsRead($notificationId) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?");
        return $stmt->execute([$notificationId]);
    } catch (Exception $e) {
        return false;
    }
}
?>
