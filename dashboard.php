<?php
session_start();

// Optional: Protect this page if user is not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  header("Location: index.php?error=Please login first.");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
</head>
<body>
  <h1>Welcome to the Dashboard!</h1>
  <p>You have successfully logged in.</p>

  <a href="logout.php">Logout</a>
</body>
</html>
