<?php
include 'db_conn.php';

$em = $_POST['email'] ?? '';

$stmt = $conn->query("SELECT * FROM contacts");

$user = $stmt->fetchAll(PDO::FETCH_ASSOC);

  ?>
    <table>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Company</th>
        <th>Yelephone</th>
        <th>Created</th>
        <th>Updated</th>
        <th>Assigned to</th>
      </tr>
      <?php foreach ($user as $row): ?>
      <tr>
        <td><?=htmlspecialchars($row['title'] . " " . $row['firstname'] . " " . $row['lastname'])?></td>
        <td><?=htmlspecialchars($row['email'])?></td>
        <td><?=htmlspecialchars($row['company'])?></td>
        <td><?=htmlspecialchars($row['telephone'])?></td>
        <td><?=htmlspecialchars($row['created_at'])?></td>
        <!-- Add created-by here -->
        <td><?=htmlspecialchars($row['updated_at'])?></td>
        <td><?=htmlspecialchars($row['assigned_to'])?></td>
      </tr>
      <?php endforeach; ?>
    </table>
    <?php



?>
<!DOCTYPE html>
<html>
	<head>
      <meta charset="utf-8">
      <title>Contacts</title>
  </head>
  <body>
    <?php include 'navigation.php';?>
  </body>
</html>