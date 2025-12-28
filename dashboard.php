<?php 
include 'db_conn.php';
$stmt = $conn->query("SELECT * FROM contacts");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
	<head>
    <meta charset="utf-8">
		<title>Dashboard</title> 
  </head>
  <body>
      <?php include 'navigation.php';?>

      <h1>DASHBOARD</h1>
      <form action="createcontact.php" method="POST">
        <button id ="add_contact">+Add Contact</button>
      </form>
      <table>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Company</th>
          <th>Type</th>
        </tr>
        <?php foreach ($results as $row): ?>
        <tr>
          <td><?=htmlspecialchars($row['title'] . ". " . $row['firstname'] . " " . $row['lastname'])?></td>
          <td><?=htmlspecialchars($row['email'])?></td>
          <td><?=htmlspecialchars($row['company'])?></td>
          <td><?=htmlspecialchars($row['type'])?></td>
          <td>
            <form action="viewcontact.php" method="POST">
              <input type="hidden" name="contact_id" value="<?= $row['id']; ?>">
              <button id="view" type="submit" name="view">View</button>
            </form>
          <td>
        </tr>
        <?php endforeach; ?>
      </table>
  </body>
</html>