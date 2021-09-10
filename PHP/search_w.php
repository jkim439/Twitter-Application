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
$writer = "";

// Get user input values
$writer = $mysqli->real_escape_string($_POST['writer']);

// Check for user input values
if ($_SERVER['REQUEST_METHOD'] !== "POST" || !isset($writer) || empty($writer)) {
  die("An error occurred during processing your request.");
} else {
  echo "<span style='margin-left: 20px;'>Showing the posts made by <strong>" . $writer . "</strong></span><br><br>";
  $writer = $mysqli->query("SELECT * FROM user WHERE username='$writer'")->fetch_array();
  $twitts = $mysqli->query("SELECT * FROM twitts WHERE uid='$writer[uid]' ORDER BY post_time DESC");
  if($twitts->num_rows < 1) {
    echo "<span style='margin-left: 20px;'>No Search Result.</span>";
    exit();
  } else {
    while($row = $twitts->fetch_array()) {
      $writer = $mysqli->query("SELECT * FROM user WHERE uid='$row[uid]'")->fetch_array();  // Select and fetch user
      $body = nl2br($row['body']);
      $html = <<<HTML
      <div class="divBox">
        <br><strong><img src="img/account.png" class="profile"> {$writer['username']}</strong> <span class="date">({$row['post_time']})</span><br><br>
        {$body}<br><br>
      </div><br>
HTML;
    echo $html;
    }
  }
}
?>