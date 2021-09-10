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
$user = $follower = $following = $twitts = $followers = $followings = $followerUser = $followingUser = "";

// Perform a query on the database
$user = $mysqli->query("SELECT * FROM user WHERE uid='$_SESSION[uid]'")->fetch_array();
$follower = $mysqli->query("SELECT * FROM follow WHERE following_id='$user[uid]'");
$following = $mysqli->query("SELECT * FROM follow WHERE follower_id='$user[uid]'");
$twitts = $mysqli->query("SELECT * FROM twitts ORDER BY post_time DESC");

// Create a follower list
$followers = "Your Followers:\\n\\n";
while($row = $follower->fetch_array()) {
  $followerUser = $mysqli->query("SELECT * FROM user WHERE uid='$row[follower_id]'")->fetch_array();
  $followerUser = "- " . $followerUser['username'];
  $followers = $followers . $followerUser . "\\n";
}

// Create a following list
$followings = "Your Followerings:\\n\\n";
while($row = $following->fetch_array()) {
  $followingUser = $mysqli->query("SELECT * FROM user WHERE uid='$row[following_id]'")->fetch_array();
  $followingUser = "- " . $followingUser['username'];
  $followings = $followings . $followingUser . "\\n";
}
?>
<!DOCTYPE html>
<html>
  <head>
  <meta charset="UTF-8">
  <link rel="icon" href="img/favicon.ico">
  <link rel="stylesheet" href="css/account.css">
  <title>Account</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="js/account.js"></script>>
