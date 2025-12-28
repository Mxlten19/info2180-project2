<?php
// api/contacts.php
session_start();
require_once '../db_conn.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$filter = $_GET['filter'] ?? 'all';
$user_id = $_SESSION['user_id'];

try {
    $sql = "SELECT c.*, CONCAT(u.firstname, ' ', u.lastname) as assigned_name 
            FROM contacts c 
            LEFT JOIN users u ON c.assigned_to = u.id 
            WHERE 1=1";
    $params = [];
    
    switch ($filter) {
        case 'saleslead':
            $sql .= " AND c.type = 'Sales Lead'";
            break;
        case 'support':
            $sql .= " AND c.type = 'Support'";
            break;
        case 'assigntome':
            $sql .= " AND c.assigned_to = ?";
            $params[] = $user_id;
            break;
    }
    
    $sql .= " ORDER BY c.updated_at DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stats = [
        'total' => count($contacts),
        'sales_leads' => 0,
        'support' => 0,
        'assigned_to_me' => 0
    ];
    
    foreach ($contacts as $contact) {
        if ($contact['type'] === 'Sales Lead') $stats['sales_leads']++;
        if ($contact['type'] === 'Support') $stats['support']++;
        if ($contact['assigned_to'] == $user_id) $stats['assigned_to_me']++;
    }
    
    echo json_encode([
        'success' => true,
        'contacts' => $contacts,
        'stats' => $stats
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>