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

// Perform a query on the database
$user = $mysqli->query("SELECT * FROM user WHERE uid='$_SESSION[uid]'")->fetch_array();
$msgInbox = $mysqli->query("SELECT * FROM message WHERE receiver_id='$_SESSION[uid]' ORDER BY send_time DESC");
$msgOutbox = $mysqli->query("SELECT * FROM message WHERE sender_id='$_SESSION[uid]' ORDER BY send_time DESC");
$msgInboxNum = $msgInbox->num_rows;
$msgOutboxNum = $msgOutbox->num_rows;

// List incoming messages
echo "<span style='margin-left: 20px;'>Incoming Messages (<strong>$msgInboxNum</strong>)</span>";
if($msgInboxNum < 1) {
  echo "<br><br><span class='date' style='margin-left: 20px;'>You don't have any incoming messages.</span><br><br>";
} else {
  while($row = $msgInbox->fetch_array()) {
    $sender = $mysqli->query("SELECT * FROM user WHERE uid='$row[sender_id]'")->fetch_array();
    $sender = $sender['username'];
    $body = nl2br($row['body']);
    $html = <<<HTML
    <div class="divBox" style="border: 1.2px solid #005D95;">
      <br>From: <strong><img src="img/account.png" class="profile"> {$sender}</strong><br>
      To: <strong><img src="img/account.png" class="profile"> {$user['username']}</strong><br>
      Date: <span class="date">({$row['send_time']})</span><br><br>
      {$body}<br><br>
    </div><br>
HTML;
    echo $html;
  }
}

// List outgoing messages
echo "<span style='margin-left: 20px;'>Outgoing Messages (<strong>$msgOutboxNum</strong>)</span>";
if($msgOutboxNum < 1) {
  echo "<br><br><span class='date' style='margin-left: 20px;'>You don't have any outgoing messages.</span><br><br>";
} else {
  while($row = $msgOutbox->fetch_array()) {
    $receiver = $mysqli->query("SELECT * FROM user WHERE uid='$row[receiver_id]'")->fetch_array();
    $receiver = $receiver['username'];
    $body = nl2br($row['body']);
    $html = <<<HTML
    <div class="divBox" style="border: 1.2px solid #005D95;">
      <br>From: <strong><img src="img/account.png" class="profile"> {$user['username']}</strong><br>
      To: <strong><img src="img/account.png" class="profile"> {$receiver}</strong><br>
      Date: <span class="date">({$row['send_time']})</span><br><br>
        {$body}<br><br>
    </div><br>
HTML;
    echo $html;
  }
}
?>
<span style="margin-left: 20px;">Send New Message</span>
<div class="divBox">
  <form onsubmit="message_s();"><br>From: <strong><img src="img/account.png" class="profile"> <?php echo $user['username']?></strong><br>
  To: <strong><img src="img/account.png" class="profile"> 
  <select name="receiver_id" id="receiver_id">
  <?php
  $userList = $mysqli->query("SELECT * FROM user WHERE NOT uid='$user[uid]'");
  while($row = $userList->fetch_array()) {
  ?>
  <option value="<?php echo $row['uid']?>"><?php echo $row['username']?></option>
  <?php }?>
</select>
  </strong><br>
  Date: <span class="date">(<?php echo date("Y-m-d H:i:s");?>)</span><br><br>
  <center><textarea name="body" id="body" placeholder="Send New Message" required></textarea><br><button type="submit" class="buttonPost">Send</button></center><br><br>
  </form>
</div><br>