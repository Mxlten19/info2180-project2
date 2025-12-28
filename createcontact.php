<?php
include 'db_conn.php';

$res = $conn->query("SELECT * FROM users");
$results = $res->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST["save"])){
    $title = $_POST['title'] ?? '';
    $type = $_POST['type'] ?? '';
    $assigned_to = $_POST['assigned-to'] ?? '';

  //validating and sanitizing other input
    $fname = filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_SPECIAL_CHARS);
    $lname = filter_input(INPUT_POST, "lastname", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $telephone = filter_input(INPUT_POST, "telephone", FILTER_SANITIZE_STRING);
    $company = filter_input(INPUT_POST, "company", FILTER_SANITIZE_SPECIAL_CHARS);

    $stmt = $conn->prepare("INSERT INTO contacts (title, firstname, lastname, email, telephone, company, type, assigned_to) VALUES (:title, :firstname, :lastname, :email, :telephone, :company, :type, :assigned_to)");
    
    if (!$stmt){
        die("SQL Error: " . implode(" | ", $conn->errorInfo()));
    }
        
        $stmt->bindParam(':title',$title);
        $stmt->bindParam(':firstname',$fname);
        $stmt->bindParam(':lastname',$lname);
        $stmt->bindParam(':email',$email);
        $stmt->bindParam(':telephone',$telephone);
        $stmt->bindParam(':company',$company);
        $stmt->bindParam(':type',$type);
        $stmt->bindParam(':assigned_to',$assigned_to);

        $stmt->execute();
        echo "Contact Successfully Added.";


}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Create Contact</title>
    <link href="styles.css" type="text/css" rel="stylesheet" />
	<script src="contact.js" type="text/javascript"></script>
</head>
<body>
    <h2>New Contact</h2>
    <?php include 'navigation.php';?>
    <form action="createcontact.php" method="POST">
        <div>
            <label for="title">Title:</label>
            <select id="title" name="title" required>
                <option value="Mr">Mr</option>
                <option value="Ms">Ms</option>
                <option value="Mrs">Mrs</option>
                <option value="Dr">Dr</option>
                <option value="Prof">Prof</option>
            </select>
        </div>
        <div>
            <label for="firstname">First Name</label>
            <input id="firstname" type="text" name="firstname" placeholder="First Name" required>

            <label for="lastname">Last Name</label>
            <input id="lastname" type="text" name="lastname" placeholder="Last Name" required>
        </div>
        <div>
            <label for="email">Email</label>
            <input id="email" type="email" name="email" placeholder="Email" required>
            <label>Telephone</label>
            <input  type="tel" id="telephone" name="telephone" placeholder="e.g. 876-999-1234" pattern="^\d{3}-\d{3}-\d{4}$" required>
        </div>
        <div>
            <label for="company">Company</label>
            <input id="company" type="text" name="company" placeholder="Company" required>
            <label for="type">Type:</label>
            <select id="type" name="type" required>
                <option value="Sales Lead">Sales Lead</option>
                <option value="Support">Support</option>
            </select>
        </div>
        <div>
            <label for="assigned-to">Assigned To</label>
            <select id="assigned-to" name="assigned-to" required>
                <?php foreach ($results as $row): ?>
                    <option value="<?= $row['id']; ?>"><?= $row['firstname'] . " " . $row['lastname']; ?></option>
                <?php endforeach; ?>
            </select>  
        </div>
        <button id="btn" type="submit" name="save">Save</button>
    </form>
</body>
</html>


