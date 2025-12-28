<?php
include 'db_conn.php';

if (isset($_POST["save"])){
  $pwd = $_POST['password'] ?? '';
  $role = $_POST['roles'] ?? '';

  //removing whitespace
  $newpwd = trim($pwd);

  //setting up password with 8 char, 1 uppercase, 1 letter and 1 number
  if (strlen($newpwd) >= 8 && preg_match('/[A-Z]/',$newpwd) && preg_match('/[a-z]/',$newpwd) 
      && preg_match('/[0-9]/',$newpwd)) {
        $hashed = password_hash($newpwd, PASSWORD_DEFAULT);
  } else {
          echo "Password needs to be atleast 8 charachters long, have 1 uppercase and 1 lowercase letter
            and 1 number";
            exit;
  }

  //validating and sanitizing other input
    $fname = filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_SPECIAL_CHARS);
    $lname = filter_input(INPUT_POST, "lastname", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);

    $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password, role) VALUES (:firstname, :lastname, :email,
                            :password, :role)");
    if (!$stmt){
      die("SQL Error: " . implode(" | ", $conn->errorInfo()));
    }
      $stmt->bindParam(':firstname',$fname);
      $stmt->bindParam(':lastname',$lname);
      $stmt->bindParam(':email',$email);
      $stmt->bindParam(':password',$hashed);
      $stmt->bindParam(':role',$role);

      $stmt->execute();
      echo "User Successfully Added.";
}
?>

<!DOCTYPE html>
<html>
	<head>
      <meta charset="utf-8">
      <title>Create User</title>
  </head>
  <body>
    <?php include 'navigation.php';?>
    <h1>New User</h1>
    <form action="newcontact.php" method="POST">
        <label for="firstname">First Name</label>
        <input id="firstname" type="text" name="firstname" placeholder="First Name">

        <label for="lastname">Last Name</label>
        <input id="lastname" type="text" name="lastname" placeholder="Last Name">

        <label for="email">Email</label>
        <input id="email" type="email" name="email" placeholder="Email">

        <label for="password">Password</label>
        <input id="password" type="password" name="password" placeholder="Password">

        <label for="roles">Role</label>
        <input list="roles" name="roles">
        <datalist id="roles">
            <option value="Admin"></option>
            <option value="Member"></option>
        </datalist>

        <button id="btn" type="submit" name="save">Save</button>
    </form>
  </body>
</html>