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
  </head>
  <body>
    <?php include 'navigation.php';?>
    <?php foreach ($user as $row): ?>
    <div>
        <h2><?=htmlspecialchars($row['title'] . " " . $row['firstname'] . " " . $row['lastname'])?></h2>
        <p>Created on <?=htmlspecialchars($row['created_at'])?> by add this later</p>
        <p>Updated on <?=htmlspecialchars($row['updated_at'])?></p>
    </div>
    <div>
        <h3>Email</h3>
        <p><?=htmlspecialchars($row['email'])?></p>
        <h3>Telephone</h3>
        <p><?=htmlspecialchars($row['telephone'])?></p>
        <h3>Company</h3>
        <p><?=htmlspecialchars($row['company'])?></p>
        <h3>Assigned To</h3>
        <p><?=htmlspecialchars($row['assigned_to'])?></p>
    </div>
    <?php endforeach; ?>
  </body>
</html>