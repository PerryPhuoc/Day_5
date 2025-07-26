<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /Day5/frontend/index.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Page</title>
    <?php include_once(__DIR__ . '/../layouts/styles.php'); ?>
</head>
<body>
    <?php include_once(__DIR__ . '/../layouts/header.php'); ?>
    <div class="container mt-4">
        <h1>Welcome to the Admin Dashboard</h1>
    </div>
</body>
</html>
