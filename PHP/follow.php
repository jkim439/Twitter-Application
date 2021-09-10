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
$uid = "";

// Get user input values
$uid = $mysqli->real_escape_string($_POST['uid']);

// Check for user input values
if ($_SERVER['REQUEST_METHOD'] !== "POST" || !isset($uid) || empty($uid)) {
  die("An error occurred during processing your request.");
} else {
  $user = $mysqli->query("SELECT * FROM user WHERE uid='$_SESSION[uid]'")->fetch_array();
  $follow = $mysqli->query("SELECT * FROM follow WHERE follower_id='$user[uid]' AND following_id='$uid'")->num_rows;
  $time = date("Y-m-d H:i:s");
  
  // When the user follow
  if($follow === 0) {
    if($mysqli->query("INSERT INTO follow (follower_id, following_id, follow_time) VALUES ('$user[uid]', '$uid', '$time')") === TRUE) {
    } else {
      die("An error occurred during processing your request.");
    }
  
  // When the user unfollow
  } else {
    if($mysqli->query("DELETE FROM follow WHERE follower_id='$user[uid]' AND following_id='$uid'") === TRUE) {
    } else {
      die("An error occurred during processing your request.");
    }
  }
}
?>