<?php
// api/get_contact.php
session_start();
require_once '../db_conn.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$contact_id = $_GET['id'] ?? 0;

try {
    $stmt = $conn->prepare("
        SELECT c.*, 
               CONCAT(creator.firstname, ' ', creator.lastname) as created_by_name,
               CONCAT(assignee.firstname, ' ', assignee.lastname) as assigned_to_name
        FROM contacts c
        LEFT JOIN users creator ON c.created_by = creator.id
        LEFT JOIN users assignee ON c.assigned_to = assignee.id
        WHERE c.id = ?
    ");
    $stmt->execute([$contact_id]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($contact) {
        echo json_encode(['success' => true, 'contact' => $contact]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Contact not found']);
    }
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>