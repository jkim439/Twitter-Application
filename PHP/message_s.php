<?php
// Connect MySQL connection
include "connection.php";

// Check for session existence
if (!isset($_SESSION['uid']) || empty($_SESSION['uid'])) {
  header("Location: index.php");
  exit();
}

// Check the session value
if ($mysqli->query("SELECT * FROM user WHERE uid='$_SESSION[uid]'")->num_rows !== 1) {
  die("An error occurred during processing your request.");
}

// Initialize values for security
$receiver_id = $body = "";

// Get user input values
$receiver_id = $mysqli->real_escape_string($_POST['receiver_id']);
$body = $mysqli->real_escape_string($_POST['body']);

// Check for user input values
if ($_SERVER['REQUEST_METHOD'] !== "POST" || !isset($receiver_id) || empty($receiver_id) || !isset($body) || empty($body)) {
  die("An error occurred during processing your request.");
} else {
  $user = $mysqli->query("SELECT * FROM user WHERE uid='$_SESSION[uid]'")->fetch_array();
  $time = date("Y-m-d H:i:s");
  if($mysqli->query("INSERT INTO message (message_id, receiver_id, sender_id, body, send_time) VALUES (NULL, '$receiver_id', '$user[uid]', '$body', '$time')") === TRUE) {
  } else {
    die("An error occurred during processing your request.");
  }
}
?>