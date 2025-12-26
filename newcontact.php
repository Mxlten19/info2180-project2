<!DOCTYPE html>
<html>
	<head>
      <meta charset="utf-8">
      <title>New Contact</title>
  </head>
  <body>
    <?php include 'navigation.php';?>
    <form action="">
        <label for="firstname">First Name</label>
        <input type="text" name="firstname" placeholder="Fisrt Name">
        <label for="lastname">Last Name</label>
        <input type="text" name="lastname" placeholder="Last Name">
        <label for="email">Email</label>
        <input type="email" name="email" placeholder="Email">
        <label for="password">Password</label>
        <input type="password" name="password" placeholder="Password">
        <label for="roles">Role</label>
        <input list="roles">
        <datalist id="roles">
            <option value="Admin"></option>
            <option value="Member"></option>
        </datalist>
        <button type="submit">Save</button>
    </form>
  </body>
</html>