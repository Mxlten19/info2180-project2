<!DOCTYPE html>
<html>
	<head>
    <meta charset="utf-8">
		<title>Dashboard</title> 
  </head>
  <body>
      <?php include 'navigation.php';?>

      <h1>DASHBOARD</h1>
      <button id ="add_contact">+Add Contact</button>
      <table>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Company</th>
          <th>Type</th>
        </tr>
        
        <tr>
          <td><?=htmlspecialchars($row['title'])?></td>
          <td><?=htmlspecialchars($row['firstname']) . ($row['lastname'])?></td>
          <td><?=htmlspecialchars($row['email'])?></td>
          <td><?=htmlspecialchars($row['company'])?></td>
          <td><?=htmlspecialchars($row['type'])?></td>
          <td><?=htmlspecialchars($row['name'])?></td>
        </tr>
      </table>
  </body>
</html>