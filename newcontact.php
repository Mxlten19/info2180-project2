<?php
session_start();
require_once 'db_conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $pwd = $_POST['password'] ?? '';
    $role = $_POST['roles'] ?? '';
    $fname = filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_SPECIAL_CHARS);
    $lname = filter_input(INPUT_POST, "lastname", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    
    if (empty($fname) || empty($lname) || empty($email) || empty($pwd) || empty($role)) {
        echo json_encode(['success' => false, 'error' => 'All fields are required']);
        exit;
    }
    
    // Validates for the correct email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'error' => 'Invalid email format']);
        exit;
    }
    
    // Validates the password strength
    $passwordRegex = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/';
    if (!preg_match($passwordRegex, $pwd)) {
        echo json_encode([
            'success' => false, 
            'error' => 'Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, and one number'
        ]);
        exit;
    }
    
    // Check if email already exists
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->execute([$email]);
    if ($checkStmt->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Email already exists']);
        exit;
    }
    
    // Hash password
    $hashed = password_hash($pwd, PASSWORD_DEFAULT);
    
    // Insert user
    try {
        $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$fname, $lname, $email, $hashed, $role]);
        
        echo json_encode(['success' => true, 'message' => 'User created successfully']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Create User - Dolphin CRM</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'navigation.php'; ?>
    
    <div style="max-width: 600px; margin: 0 auto;">
        <h1>Create New User</h1>
        <div id="message"></div>
        
        <form id="createUserForm" method="POST" class="ajax-form">
            <div class="form-group">
                <label for="firstname">First Name *</label>
                <input type="text" id="firstname" name="firstname" required 
                       placeholder="Enter first name">
            </div>
            
            <div class="form-group">
                <label for="lastname">Last Name *</label>
                <input type="text" id="lastname" name="lastname" required 
                       placeholder="Enter last name">
            </div>
            
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required 
                       placeholder="Enter email address">
            </div>
            
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" required 
                       placeholder="Enter password">
                <small style="color: #666; display: block; margin-top: 5px;">
                    Must be at least 8 characters with uppercase, lowercase, and number
                </small>
            </div>
            
            <div class="form-group">
                <label for="roles">Role *</label>
                <select id="roles" name="roles" required>
                    <option value="">Select a role</option>
                    <option value="Admin">Admin</option>
                    <option value="Member">Member</option>
                </select>
            </div>
            
            <button type="submit" class="btn-save">Create User</button>
            <button type="button" class="btn-secondary" onclick="window.location.href='dashboard.php'">Cancel</button>
        </form>
    </div>
    
    <script src="assets/js/main.js"></script>
    <script>
    document.getElementById('createUserForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        // Shows the loading
        submitBtn.disabled = true;
        submitBtn.textContent = 'Creating...';
        
        fetch('newcontact.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
            
            const messageDiv = document.getElementById('message');
            if (data.success) {
                messageDiv.innerHTML = `<div class="message success">${data.message}</div>`;
                this.reset();
                
                // Redirects after 2 seconds
                setTimeout(() => {
                    window.location.href = 'users.php';
                }, 2000);
            } else {
                messageDiv.innerHTML = `<div class="message error">${data.error}</div>`;
            }
        })
        .catch(error => {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
            document.getElementById('message').innerHTML = 
                `<div class="message error">Network error: ${error}</div>`;
        });
    });
    </script>
</body>
</html>