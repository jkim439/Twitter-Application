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

// Find the person who has the most number of followers
$followMax = $mysqli->query("SELECT * FROM follow GROUP BY following_id ORDER BY COUNT(following_id) DESC LIMIT 1;")->fetch_array();
$userFollowMax = $mysqli->query("SELECT * FROM user WHERE uid='$followMax[following_id]'")->fetch_array();
$userFollowNum = $mysqli->query("SELECT * FROM follow WHERE following_id='$userFollowMax[uid]'")->num_rows;
$html = <<<HTML
<span style="margin-left: 20px;">The person who has the most number of followers</span>
<div class="divBox">
  <br><strong><img src="img/account.png" class="profile"> {$userFollowMax['username']}</strong> has <strong>{$userFollowNum}</strong> followers.<br><br>
</div><br>
HTML;
echo $html;

// Find the post that has the most number of likes
$thumbMax = $mysqli->query("SELECT * FROM thumb GROUP BY tid ORDER BY COUNT(tid) DESC LIMIT 1;")->fetch_array();
$twittsThumbMax = $mysqli->query("SELECT * FROM twitts WHERE tid='$thumbMax[tid]'")->fetch_array();
$writer = $mysqli->query("SELECT * FROM user WHERE uid='$twittsThumbMax[uid]'")->fetch_array();
$writer = $writer['username'];
$body = nl2br($twittsThumbMax['body']);
$thumb = $mysqli->query("SELECT * FROM thumb WHERE tid='$twittsThumbMax[tid]'");
$thumbNum = $thumb->num_rows;
if($thumbNum > 1) {
  $thumbNum = $thumbNum . " Likes";
} else {
  $thumbNum = $thumbNum . " Like";
}
$html = <<<HTML
<span style="margin-left: 20px;">The post that has the most number of likes</span>
<div class="divBox">
  <br><strong><img src="img/account.png" class="profile"> {$writer}</strong> <span class="date">({$twittsThumbMax['post_time']})</span><br><br>
  {$body}<br><br><hr>
  <span style="color: red;"><strong>{$thumbNum}</strong></span><br><br>
</div><br>
HTML;
echo $html;
?>