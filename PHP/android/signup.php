<?php
// Connect MySQL connection
include "connection.php";

// Initialize values for security
$username = $password = $email = $location = "";

// Get user input values
$username = $mysqli->real_escape_string($_POST['username']);
$password = $mysqli->real_escape_string($_POST['password']);
$email = $mysqli->real_escape_string($_POST['email']);
$location = $mysqli->real_escape_string($_POST['location']);

// Check for user input values
if ($_SERVER['REQUEST_METHOD'] !== "POST" || !isset($username) || !isset($password) || !isset($email) || !isset($location) || empty($username) || empty($password) || empty($email) || empty($location)) {
  echo "Fill in all required entry fields.";
  exit();
} else {
  $user = $mysqli->query("SELECT * FROM user WHERE username='$username'");
  if ($user->num_rows === 0) {
    $time = date("Y-m-d H:i:s");
    if($mysqli->query("INSERT INTO user (uid, username, password, email, location, regis_date) VALUES (NULL, '$username', '$password', '$email', '$location', '$time')") === TRUE) {
      echo "Your account has been created now.";
      exit();
    } else {
      die("An error occurred during processing your request.");
    }
  } else {
    echo "Username \"$username\" is already being used by someone else.";
    exit();
  }
}

exit();
?>