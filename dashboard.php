<?php 
include 'db_conn.php';
$stmt = $conn->query("SELECT * FROM contacts");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
//filter all, support, lead and assigned to me

$filter = $_POST['filter'] ?? 'all';

if ($filter === 'saleslead'){
  $flt = $conn->prepare("SELECT * FROM contacts WHERE type = ?");
  $flt->execute(['Sales Lead']);

} elseif ($filter === 'support'){
  $flt = $conn->prepare("SELECT * FROM contacts WHERE type = ?");
  $flt->execute(['Support']);

} elseif ($filter === 'assigntome'){
  $flt = $conn->prepare("SELECT * FROM contacts WHERE assigned_to = ?");
  $flt->execute([$_SESSION['email']]); //should have the user

} else {
  $flt = $conn->prepare("SELECT * FROM contacts");
  $flt->execute();
}

$results = $flt->fetchAll(PDO::FETCH_ASSOC);
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

      <!-- Filters-->
       <form action="dashboard.php" method="POST">
        <button name="filter" value ="all">All</button>
      </form>
      <form action="dashboard.php" method="POST">
        <button name="filter" value="saleslead">Sales Lead</button>
      </form>
      <form action="dashboard.php" method="POST">
        <button name="filter" value="support">Support</button>
      </form>
      <form action="dashboard.php" method="POST">
        <button name="filter" value="assigntome">Assigned to Me</button>
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