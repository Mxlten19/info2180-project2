<?php
// api/assign_contact.php
session_start();
require_once '../db_conn.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$contact_id = $_POST['contact_id'] ?? 0;

try {
    $stmt = $conn->prepare("UPDATE contacts SET assigned_to = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$_SESSION['user_id'], $contact_id]);
    
    echo json_encode(['success' => true, 'message' => 'Contact assigned successfully']);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>