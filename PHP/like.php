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
$tid = "";

// Get user input values
$tid = $mysqli->real_escape_string($_POST['tid']);

// Check for user input values
if ($_SERVER['REQUEST_METHOD'] !== "POST" || !isset($tid) || empty($tid)) {
  die("An error occurred during processing your request.");
} else {
  $user = $mysqli->query("SELECT * FROM user WHERE uid='$_SESSION[uid]'")->fetch_array();
  $twitts = $mysqli->query("SELECT * FROM twitts WHERE tid='$_POST[tid]'")->fetch_array();
  $thumbMine = $mysqli->query("SELECT * FROM thumb WHERE tid='$_POST[tid]' AND uid='$user[uid]'");
  if($thumbMine->num_rows) {
    if($mysqli->query("DELETE FROM thumb WHERE tid='$_POST[tid]' AND uid='$user[uid]'") === TRUE) {
    } else {
      die("An error occurred during processing your request.");
    }
  } else {
    if($mysqli->query("INSERT INTO thumb (like_id, uid, tid) VALUES (NULL, '$user[uid]', '$_POST[tid]')") === TRUE) {
    } else {
      die("An error occurred during processing your request.");
    }
  }
}
?>