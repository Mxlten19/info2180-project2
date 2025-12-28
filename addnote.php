<?php
include 'db_conn.php';

if (isset($_POST["add"])){
    $contact = $_POST['contact_id'] ?? '';
    $comment = $_POST['comment'] ?? '';

    $comment = filter_input(INPUT_POST, "comment", FILTER_SANITIZE_SPECIAL_CHARS);

    $stmt = $conn->prepare("INSERT INTO notes (contact_id, comment, created_by) VALUES (:contact_id, :comment, :created_by)");
    $stmt->bindParam(':created_by', $_SESSION['user_id']);

    if (!$stmt){
      die("SQL Error: " . implode(" | ", $conn->errorInfo()));
    }
      $stmt->bindParam(':comment', $comment);
      $stmt->bindParam(':contact_id', $contact);

      $stmt->execute();
      echo "Note Successfully Added.";

}

?>