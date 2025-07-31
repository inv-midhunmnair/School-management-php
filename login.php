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
$lockoutDuration = 10; // 2 minutes in seconds

if (!isset($attempts[$ip])) {
  $attempts[$ip] = ['count' => 0, 'last_time' => 0];
}

// Lockout check
$failData = $attempts[$ip];
$timePassed = $now - $failData['last_time'];

if ($failData['count'] >= 3) {
  if ($timePassed < $lockoutDuration) {
    $lockoutExpiresAt = $failData['last_time'] + $lockoutDuration;
    header("Location: index.php?error=locked&expires={$lockoutExpiresAt}");
    exit;
  } else {
    // Lockout expired, reset attempt count
    $attempts[$ip] = ['count' => 0, 'last_time' => 0];
  }
}

if ($username === $stored_username && $password === $stored_password) {
  $_SESSION['logged_in'] = true;
  unset($attempts[$ip]);
  file_put_contents($attemptsFile, json_encode($attempts));
  header("Location: dashboard.php");
  exit;
} else {
  $attempts[$ip]['count'] += 1;
  $attempts[$ip]['last_time'] = $now;

  // Lock immediately on 3rd failed attempt
  if ($failData['count'] >= 3) {
  if ($timePassed < $lockoutDuration) {
    $lockoutExpiresAt = $failData['last_time'] + $lockoutDuration;
    header("Location: index.php?error=locked&expires={$lockoutExpiresAt}");
    exit;
  } else {
    // ✅ Reset after lockout ends
    $attempts[$ip] = ['count' => 0, 'last_time' => 0];
    file_put_contents($attemptsFile, json_encode($attempts)); // 🛠️ Save reset
  }
}

  file_put_contents($attemptsFile, json_encode($attempts));
  header("Location: index.php?error=Invalid credentials.");
  exit;
}
?>
