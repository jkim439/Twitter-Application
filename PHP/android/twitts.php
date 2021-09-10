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
  $twitts = $mysqli->query("SELECT * FROM twitts ORDER BY post_time DESC");
}

$json = array();
while($row = $twitts->fetch_array()) {
  $writer = $mysqli->query("SELECT * FROM user WHERE uid='$row[uid]'")->fetch_array();  // Find writer
  $writer = $writer['username'];
  $posttime = "(" . $row['post_time'] . ")";
  $thumb = $mysqli->query("SELECT * FROM thumb WHERE tid='$row[tid]'");
  $thumbNum = $thumb->num_rows;
  if($thumbNum > 1) {
    $thumbNum = $thumbNum . " Likes";
  } else {
    $thumbNum = $thumbNum . " Like";
  }
  $comment = $mysqli->query("SELECT * FROM comment WHERE tid='$row[tid]' ORDER BY comment_time");  // Find comment
  $commentNum = $comment->num_rows;  // Get the number of comment
  if($commentNum > 1) {
    $commentNum = $commentNum . " Comments";
  } else {
    $commentNum = $commentNum . " Comment";
  }
  $follow = $mysqli->query("SELECT * FROM follow WHERE follower_id='$user[uid]' AND following_id='$row[uid]'")->num_rows;  // Find follower
  if($user['uid'] === $row['uid']) {
    $follow = "";
  } else if ($follow === 2) {
    $follow = "";
  } else {
    $follow = "[Following]";
  }
  array_push($json,
             array('tid'=>$row[0],
                   'username'=>$writer,
                   'posttime'=>$posttime,
                   'body'=>$row['body'],
                   'thumb'=>$thumbNum,
                   'comment'=>$commentNum,
                   'follow'=>$follow
  ));
}

header('Content-Type: application/json; charset=utf8');
$json = json_encode(array("twitts"=>$json), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
echo $json;

exit();
?>