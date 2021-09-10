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
  <link rel="stylesheet" href="css/signup.css">
  <title>Sign Up</title>
  <script>
    window.onload = function() {
      document.getElementById("username").focus();
    }
  </script>
</head>
<body>
  <center>
  <div class="divBox">
    <form method="post" action="signup_r.php"><br>
    <!-- pattern="[a-zA-Z]{1,}, [a-zA-Z]{2}" -->
    <span style="margin-right: 20px; float:right;"><a href="index.php">Have account? Sign In!</a></span><br><br><br><strong>
    Username<br><span class="spanGrey">(At least three letters)</span><br><input type="text" name="username" id="username" required pattern="[a-zA-Z]{3,}"><br><br><br>
    Password<br><span class="spanGrey">(At least one letters or numbers)</span><br><input type="password" name="password" required pattern="[a-zA-Z0-9]{1,}"><br><br><br>
    Email<br><span class="spanGrey">(Email address)</span><br><input type="email" name="email" required><br><br><br>
    Location<br><span class="spanGrey">(City, State)</span><br><input type="text" name="location" required placeholder="Lowell, MA"><br><br><br>
    <button type="submit" class="buttonPost">Sign Up</button><br><br></strong><br>
    </center>
  </div>
  </center>
</body>
</html>