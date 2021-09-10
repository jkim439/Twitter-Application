<?php
// Connect MySQL connection
include "connection.php";

// Check for session existence
if (isset($_SESSION['uid']) || !empty($_SESSION['uid'])) {
  header("Location: account.php");
  exit();
}
?>
<!DOCTYPE html>
<html>
  <head>
  <meta charset="UTF-8">
  <link rel="icon" href="img/favicon.ico">
  <link rel="stylesheet" href="css/index.css">
  <title>Twitter</title>
  <script>
    window.onload = function() {
      document.getElementById("username").focus();
    }
  </script>
</head>
<body>
  <div class="divCenter">
    <center><br><img src="img/index.png"><br><br>
    <div class="divBox">
      <br><form method="post" action="signin.php">
        <strong>Username<br><input type="text" name="username" id="username" required><br><br>
        Password<br><input type="password" name="password" required><br><br></strong>
        <button type="submit">Sign In</button><br><br>
        <a href="signup.php">No account? Create one!</a>
      </form><br>
    </div>
    </center>
  </div>
  <div class="divLeft"></div>
  <div class="divRight"></div>
  <div class="divBottom">
    Copyright © 2018 Black Hawk. All rights reserved.
  </div>
</body>
</html>