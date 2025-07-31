<?php
session_start();
if (!$_SESSION['logged_in']) header("Location: index.php");

$name = $reg_no = $email = $phone = $course = '';
$age = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $reg_no = $_POST['reg_no'];
  $age = (int)$_POST['age'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $course = $_POST['course'];

  // Validation
  if (!preg_match("/^[a-zA-Z ]{2,}$/", $name)) $errors[] = "Name must be at least 2 letters and only alphabets.";
  if (!preg_match("/^REG-\d{4}-\d{4}$/", $reg_no)) $errors[] = "Registration number must be in REG-YYYY-NNNN format.";
  if ($age < 18 || $age > 25) $errors[] = "Age must be between 18 and 25.";
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email address.";
  if (!preg_match("/^\d{10}$/", $phone)) $errors[] = "Phone number must be 10 digits.";
  if (!in_array($course, ['BCA', 'BSc', 'MCA'])) $errors[] = "Please select a valid course.";

  if (empty($errors)) {
    $dataFile = 'data/students.json';
    $students = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
    $students[] = compact('name', 'reg_no', 'age', 'email', 'phone', 'course');
    file_put_contents($dataFile, json_encode($students, JSON_PRETTY_PRINT));
    header("Location: dashboard.php");
    exit;
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Register Student</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-content">
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="navbar">
      <h2>Register New Student</h2>
      <div class="nav-buttons">
        <a href="dashboard.php" class="register">Back to Dashboard</a>
      </div>
    </div>

    <?php if (!empty($errors)): ?>
      <div class="form-error-box">
        <?php foreach ($errors as $error): ?>
          <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <div class="form-card">
      <form method="POST" action="">
        <h2 class="sub heading">Make sure to fill all the fields</h2>
        <label>Name:
          <input type="text" name="name" required placeholder="e.g. John Doe" value="<?= htmlspecialchars($name) ?>">
        </label>

        <label>Registration Number:
          <input type="text" name="reg_no" required placeholder="e.g. REG-2024-0001" value="<?= htmlspecialchars($reg_no) ?>">
        </label>

        <label>Age:
          <input type="number" name="age" required min="18" max="25" value="<?= htmlspecialchars($age) ?>">
        </label>

        <label>Email:
          <input type="email" name="email" required placeholder="e.g. john@example.com" value="<?= htmlspecialchars($email) ?>">
        </label>

        <label>Phone Number:
          <input type="text" name="phone" required placeholder="10-digit number" value="<?= htmlspecialchars($phone) ?>">
        </label>

        <label>Course:
          <select name="course" required>
            <option value="">-- Select Course --</option>
            <option value="BCA" <?= $course === 'BCA' ? 'selected' : '' ?>>BCA</option>
            <option value="BSc" <?= $course === 'BSc' ? 'selected' : '' ?>>BSc</option>
            <option value="MCA" <?= $course === 'MCA' ? 'selected' : '' ?>>MCA</option>
          </select>
        </label>

        <button type="submit" class="submit-btn">Create Student</button>
      </form>
    </div>
  </div>
</div>

</body>
</html>
