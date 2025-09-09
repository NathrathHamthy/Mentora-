<?php
require_once __DIR__ . '/../config/database.php';

function getDepartments() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT id, name, code FROM departments ORDER BY name");
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function getModulesByDepartment($department) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT DISTINCT module_name as name FROM uploads WHERE department = ? AND module_name IS NOT NULL ORDER BY module_name");
        $stmt->execute([$department]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function getCategoriesByDepartment($department) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT DISTINCT category as name FROM uploads WHERE department = ? ORDER BY category");
        $stmt->execute([$department]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

function generateFileName($originalName, $userId) {
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    // Sanitize extension to prevent security issues
    $extension = preg_replace('/[^a-zA-Z0-9]/', '', $extension);
    $extension = strtolower($extension);

    // Only allow safe extensions
    $allowedExtensions = ['pdf', 'doc', 'docx', 'txt', 'jpg', 'jpeg', 'png', 'ppt', 'pptx', 'zip', 'rar'];
    if (!in_array($extension, $allowedExtensions)) {
        $extension = 'txt';
    }

    return $userId . '_' . time() . '_' . uniqid() . '.' . $extension;
}

function getUploadsByUser($userId, $limit = null) {
    global $pdo;

    $query = "SELECT * FROM uploads WHERE user_id = ? ORDER BY created_at DESC";
    if ($limit) {
        $query .= " LIMIT " . intval($limit);
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

function getModules($departmentId = null) {
    global $pdo;
    try {
        if ($departmentId) {
            $stmt = $pdo->prepare("SELECT * FROM modules WHERE department_id = ? ORDER BY name");
            $stmt->execute([$departmentId]);
        } else {
            $stmt = $pdo->query("SELECT * FROM modules ORDER BY name");
        }
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function validateFileUpload($file) {
    $allowedExtensions = ['pdf', 'doc', 'docx', 'txt', 'jpg', 'jpeg', 'png', 'ppt', 'pptx', 'zip', 'rar'];
    $maxSize = 50 * 1024 * 1024; // 50MB
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    if ($file['size'] > $maxSize) {
        return false;
    }
    
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    return in_array($extension, $allowedExtensions);
}

function generateSecureFileName($userId, $originalName) {
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    
    // Sanitize extension to prevent security issues
    $extension = preg_replace('/[^a-zA-Z0-9]/', '', $extension);
    
    // Only allow safe extensions
    $allowedExtensions = ['pdf', 'doc', 'docx', 'txt', 'jpg', 'jpeg', 'png', 'ppt', 'pptx', 'zip', 'rar'];
    if (!in_array($extension, $allowedExtensions)) {
        $extension = 'txt';
    }
    
    return $userId . '_' . time() . '_' . uniqid() . '.' . $extension;
}

function authenticateUser($username, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    
    return false;
}

function registerUser($userData) {
    global $pdo;
    
    // Check if username already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$userData['username']]);
    if ($stmt->fetch()) {
        return false; // Username already exists
    }
    
    // Hash password
    $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);
    
    // Insert new user
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, full_name, department) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([
        $userData['username'],
        $hashedPassword,
        $userData['email'],
        $userData['full_name'],
        $userData['department']
    ]);
}

function searchUploads($searchTerm, $filters = []) {
    $filters['search'] = $searchTerm;
    return getApprovedUploads($filters);
}

function addRating($uploadId, $userId, $rating) {
    global $pdo;
    
    $stmt = $pdo->prepare("INSERT INTO ratings (upload_id, user_id, rating) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE rating = VALUES(rating)");
    return $stmt->execute([$uploadId, $userId, $rating]);
}

function getRatings($uploadId) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT AVG(rating) as average_rating, COUNT(*) as total_ratings FROM ratings WHERE upload_id = ?");
    $stmt->execute([$uploadId]);
    return $stmt->fetch();
}

function addComment($uploadId, $userId, $comment) {
    global $pdo;
    
    $stmt = $pdo->prepare("INSERT INTO comments (upload_id, user_id, comment) VALUES (?, ?, ?)");
    return $stmt->execute([$uploadId, $userId, $comment]);
}

function getComments($uploadId) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT c.*, u.full_name FROM comments c JOIN users u ON c.user_id = u.id WHERE c.upload_id = ? ORDER BY c.created_at DESC");
    $stmt->execute([$uploadId]);
    return $stmt->fetchAll();
}

function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function getApprovedUploads($filters = []) {
    global $pdo;

    $where = ["u.approval_status = 'approved'"];
    $params = [];

    if (!empty($filters['department'])) {
        $where[] = "u.department = ?";
        $params[] = $filters['department'];
    }

    if (!empty($filters['module_type'])) {
        $where[] = "u.module_type = ?";
        $params[] = $filters['module_type'];
    }

    if (!empty($filters['category'])) {
        $where[] = "u.category = ?";
        $params[] = $filters['category'];
    }

    if (!empty($filters['search'])) {
        $where[] = "(u.title LIKE ? OR u.description LIKE ?)";
        $params[] = '%' . $filters['search'] . '%';
        $params[] = '%' . $filters['search'] . '%';
    }

    $orderBy = "ORDER BY u.created_at DESC";
    if (!empty($filters['order']) && $filters['order'] === 'oldest') {
        $orderBy = "ORDER BY u.created_at ASC";
    }

    $query = "SELECT u.*, us.full_name as uploader_name 
              FROM uploads u 
              JOIN users us ON u.user_id = us.id 
              WHERE " . implode(' AND ', $where) . " " . $orderBy;

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function validateDownload($uploadId, $userId) {
    global $pdo;
    
    if (!$pdo) {
        return ['success' => false, 'message' => 'Database connection not available'];
    }
    
    try {
        // Get upload details with security validation
        $stmt = $pdo->prepare("
            SELECT u.*, us.full_name as uploader_name 
            FROM uploads u 
            JOIN users us ON u.user_id = us.id 
            WHERE u.id = ? AND u.approval_status = 'approved'
        ");
        $stmt->execute([$uploadId]);
        $upload = $stmt->fetch();
        
        if (!$upload) {
            return ['success' => false, 'message' => 'Material not found or not approved'];
        }
        
        // Get the stored file path
        $storedPath = $upload['file_path'];
        
        // If stored path is absolute and exists, use it
        if (file_exists($storedPath)) {
            $filePath = $storedPath;
        } else {
            // Try alternative paths for backward compatibility
            $projectRoot = dirname(__DIR__);
            $uploadDir = $projectRoot . '/uploads';
            $fileName = basename($storedPath);
            
            $possiblePaths = [
                $uploadDir . '/' . $fileName,
                $projectRoot . '/' . $storedPath,
                $storedPath
            ];
            
            $filePath = null;
            foreach ($possiblePaths as $path) {
                if (file_exists($path)) {
                    $filePath = $path;
                    break;
                }
            }
            
            if (!$filePath) {
                return ['success' => false, 'message' => 'File not found on server'];
            }
        }
        
        // Security: Validate file path is within uploads directory
        $projectRoot = dirname(__DIR__);
        $uploadDir = $projectRoot . '/uploads';
        $realUploadDir = realpath($uploadDir);
        $realFilePath = realpath($filePath);
        
        if (!$realFilePath || !$realUploadDir || strpos($realFilePath, $realUploadDir) !== 0) {
            return ['success' => false, 'message' => 'Access denied'];
        }
        
        // Validate file extension
        $allowedExtensions = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'txt', 'zip', 'rar', 'jpg', 'jpeg', 'png', 'xls', 'xlsx'];
        $fileExtension = strtolower(pathinfo($upload['file_name'], PATHINFO_EXTENSION));
        
        if (!in_array($fileExtension, $allowedExtensions)) {
            return ['success' => false, 'message' => 'File type not allowed'];
        }
        
        return ['success' => true, 'upload' => $upload, 'file_path' => $realFilePath];
        
    } catch (Exception $e) {
        error_log("Download validation error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Validation failed'];
    }
}

function trackDownload($uploadId, $userId) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("INSERT INTO downloads (upload_id, user_id, downloaded_at) VALUES (?, ?, ?)");
        return $stmt->execute([$uploadId, $userId, date('Y-m-d H:i:s')]);
    } catch (Exception $e) {
        error_log("Download tracking error: " . $e->getMessage());
        return false;
    }
}
?>