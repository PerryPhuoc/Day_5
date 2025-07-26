<?php
session_start();

// 1. Nếu đã đăng nhập, chuyển hướng về trang chủ
if (isset($_SESSION['user_id'])) {
    header('Location: /Day5/frontend/index.php');
    exit();
}

$error_message = '';

// 2. Xử lý khi form được submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once(__DIR__ . '/../../dbconnect.php');

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Truy vấn người dùng dựa trên username
    $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Kiểm tra username tồn tại
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // So sánh mật khẩu người dùng nhập với mật khẩu lưu trong DB (chưa mã hóa)
        if ($password === $user['password']) {
            session_regenerate_id(true); // Bảo mật: tạo lại session_id

            // Lưu thông tin người dùng vào session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['kh_tendangnhap_logged'] = $user['username'];
            $_SESSION['user_role'] = $user['role']; // ✅ Lưu vai trò người dùng

            // Chuyển hướng về trang chính
            header('Location: /day5/frontend/index.php');
            exit();
        } else {
            $error_message = "Invalid username or password!";
        }
    } else {
        $error_message = "Invalid username or password!";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <?php include_once(__DIR__ . '/../layouts/styles.php'); ?>

    <style>
        .form-signin {
            max-width: 400px;
            max-height: 400px;
        }
    </style>
</head>
<body class="d-flex align-items-center py-4 bg-light text-center h-100">
    <main class="form-signin w-100 m-auto bg-white p-4 rounded shadow-sm">
        <form method="POST" action="">
            <h1 class="h3 mb-4 fw-normal">Please sign in</h1>

            <?php if (!empty($error_message)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                <label for="username">Username</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>

            <p class="mt-4 mb-2 text-body-secondary">
                Don't have an account? <a href="/day4/frontend/pages/register.php">Register now</a>
            </p>
            <p class="text-body-secondary">&copy; 2025</p>
        </form>
    </main>

    <?php include_once(__DIR__ . '/../layouts/scripts.php'); ?>
</body>
</html>
