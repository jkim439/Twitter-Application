<?php
// Connect MySQL connection
include "connection.php";

// Initialize values for security
$uid = $body = "";

// Get user input values
$uid = $mysqli->real_escape_string($_POST['uid']);
$body = $mysqli->real_escape_string($_POST['body']);

// Check the user account
if ($mysqli->query("SELECT * FROM user WHERE uid='$uid'")->num_rows !== 1) {
  die("An error occurred during processing your request.");
} else {
  $user = $mysqli->query("SELECT * FROM user WHERE uid='$uid'")->fetch_array();
}

// Check for user input values
if ($_SERVER['REQUEST_METHOD'] !== "POST" || !isset($body) || empty($body)) {
  echo "Fill in all required entry fields.";
  exit();
} else {
  $time = date("Y-m-d H:i:s");
  if($mysqli->query("INSERT INTO twitts (tid, uid, body, post_time) VALUES (NULL, '$user[uid]', '$body', '$time')") === TRUE) {
    echo "Your twit has been posted now.";
    exit();
  } else {
    die("An error occurred during processing your request.");
  }
}

exit();
?>