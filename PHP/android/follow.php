<?php
// Connect MySQL connection
include "connection.php";

// Initialize values for security
$uid = $tid = $json = "";

// Get user input values
$uid = $mysqli->real_escape_string($_POST['uid']);
$tid = $mysqli->real_escape_string($_POST['tid']);

// Check the user account
if (($mysqli->query("SELECT * FROM user WHERE uid='$uid'")->num_rows !== 1) || ($mysqli->query("SELECT * FROM twitts WHERE tid='$tid'")->num_rows !== 1)) {
  die("An error occurred during processing your request.");
} else {
  $user = $mysqli->query("SELECT * FROM user WHERE uid='$uid'")->fetch_array();
  $twitts = $mysqli->query("SELECT * FROM twitts WHERE tid='$tid'")->fetch_array();
}
$time = date("Y-m-d H:i:s");
$follow = $mysqli->query("SELECT * FROM follow WHERE follower_id='$user[uid]' AND following_id='$twitts[uid]'")->num_rows;
if($user['uid'] === $twitts['uid']) {
  die("An error occurred during processing your request.");
}
if($follow === 0) {
  if($mysqli->query("INSERT INTO follow (follower_id, following_id, follow_time) VALUES ('$user[uid]', '$twitts[uid]', '$time')") === TRUE) {
  } else {
    die("An error occurred during processing your request.");
  }
} else {
    if($mysqli->query("DELETE FROM follow WHERE follower_id='$user[uid]' AND following_id='$twitts[uid]'") === TRUE) {
    } else {
      die("An error occurred during processing your request.");
    }
}

exit();
?>