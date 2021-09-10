<?php
// Connect MySQL connection
include "connection.php";

// Check for session existence
if (isset($_SESSION['uid']) || !empty($_SESSION['uid'])) {
  header("Location: account.php");
  exit();
}

// Initialize values for security
$username = $password = $email = $location = "";

// Get user input values
$username = $mysqli->real_escape_string($_POST['username']);
$password = $mysqli->real_escape_string($_POST['password']);
$email = $mysqli->real_escape_string($_POST['email']);
$location = $mysqli->real_escape_string($_POST['location']);

// Check for user input values
if ($_SERVER['REQUEST_METHOD'] !== "POST" || !isset($username) || !isset($password) || !isset($email) || !isset($location) || empty($username) || empty($password) || empty($email) || empty($location)) {
  die("An error occurred during processing your request.");
} else {
  $user = $mysqli->query("SELECT * FROM user WHERE username='$username'");
  if ($user->num_rows === 0) {
    $time = date("Y-m-d H:i:s");
    if($mysqli->query("INSERT INTO user (uid, username, password, email, location, regis_date) VALUES (NULL, '$username', '$password', '$email', '$location', '$time')") === TRUE) {
      echo "<script>alert(\"Your account has been created now.\");location.href='index.php';</script>";
      exit();
    } else {
      die("An error occurred during processing your request.");
    }
  } else {
    echo "<script>alert(\"Username $username is already being used by someone else.\");history.back();</script>";
    exit();
  }
}
?>