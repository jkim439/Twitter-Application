<?php
// Set timezone
date_default_timezone_set('America/New_York');

// Connect MySQL connection
$mysqli = new mysqli("localhost", "jace4390_admin", "L#^*sb[JHD=7", "jace4390_tweeter");
if ($mysqli->connect_errno) {
  die("Connect failed: " . $mysqli->connect_error);
}
?>