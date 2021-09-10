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
$tid = $body = "";

// Get user input values
$tid = $mysqli->real_escape_string($_POST['tid']);
$body = $mysqli->real_escape_string($_POST['body']);

// Check for user input values
if ($_SERVER['REQUEST_METHOD'] !== "POST" || !isset($tid) || !isset($body) || empty($tid) || empty($body)) {
  die("An error occurred during processing your request.");
} else {
  $user = $mysqli->query("SELECT * FROM user WHERE uid='$_SESSION[uid]'")->fetch_array();
  $time = date("Y-m-d H:i:s");
  if($mysqli->query("INSERT INTO comment (cid, uid, tid, body, comment_time) VALUES (NULL, '$user[uid]', '$tid', '$body', '$time')") === TRUE) {
  } else {
    die("An error occurred during processing your request.");
  }
}
?>