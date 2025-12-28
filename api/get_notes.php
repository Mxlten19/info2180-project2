<?php
session_start();
require_once '../db_conn.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$contact_id = $_GET['contact_id'] ?? 0;

try {
    $stmt = $conn->prepare("
        SELECT n.*, CONCAT(u.firstname, ' ', u.lastname) as created_by_name
        FROM notes n
        LEFT JOIN users u ON n.created_by = u.id
        WHERE n.contact_id = ?
        ORDER BY n.created_at DESC
    ");
    $stmt->execute([$contact_id]);
    $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'notes' => $notes]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>