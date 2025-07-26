<?php
// Bắt đầu session để xóa dữ liệu
session_start();

// Xóa toàn bộ session (xóa cả đăng nhập)
session_unset();
session_destroy();

if (isset($_SESSION['user_id'])) {
    unset($_SESSION['user_id']);
    unset($_SESSION['username']);
    header('location:login.php');
} else {
    echo 'User not login!';
    die;
}

// Chuyển hướng về trang đăng nhập hoặc trang chủ
header('Location: /Day5/frontend/index.php');
exit;
