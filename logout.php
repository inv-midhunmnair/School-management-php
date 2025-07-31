<?php
session_start();
session_unset();
session_destroy();

// Prevent caching of logout redirection page
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Redirect to login
header("Location: index.php");
exit;
?>
