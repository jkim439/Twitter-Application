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
$year = "";

// Get user input values
$year = $mysqli->real_escape_string($_POST['year']);

// Check for user input values
if ($_SERVER['REQUEST_METHOD'] !== "POST" || !isset($year) || empty($year)) {
  die("An error occurred during processing your request.");
} else {
  $twitts = $mysqli->query("SELECT * FROM twitts WHERE year(post_time)='$year' ORDER BY post_time DESC");
 echo "<span style='margin-left: 20px;'>Showing the posts wriiten in <strong>" . $year . "</strong><br><br>";
  if($twitts->num_rows < 1) {
    echo "<span style='margin-left: 20px;'>No Search Result.</span>";
    exit;
  } else {
    while($row = $twitts->fetch_array()) {
      $writer = $mysqli->query("SELECT * FROM user WHERE uid='$row[uid]'")->fetch_array();
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