<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create User - Dolphin CRM</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'navigation.php'; ?>
    
    <div class="form-container">
        <h1>Create New User</h1>
        <div id="message"></div>
        
        <form id="createUserForm" method="POST" class="ajax-form">
            <div class="form-row">
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
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required 
                           placeholder="Enter email address">
                </div>
                
                <div class="form-group">
                    <label for="roles">Role *</label>
                    <select id="roles" name="roles" required>
                        <option value="">Select a role</option>
                        <option value="Admin">Admin</option>
                        <option value="Member">Member</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" required 
                       placeholder="Enter password">
                <small style="display: block; margin-top: 8px; color: #666; font-size: 14px;">
                    Must be at least 8 characters with uppercase, lowercase, and number
                </small>
            </div>
            
            <div class="button-group">
                <button type="submit" class="btn-save">Create User</button>
                <button type="button" class="btn-secondary" onclick="window.location.href='dashboard.php'">Cancel</button>
            </div>
        </form>
    </div>
    
    <script src="assets/js/main.js"></script>
</body>
</html>