<?php
// api/switch_type.php
session_start();
require_once '../db_conn.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$contact_id = $_POST['contact_id'] ?? 0;
$new_type = $_POST['new_type'] ?? '';

if (!in_array($new_type, ['Sales Lead', 'Support'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid type']);
    exit;
}

try {
    $stmt = $conn->prepare("UPDATE contacts SET type = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$new_type, $contact_id]);
    
    echo json_encode(['success' => true, 'message' => 'Contact type updated']);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>