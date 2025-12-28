<?php
session_start();
require_once 'db_conn.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$res = $conn->query("SELECT * FROM users");
$results = $res->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $title = $_POST['title'] ?? '';
    $type = $_POST['type'] ?? '';
    $assigned_to = $_POST['assigned-to'] ?? '';
    
    $fname = filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_SPECIAL_CHARS);
    $lname = filter_input(INPUT_POST, "lastname", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $telephone = filter_input(INPUT_POST, "telephone", FILTER_SANITIZE_SPECIAL_CHARS);
    $company = filter_input(INPUT_POST, "company", FILTER_SANITIZE_SPECIAL_CHARS);
    
    $errors = [];
    if (empty($fname)) $errors[] = 'First name is required';
    if (empty($lname)) $errors[] = 'Last name is required';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
    
    if (!empty($errors)) {
        echo json_encode(['success' => false, 'error' => implode(', ', $errors)]);
        exit;
    }
    
    try {
        $stmt = $conn->prepare("INSERT INTO contacts (title, firstname, lastname, email, telephone, company, type, assigned_to, created_by) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $fname, $lname, $email, $telephone, $company, $type, $assigned_to, $_SESSION['user_id']]);
        
        echo json_encode(['success' => true, 'message' => 'Contact created successfully', 'redirect' => 'dashboard.php']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Create Contact - Dolphin CRM</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'navigation.php'; ?>
    
    <div style="max-width: 800px; margin: 0 auto;">
        <h1>Create New Contact</h1>
        <div id="message"></div>
        
        <form id="createContactForm" method="POST" class="ajax-form">
            <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                <div style="flex: 1;">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <select id="title" name="title" required>
                            <option value="">Select title</option>
                            <option value="Mr">Mr</option>
                            <option value="Ms">Ms</option>
                            <option value="Mrs">Mrs</option>
                            <option value="Dr">Dr</option>
                            <option value="Prof">Prof</option>
                        </select>
                    </div>
                </div>
                
                <div style="flex: 2;">
                    <div class="form-group">
                        <label for="firstname">First Name *</label>
                        <input type="text" id="firstname" name="firstname" required placeholder="First Name">
                    </div>
                </div>
                
                <div style="flex: 2;">
                    <div class="form-group">
                        <label for="lastname">Last Name *</label>
                        <input type="text" id="lastname" name="lastname" required placeholder="Last Name">
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                <div style="flex: 1;">
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required placeholder="Email">
                    </div>
                </div>
                
                <div style="flex: 1;">
                    <div class="form-group">
                        <label for="telephone">Telephone</label>
                        <input type="tel" id="telephone" name="telephone" placeholder="e.g. 876-999-1234" 
                               pattern="^\d{3}-\d{3}-\d{4}$">
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                <div style="flex: 1;">
                    <div class="form-group">
                        <label for="company">Company</label>
                        <input type="text" id="company" name="company" placeholder="Company">
                    </div>
                </div>
                
                <div style="flex: 1;">
                    <div class="form-group">
                        <label for="type">Type *</label>
                        <select id="type" name="type" required>
                            <option value="">Select type</option>
                            <option value="Sales Lead">Sales Lead</option>
                            <option value="Support">Support</option>
                        </select>
                    </div>
                </div>
                
                <div style="flex: 1;">
                    <div class="form-group">
                        <label for="assigned-to">Assigned To *</label>
                        <select id="assigned-to" name="assigned-to" required>
                            <option value="">Select user</option>
                            <?php foreach ($results as $row): ?>
                                <option value="<?= $row['id']; ?>">
                                    <?= htmlspecialchars($row['firstname'] . " " . $row['lastname']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn-save">Save Contact</button>
            <button type="button" class="btn-secondary" onclick="window.location.href='dashboard.php'">Cancel</button>
        </form>
    </div>
    
    <script src="assets/js/main.js"></script>
</body>
</html>