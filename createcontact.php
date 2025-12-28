<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Contact - Dolphin CRM</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'navigation.php'; ?>
    
    <div class="form-container">
        <h1>Create New Contact</h1>
        <div id="message"></div>
        
        <form id="createContactForm" method="POST" class="ajax-form">
            <div class="form-row">
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
                
                <div class="form-group">
                    <label for="firstname">First Name *</label>
                    <input type="text" id="firstname" name="firstname" required placeholder="First Name">
                </div>
                
                <div class="form-group">
                    <label for="lastname">Last Name *</label>
                    <input type="text" id="lastname" name="lastname" required placeholder="Last Name">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required placeholder="Email">
                </div>
                
                <div class="form-group">
                    <label for="telephone">Telephone</label>
                    <input type="tel" id="telephone" name="telephone" placeholder="e.g. 876-999-1234" 
                           pattern="^\d{3}-\d{3}-\d{4}$">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="company">Company</label>
                    <input type="text" id="company" name="company" placeholder="Company">
                </div>
                
                <div class="form-group">
                    <label for="type">Type *</label>
                    <select id="type" name="type" required>
                        <option value="">Select type</option>
                        <option value="Sales Lead">Sales Lead</option>
                        <option value="Support">Support</option>
                    </select>
                </div>
                
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
            
            <div class="button-group">
                <button type="submit" class="btn-save">Save Contact</button>
                <button type="button" class="btn-secondary" onclick="window.location.href='dashboard.php'">Cancel</button>
            </div>
        </form>
    </div>
    
    <script src="assets/js/main.js"></script>
</body>
</html>