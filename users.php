<?php
include 'db_conn.php';

$em = $_POST['email'] ?? '';

$stmt = $conn->query("SELECT * FROM users");

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
  </body>
</html>

<?php 
?>
    <table>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Created</th>
      </tr>
      <?php foreach ($user as $row): ?>
      <tr>
        <td><?=htmlspecialchars($row['firstname'] . " " . $row['lastname'])?></td>
        <td><?=htmlspecialchars($row['email'])?></td>
        <td><?=htmlspecialchars($row['role'])?></td>
        <td><?=htmlspecialchars($row['created_at'])?></td>
      </tr>
      <?php endforeach; ?>
    </table>
    <?php
?>