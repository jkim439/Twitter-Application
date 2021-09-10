<?php
// Connect MySQL connection
include "connection.php";

// Check for session existence
if (isset($_SESSION['uid']) || !empty($_SESSION['uid'])) {
  header("Location: account.php");
  exit();
}

// Initialize values for security
$username = $password = "";

// Get user input values
$username = $mysqli->real_escape_string($_POST['username']);
$password = $mysqli->real_escape_string($_POST['password']);

// Check for user input values
if ($_SERVER['REQUEST_METHOD'] !== "POST" || !isset($username) || !isset($password) || empty($username) || empty($password)) {
  die("An error occurred during processing your request.");
} else {
  $user = $mysqli->query("SELECT * FROM user WHERE username='$username' AND password='$password'");
  if ($user->num_rows !== 1) {
    echo "<script>alert(\"Invalid Username or Password.\");history.back();</script>";
    exit();
  } else {
    
    // Check for session existence
    if (!isset($_SESSION['uid']) || empty($_SESSION['uid'])) {
      $user = $user->fetch_array();
      session_regenerate_id(true);
      $_SESSION['uid'] = htmlspecialchars($user['uid']);
      header("Location: account.php");
      exit();
    } else {
      die("An error occurred during processing your request.");
    }
  }
}
?>