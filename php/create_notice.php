<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header('Location: login.php');
  exit;
}

if ($_SESSION['usertype'] != 'admin') {
  die("403: You are not authorized to access this resource.");
}

require 'connect.php';

if (isset($_POST['date'], $_POST['description'])) {
  $notice_date = $_POST['date'];
  $notice_title = trim($_POST['title']);
  $notice_description = trim($_POST['description']);

  try {
    $stmt = $pdo->prepare("INSERT INTO notices (description, notice_date, title) VALUES (?, ?, ?)");
    $stmt->execute([$notice_description, $notice_date, $notice_title]);

    echo '<script>window.history.back();</script>';
    exit;
  } catch (PDOException $error) {
    die("Error creating notice: " . $error->getMessage());
  }
} else {
  echo '<script>window.history.back();</script>';
  exit;
}
?>
