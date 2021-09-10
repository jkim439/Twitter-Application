<?php
// Connect MySQL connection
include "connection.php";

// Initialize values for security
$username = $password = "";

// Get user input values
$username = $mysqli->real_escape_string($_POST['username']);
$password = $mysqli->real_escape_string($_POST['password']);
$response = array("error" => FALSE);

// Check for user input values
if ($_SERVER['REQUEST_METHOD'] !== "POST" || !isset($username) || !isset($password) || empty($username) || empty($password))  {
  echo "Fill in all required entry fields.";
  exit();
} else {
  $user = $mysqli->query("SELECT * FROM user WHERE username='$username' AND password='$password'");
  if ($user->num_rows !== 1) {
    echo "Invalid Username or Password.";
    exit();
  } else {
    $user = $user->fetch_array();
    echo $user['uid'];
    exit();
  }
}

exit();
?>