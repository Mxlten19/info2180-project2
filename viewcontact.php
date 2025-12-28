<?php
include 'db_conn.php';

if (isset($_POST["view"])){
    $contact = $_POST['contact_id'] ?? '';
}

$stmt = $conn->query("SELECT * FROM contacts WHERE id = $contact");
$user = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->query("SELECT * FROM notes WHERE contact_id = $contact");
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
	<head>
      <meta charset="utf-8">
      <title>Contacts</title>
      <link rel="stylesheet" type="text/css" href="viewcontact.css" >
  </head>
  <body>
    <?php include 'navigation.php';?>
    <?php foreach ($user as $row): ?>
    <header>
        <div>
            <img src="">
            <h2><?=htmlspecialchars($row['title'] . " " . $row['firstname'] . " " . $row['lastname'])?></h2>
            <p>Created on <?=htmlspecialchars($row['created_at'])?> by add this later</p>
            <p>Updated on <?=htmlspecialchars($row['updated_at'])?></p>
        </div>
        <div>
            <button id="assign-me">Assign to me</button>
            <button id="switch">Switch to </button>
        </div>
    </header>
    <section id="details">
        <div>
            <h3>Email</h3>
            <p><?=htmlspecialchars($row['email'])?></p>
        </div>
        <div>
            <h3>Telephone</h3>
            <p><?=htmlspecialchars($row['telephone'])?></p>
        </div>
        <div>
            <h3>Company</h3>
            <p><?=htmlspecialchars($row['company'])?></p>
        </div>
        <div>
            <h3>Assigned To</h3>
            <p><?=htmlspecialchars($row['assigned_to'])?></p>
        </div>
    </section>
    <section>
        <h3>Notes</h3>
        <?php foreach ($notes as $note): ?>
        <h3><?=htmlspecialchars($note['created_by'])?></h3>
        <p><?=htmlspecialchars($note['comment'])?></p>
        <h4><?=htmlspecialchars($note['created_at'])?></h4>
        <?php endforeach; ?>
        <form action="addnote.php" method="POST">
            <input type="hidden" name="contact_id" value="<?= htmlspecialchars($row['id']) ?>">
            <label for="notes">Add a note about <?=htmlspecialchars($row['firstname'])?></label>
            <textarea id="comment" name="comment" placeholder="Enter details here"></textarea>
            <button id="add" type="submit" name="add">Add note</button>
        </form>
    </section>
    <?php endforeach; ?>

  </body>
</html>