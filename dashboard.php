<?php
session_start();
if (!$_SESSION['logged_in']) {
  header("Location: index.php");
  exit;
}

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Load student data
$students = file_exists("data/students.json") ? json_decode(file_get_contents("data/students.json"), true) : [];
?>

<!DOCTYPE html>
<html>
<head>
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="style.css">
  <meta http-equiv="Cache-Control" content="no-store" />
</head>
<body>

<div class="container">
  <div class="sidebar">
    <!-- Sidebar content if any -->
  </div>

  <div class="main-content">
    <div class="navbar">
      <h2>Welcome to the Student Dashboard</h2>
      <div class="nav-buttons">
        <a href="register.php" class="register">Register Student</a>
        <a href="logout.php" class="logout">Logout</a>
      </div>
    </div>

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Reg No</th>
            <th>Age</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Course</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($students) === 0): ?>
            <tr><td colspan="6">No students registered yet.</td></tr>
          <?php else: ?>
            <?php foreach ($students as $s): ?>
              <tr>
                <td><?= htmlspecialchars($s['name']) ?></td>
                <td><?= htmlspecialchars($s['reg_no']) ?></td>
                <td><?= htmlspecialchars($s['age']) ?></td>
                <td><?= htmlspecialchars($s['email']) ?></td>
                <td><?= htmlspecialchars($s['phone']) ?></td>
                <td><?= htmlspecialchars($s['course']) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  // Force reload if page is loaded from back-forward cache
  window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
      window.location.reload();
    }
  });
</script>

</body>
</html>
  