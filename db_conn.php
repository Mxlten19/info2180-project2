<?php
$host = 'localhost';
$username = 'project2_user';
$password = 'wegotthis55';
$dbname = 'dolphin_crm';

try{
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connectio Failed" . $e->getMessage());
}
?>
