<?php
session_start();
$username = $_POST['username'];
$password = $_POST['password'];

$stored_username = "admin";
$stored_password = "admin";

$attemptsFile = 'data/login_attempts.json';
$attempts = file_exists($attemptsFile) ? json_decode(file_get_contents($attemptsFile), true) : [];

$ip = $_SERVER['REMOTE_ADDR'];
$now = time();
$lockoutDuration = 10;

if (!isset($attempts[$ip])) {
  $attempts[$ip] = ['count' => 0, 'last_time' => 0];
}

// Check existing lockout
$failData = $attempts[$ip];
$timePassed = $now - $failData['last_time'];

if ($failData['count'] >= 3 && $timePassed < $lockoutDuration) {
  $lockoutExpiresAt = $failData['last_time'] + $lockoutDuration;
  header("Location: index.php?error=locked&expires={$lockoutExpiresAt}");
  exit;
}

if ($username === $stored_username && $password === $stored_password) {
  $_SESSION['logged_in'] = true;
  unset($attempts[$ip]);
  file_put_contents($attemptsFile, json_encode($attempts));
  header("Location: dashboard.php");
  exit;
} else {
  // ❗Increment first
  $attempts[$ip]['count'] += 1;
  $attempts[$ip]['last_time'] = $now;

  // ✅ Check lockout again after increment
  if ($attempts[$ip]['count'] >= 3) {
    $lockoutExpiresAt = $attempts[$ip]['last_time'] + $lockoutDuration;
    file_put_contents($attemptsFile, json_encode($attempts));
    header("Location: index.php?error=locked&expires={$lockoutExpiresAt}");
    exit;
  }

  // Not locked yet, just show error
  file_put_contents($attemptsFile, json_encode($attempts));
  header("Location: index.php?error=Invalid credentials.");
  exit;
}
?>
