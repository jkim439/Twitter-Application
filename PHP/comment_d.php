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
$cid = "";

// Get user input values
$cid = $mysqli->real_escape_string($_POST['cid']);

// Check for user input values
if ($_SERVER['REQUEST_METHOD'] !== "POST" || !isset($cid) || empty($cid)) {
  die("An error occurred during processing your request.");
} else {
  $user = $mysqli->query("SELECT * FROM user WHERE uid='$_SESSION[uid]'")->fetch_array();
  $comment = $mysqli->query("SELECT * FROM comment WHERE cid='$cid'")->fetch_array();
  $twitts = $mysqli->query("SELECT * FROM twitts WHERE tid='$comment[tid]'")->fetch_array();
  if($user['uid'] === $comment['uid'] || $user['uid'] === $twitts['uid']) {
    if($mysqli->query("DELETE FROM comment WHERE cid='$cid'") === TRUE) {
    } else {
      die("An error occurred during processing your request.");
    }
  } else {
    die("An error occurred during processing your request.");
  }
}
?>