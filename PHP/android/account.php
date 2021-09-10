<?php
// Connect MySQL connection
include "connection.php";

// Initialize values for security
$uid = "";

// Get user input values
$uid = $mysqli->real_escape_string($_POST['uid']);

// Check the user account
if ($mysqli->query("SELECT * FROM user WHERE uid='$uid'")->num_rows !== 1) {
  die("An error occurred during processing your request.");
} else {
  $user = $mysqli->query("SELECT * FROM user WHERE uid='$uid'")->fetch_array();
}

$json = array();
$json['uid'] = $user['uid'];
$json['username'] = $user['username'];
$json['password'] = $user['password'];
$json['email'] = $user['email'];
$json['location'] = $user['location'];
$json['regis_date'] = $user['regis_date'];
echo json_encode(array($json));

exit();
?>