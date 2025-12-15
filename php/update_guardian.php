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

if (isset($_POST['id'], $_POST['full_name'], $_POST['address'], $_POST['email'], $_POST['phone_number'])) {
  $guardian_id = $_POST['id'];
  $full_name = trim($_POST['full_name']);
  $address = trim($_POST['address']);
  $email = trim($_POST['email']);
  $phone_number = trim($_POST['phone_number']);

  try {
    $guardian_sql = "UPDATE guardians SET 
    full_name = :full_name, 
    address = :address,
    email = :email, 
    phone_number = :phone_number 
    WHERE guardian_id = :guardian_id";

    $stmt = $pdo->prepare($guardian_sql);
    $stmt->execute([
      'full_name' => $full_name,
      'address' => $address,
      'email' => $email,
      'phone_number' => $phone_number,
      'guardian_id' => $guardian_id
    ]);

    echo '<script>window.history.back();</script>';
    exit;
  } catch (PDOException $error) {
    die("Error updating guardian: " . $error->getMessage());
  }
} else {
  echo '<script>window.history.back();</script>';
  exit;
}
?>
