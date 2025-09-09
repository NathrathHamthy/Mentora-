<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$query = $_GET['query'] ?? '';
$department = $_GET['department'] ?? '';
$category = $_GET['category'] ?? '';
$page = intval($_GET['page'] ?? 1);
$limit = 10;
$offset = ($page - 1) * $limit;

try {
    $filters = [];
    if ($query) $filters['search'] = $query;
    if ($department) $filters['department'] = $department;
    if ($category) $filters['category'] = $category;

    $materials = getApprovedUploads($filters);

    // Apply pagination
    $total = count($materials);
    $materials = array_slice($materials, $offset, $limit);

    echo json_encode([
        'materials' => $materials,
        'total' => $total,
        'page' => $page,
        'totalPages' => ceil($total / $limit)
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Search failed']);
}
?>