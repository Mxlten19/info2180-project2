<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Details - Dolphin CRM</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .note-form-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            margin-top: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    <?php include 'navigation.php'; ?>
    
    <div class="contact-view-container">
        <?php foreach ($user as $row): ?>
        <!-- Contact Header -->
        <div class="contact-header">
            <div class="contact-title">
                <h2><?= htmlspecialchars($row['title'] . " " . $row['firstname'] . " " . $row['lastname']) ?></h2>
                <div class="contact-meta">
                    <p>Created on <?= date('F j, Y', strtotime($row['created_at'])) ?> by Admin</p>
                    <p>Updated on <?= date('F j, Y', strtotime($row['updated_at'])) ?></p>
                </div>
            </div>
            <div class="contact-actions-header">
                <button class="btn-success" id="assign-me" onclick="assignToMe(<?= $row['id'] ?>)">Assign to me</button>
                <button class="btn-secondary" id="switch" onclick="switchType(<?= $row['id'] ?>, '<?= $row['type'] ?>')">
                    Switch to <?= $row['type'] === 'Sales Lead' ? 'Support' : 'Sales Lead' ?>
                </button>
            </div>
        </div>
        
        <!-- Contact Details -->
        <div class="contact-details-grid">
            <div class="detail-item">
                <h3>Email</h3>
                <p><?= htmlspecialchars($row['email']) ?></p>
            </div>
            <div class="detail-item">
                <h3>Telephone</h3>
                <p><?= htmlspecialchars($row['telephone']) ?></p>
            </div>
            <div class="detail-item">
                <h3>Company</h3>
                <p><?= htmlspecialchars($row['company']) ?></p>
            </div>
            <div class="detail-item">
                <h3>Assigned To</h3>
                <p id="assigned-to">User #<?= htmlspecialchars($row['assigned_to']) ?></p>
            </div>
            <div class="detail-item">
                <h3>Type</h3>
                <p id="contact-type" class="contact-type <?= strtolower(str_replace(' ', '-', $row['type'])) ?>">
                    <?= htmlspecialchars($row['type']) ?>
                </p>
            </div>
            <div class="detail-item">
                <h3>Created By</h3>
                <p>User #<?= htmlspecialchars($row['created_by']) ?></p>
            </div>
        </div>
        
        <!-- Notes Section -->
        <div class="notes-section">
            <h3>Notes</h3>
            
            <?php if (count($notes) > 0): ?>
                <?php foreach ($notes as $note): ?>
                <div class="note">
                    <div class="note-author">User #<?= htmlspecialchars($note['created_by']) ?></div>
                    <div class="note-date"><?= date('F j, Y \a\t g:i A', strtotime($note['created_at'])) ?></div>
                    <div class="note-content"><?= nl2br(htmlspecialchars($note['comment'])) ?></div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-message">No notes yet for this contact</div>
            <?php endif; ?>
            
            <!-- Add Note Form -->
            <div class="note-form-container">
                <h3 style="margin-top: 0;">Add a note about <?= htmlspecialchars($row['firstname']) ?></h3>
                <form action="addnote.php" method="POST" id="addNoteForm">
                    <input type="hidden" name="contact_id" value="<?= htmlspecialchars($row['id']) ?>">
                    <div class="form-group">
                        <textarea id="comment" name="comment" placeholder="Enter note details here..." required></textarea>
                    </div>
                    <div class="button-group">
                        <button type="submit" class="btn-submit" name="add">Add Note</button>
                    </div>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <script>
    function assignToMe(contactId) {
        if (confirm('Assign this contact to you?')) {
            fetch('api/assign_contact.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `contact_id=${contactId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Contact assigned to you!');
                    document.getElementById('assigned-to').textContent = 'You';
                    document.getElementById('assign-me').style.display = '