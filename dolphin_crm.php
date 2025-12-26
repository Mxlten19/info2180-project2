<?php
$host = 'localhost';
$username = 'project2_user';
$password = 'wegotthis55';
$dbname = 'dolphin_crm';

$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
?>

<?php 

$password = $_POST['pwd'] ?? '';
$email = $_POST['email'] ?? '';
$hashed = password_hash($password, PASSWORD_DEFAULT);

$checker = $conn->prepare("SELECT password FROM user WHERE email = ?");
$checker->execute ([$email]);
$result = $checker->fetch();

if ($result && password_verify($password, $result['password'])) {
    echo " Login Success";
} else {
    echo " Invalid";
}



?>