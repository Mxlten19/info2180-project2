<?php
include 'db_conn.php';

if (isset($_POST["view"])){
  $contact = $_POST['contact_id'] ?? '';
}

$stmt = $conn->query("SELECT * FROM contacts WHERE id = $contact");

$user = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <section>
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
    
    <?php endforeach; ?>
  </body>
</html>