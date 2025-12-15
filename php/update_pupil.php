<?php
session_start();

require 'connect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header('Location: login.php');
  exit;
}

if ($_SESSION['usertype'] != 'admin') {
  die("403: You are not authorized to access this resource.");
}

if (isset($_POST['id'], $_POST['full_name'], $_POST['birthday'], $_POST['address'])) {
  $pupil_id = $_POST['id'];
  $full_name = trim($_POST['full_name']);
  $birthday = $_POST['birthday'];
  $address = trim($_POST['address']);
  
  $medical_info = !empty(trim($_POST['medical_info'])) ? trim($_POST['medical_info']) : null;

  try {
    $pupil_sql = "UPDATE pupils SET 
    full_name = :full_name, 
    address = :address,
    birthday = :birthday, 
    medical_info = :medical_info 
    WHERE pupil_id = :pupil_id";

    $stmt = $pdo->prepare($pupil_sql);
    $stmt->execute([
      'full_name' => $full_name,
      'address' => $address,
      'birthday' => $birthday,
      'medical_info' => $medical_info,
      'pupil_id' => $pupil_id
    ]);

    echo '<script>window.history.back();</script>';
    exit;
  } catch (PDOException $error) {
    die("Error updating pupil: " . $error->getMessage());
  }
} else {
  echo '<script>window.history.back();</script>';
  exit;
}
?>
