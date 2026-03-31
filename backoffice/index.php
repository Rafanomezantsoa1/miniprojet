<?php
session_start();

// Redirect to auth.php for login
if (empty($_SESSION['logged_in'])) {
    header('Location: login.html');
    exit();
}

// Redirect to dashboard if already logged in
header('Location: admin-dashboard.html');
exit();
?>
?>

