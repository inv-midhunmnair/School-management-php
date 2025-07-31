<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<form method="POST" action="login.php">
  <h2>Admin Login</h2>
  <label>Username:
    <input type="text" name="username" required>
  </label>
  <label>Password:
    <input type="password" name="password" required>
  </label>
  <button type="submit">Login</button>
</form>

<?php
if (isset($_GET['error'])) {
  if ($_GET['error'] === 'locked' && isset($_GET['expires'])) {
    $expiresAt = intval($_GET['expires']) * 1000; // Convert to milliseconds for JS

    echo "
      <p class='error' id='lockout-msg'>
        Too many attempts. Try again in <span id='countdown'></span>
      </p>
      <script>
        const countdownEl = document.getElementById('countdown');
        const expiresAt = $expiresAt;

        function updateCountdown() {
          const now = Date.now();
          let remainingMs = expiresAt - now;

          if (remainingMs <= 0) {
            countdownEl.textContent = '00:00';
            return;
          }

          const totalSeconds = Math.floor(remainingMs / 1000);
          const minutes = Math.floor(totalSeconds / 60);
          const seconds = totalSeconds % 60;

          countdownEl.textContent =
            String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');

          setTimeout(updateCountdown, 1000);
        }

        updateCountdown();
      </script>
    ";
  } else {
    echo "<p class='error'>" . htmlspecialchars($_GET['error']) . "</p>";
  }
}
?>


</body>
</html>
