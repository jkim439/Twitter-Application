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

// Check for user input values
if ($_SERVER['REQUEST_METHOD'] !== "GET") {
  die("An error occurred during processing your request.");
} else {
  $result = $mysqli->query("SELECT user.location, COUNT(user.location) FROM user, twitts WHERE user.uid = twitts.uid AND (twitts.body LIKE '% flu' OR twitts.body LIKE 'flu%' OR twitts.body LIKE '% flu %' OR twitts.body LIKE '% flu%') GROUP BY user.location");
  echo "<span style='margin-left: 20px;'>Showing the result in <strong>flu</strong></span><br><br>";
  if($result->num_rows < 1) {
    echo "<span style='margin-left: 20px;'>No Search Result.</span>";
    exit();
  } else {
    while($row = $result->fetch_array()) {
      echo "<span style='margin-left: 20px;'><strong>$row[0]</strong>: $row[1]</span>";
    }
  }
}
?>