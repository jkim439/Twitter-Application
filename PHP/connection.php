<?php
// Session start
session_start();

// Set timezone
date_default_timezone_set('America/New_York');

// Connect MySQL connection
$mysqli = new mysqli("localhost", "x", "x", "x");
if ($mysqli->connect_errno) {
  die("Connect failed: " . $mysqli->connect_error);
}
?>