</head>
<body>
  <!-- Top Div -->
  <div class="divTop"><br><img src="img/index.png" class="imgLogo"></div>
  <!-- Left Div -->
  <div class="divLeft">
    <br><img src="img/account.png">
    <h2><?php echo $user['username']?></h2><br>
    <?php if ($follower->num_rows > 0) {?><button type="button" class="buttonFollowerView" onclick="javascript:alert('<?php echo $followers?>');"><?php echo $follower->num_rows?> Follower<?php if ($follower->num_rows > 1) {?>s<?php }?></button><?php } else {?><button type="button" class="buttonFollowerView" style="opacity: 0.4;">No Follower</button><?php }?>
    <?php if ($following->num_rows > 0) {?><button type="button" class="buttonFollowingView" onclick="javascript:alert('<?php echo $followings?>');"><?php echo $following->num_rows?> Following<?php if ($following->num_rows > 1) {?>s<?php }?></button><?php } else {?><button type="button" class="buttonFollowingView" style="opacity: 0.4;">No Following</button><?php }?><br><br>
    <?php echo $user['email']?><br>
    <?php echo $user['location']?><br>
    Joined <?php echo $user['regis_date']?><br><br>
    <button type="button" class="buttonRed" onclick="if (confirm('Sign out of <?php echo $user['username']?>?'))javascript:location.href='signout.php';">Sign Out</button>
  </div>
  <!-- Twitt Div -->
  <div class="divRight"><br>
    <!-- Menu -->
    <input type="text" id="writer" name="writer" style="width: 20%; margin-left: 20px;" placeholder="Search Name" oninput="search_w(); document.getElementById('year').value='';"> 
    <input type="number" id="year" name="year" style="width: 10%;" placeholder="Search Year" oninput="search_y(); document.getElementById('writer').value='';"> 
    <button type="button" class="buttonMenu" id="buttonRank" onclick="rank(); document.getElementById('writer').value=''; document.getElementById('year').value='';">Top Rank</button> 
    <button type="button" class="buttonMenu" id="buttonMessage" onclick="message(); document.getElementById('writer').value=''; document.getElementById('year').value='';">Messages</button> 
    <button type="button" class="buttonRed" id="buttonCancel" onclick="cancel();">Cancel</button><br><br>
    <div class="divExtra"></div>
    <!-- New Twitt -->
    <div class="divBox">
      <center><br><strong><img src="img/account.png" class="profile"> <?php echo $user['username']?></strong><br><br>
      <form onsubmit="twitt_w();"><textarea name="body" id="body" placeholder="Post new twitt" required></textarea><br><button type="submit" class="buttonPost">Post</button></form><br></center>
    </div><br>
    <!-- List Twitts -->
    <?php
    while($row = $twitts->fetch_array()) {
      $writer = $mysqli->query("SELECT * FROM user WHERE uid='$row[uid]'")->fetch_array();  // Find writer
      $writer = $writer['username'];  // Find writer
      $thumb = $mysqli->query("SELECT * FROM thumb WHERE tid='$row[tid]'");  // Get thumb
      $thumbNum = $thumb->num_rows;  // Get the number of thumb
      $thumbMine = $mysqli->query("SELECT * FROM thumb WHERE tid='$row[tid]' AND uid='$user[uid]'");  // Check that I liked
      $comment = $mysqli->query("SELECT * FROM comment WHERE tid='$row[tid]' ORDER BY comment_time");  // Find comment
      $commentNum = $comment->num_rows;  // Get the number of comment
      $follow = $mysqli->query("SELECT * FROM follow WHERE follower_id='$user[uid]' AND following_id='$row[uid]'")->num_rows;  // Find follower
      if ($user['uid'] === $row['uid']) $follow = 2;  // Check that I post this twitt
    ?>
    <div class="divBox" style="border: 1.2px solid #005D95;">
      <br><strong><img src="img/account.png" class="profile"> <?php echo $writer?></strong> <span class="date">(<?php echo $row['post_time']?>)</span><?php if ($follow === 0) {?> <button type="button" class="buttonFollow" onclick="follow(<?php echo $row['uid']?>);">Follow</button><?php } if ($follow === 1) {?> <button type="button" class="buttonFollowing" onclick="if (confirm('Unfollow <?php echo $writer?>?'))follow(<?php echo $row['uid']?>);">Following</button> <img src="img/following.png" class="profile"> <?php }?><br><br>
      <?php echo nl2br($row['body']);?><?php if ($user['uid'] === $row['uid']) {?> <form style="display: inline;" onsubmit="if (confirm('Delete this twiit?'))twitt_d(<?php echo $row['tid']?>);return false;"><button type="submit" class="buttonDelete">X</button></form><?php }?><br><br><hr>
      <?php if ($thumbMine->num_rows) {?>
      <span style="color: red;"><strong><?php echo $thumbNum?> Like<?php if ($thumbNum > 1) {?>s<?php }?></strong></span> <button type="button" style="width: 20px; color: red;" class="buttonLiked" onclick="javascript:like(<?php echo $row['tid']?>);"> &hearts; </button><hr><?php } else {?>
      <strong><?php echo $thumbNum?> Like<?php if ($thumbNum > 1) {?>s<?php }?></strong> <button type="button" style="width: 20px; color: black;" class="buttonLike" onclick="javascript:like(<?php echo $row['tid']?>);"> &hearts; </button><hr><?php }?>
      <strong><?php echo $commentNum?> Comment<?php if ($commentNum > 1) {?>s<?php }?></strong><br><br>
      <?php
      while($rowComment = $comment->fetch_array()) {
        $writerComment = $mysqli->query("SELECT * FROM user WHERE uid='$rowComment[uid]'")->fetch_array();  // Find comment writer
        $writerComment = $writerComment['username'];  // Find comment writer
      ?>
      <span class="comment"><strong><?php echo $writerComment ?>:</strong> <?php echo $rowComment['body'];?><?php if ($user['uid'] === $rowComment['uid'] || $user['uid'] === $row['uid']) {?> <form style="display: inline;" onsubmit="if (confirm('Delete this comment?'))comment_d(<?php echo $rowComment['cid']?>);return false;"><button type="submit" class="buttonDelete">X</button></form><?php }?></span><br>
      <?php }?>
      <form onsubmit="comment_w(<?php echo $row['tid']?>);"><span class="comment"><strong><?php echo $user['username']?>:</strong></span> <input type="text" id="<?php echo "body".$row['tid']?>" name="body" style="width: 40%;" placeholder="Add comment" required> <button type="submit" class="buttonAdd">Add</button></form><br>
    </div><br>
    <?php }?>
  </div>
</body>
</html>