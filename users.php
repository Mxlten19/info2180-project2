<?php
session_start();
require_once 'db_conn.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') {
    header('Location: login.php');
    exit;
}

// Fetch all users
$stmt = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>  
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Users - Dolphin CRM</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'navigation.php'; ?>
    
    <div class="users-container">
        <div class="users-header">
            <h1>Users</h1>
            <button class="btn-primary" onclick="window.location.href='newcontact.php'">
                + Add New User
            </button>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($users) > 0): ?>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td style="font-weight: 500;"><?= htmlspecialchars($user['firstname'] . " " . $user['lastname']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <span class="contact-type <?= strtolower($user['role']) === 'admin' ? 'sales-lead' : 'support' ?>">
                                    <?= htmlspecialchars($user['role']) ?>
                                </span>
                            </td>
                            <td><?= date('M j, Y', strtotime($user['created_at'])) ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="table-btn btn-edit" onclick="editUser(<?= $user['id'] ?>)">Edit</button>
                                    <button class="table-btn btn-delete" onclick="deleteUser(<?= $user['id'] ?>)">Delete</button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="empty-message">
                                No users found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
    function editUser(userId) {
        alert('Edit functionality ' + userId);
        
    
    function deleteUser(userId) {
        if (confirm('Are you sure you want to delete this user?')) {
            alert('Delete functionality ' + userId);
        }
    }
    </script>
</body>
</html>