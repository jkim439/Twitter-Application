<?php
// Connect MySQL connection
include "connection.php";
$json = array();

// Find the person who has the most number of followers
$followMax = $mysqli->query("SELECT * FROM follow GROUP BY following_id ORDER BY COUNT(following_id) DESC LIMIT 1;")->fetch_array();
$userFollowMax = $mysqli->query("SELECT * FROM user WHERE uid='$followMax[following_id]'")->fetch_array();
$userFollowNum = $mysqli->query("SELECT * FROM follow WHERE following_id='$userFollowMax[uid]'")->num_rows;
$json['followUsername'] = $userFollowMax['username'];
$json['followNum'] = $userFollowNum;

// Find the post that has the most number of likes
$thumbMax = $mysqli->query("SELECT * FROM thumb GROUP BY tid ORDER BY COUNT(tid) DESC LIMIT 1;")->fetch_array();
$twittsThumbMax = $mysqli->query("SELECT * FROM twitts WHERE tid='$thumbMax[tid]'")->fetch_array();
$writer = $mysqli->query("SELECT * FROM user WHERE uid='$twittsThumbMax[uid]'")->fetch_array();
$writer = $writer['username'];
$posttime = "(" . $twittsThumbMax['post_time'] . ")";
$thumb = $mysqli->query("SELECT * FROM thumb WHERE tid='$twittsThumbMax[tid]'");
$thumbNum = $thumb->num_rows;
if($thumbNum > 1) {
  $thumbNum = $thumbNum . " Likes";
} else {
  $thumbNum = $thumbNum . " Like";
}
$json['thumbUsername'] = $writer;
$json['thumbPosttime'] = $posttime;
$json['thumbBody'] = $twittsThumbMax['body'];
$json['thumbNum'] = $thumbNum;
echo json_encode(array($json));
?>