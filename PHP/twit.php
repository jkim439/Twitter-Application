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

$json = array();

$writer = $mysqli->query("SELECT * FROM user WHERE uid='$twitts[uid]'")->fetch_array();
$writer = $writer['username'];

$posttime = "(" . $twitts['post_time'] . ")";

$thumb = $mysqli->query("SELECT * FROM thumb WHERE tid='$twitts[tid]'");
$thumb = $thumb->num_rows;
if($thumb > 1) {
  $thumb = $thumb . " Likes";
} else {
  $thumb = $thumb . " Like";
}

$comment = $mysqli->query("SELECT * FROM comment WHERE tid='$twitts[tid]' ORDER BY comment_time"); 
$comment = $comment->num_rows;  // Get the number of comment
if($comment > 1) {
  $comment = $comment . " Comments";
} else {
  $comment = $comment . " Comment";
}

$follow = $mysqli->query("SELECT * FROM follow WHERE follower_id='$user[uid]' AND following_id='$twitts[uid]'")->num_rows;
if($user['uid'] === $twitts['uid']) {
  $follow = "2";  // 0: unfollowing, 1: following, 2: mine
}

$json['writer'] = $writer;
$json['posttime'] = $posttime;
$json['body'] = $twitts['body'];
$json['thumb'] = $thumb;
$json['comment'] = $comment;
$json['follow'] = $follow;
echo json_encode(array($json));

exit();
?>