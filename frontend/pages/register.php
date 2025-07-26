<?php
session_start();

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once(__DIR__ . '/../../dbconnect.php');

    // Nhận dữ liệu từ form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    // --- VALIDATION ---
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $error_message = "Password must be at least 6 characters long.";
    } else {
        // Kiểm tra username đã tồn tại chưa
        $sql_check = "SELECT id FROM users WHERE username = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param('s', $username);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Username already exists!";
        } else {
            // Không hash password
            $plain_password = $password;

            // Thêm người dùng mới vào database
            $sql_insert = "INSERT INTO users (username, password, email, address) VALUES (?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param('ssss', $username, $plain_password, $email, $address);

            if ($stmt_insert->execute()) {
                // Tự động đăng nhập sau khi đăng ký
                $_SESSION['user_id'] = $conn->insert_id;
                $_SESSION['username'] = $username;
                $_SESSION['kh_tendangnhap_logged'] = $username;

                header('Location: /Day5/frontend/index.php');
                exit();
            } else {
                $error_message = "An error occurred. Please try again later.";
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <?php include_once(__DIR__ . '/../layouts/styles.php'); ?>

    <style>
        .form-register {
            max-width: 400px;
            max-height: 600px;
        }
    </style>
</head>
<body class="d-flex align-items-center py-4 bg-light text-center h-100">

    <main class="form-register w-100 m-auto bg-white p-4 rounded shadow-sm">
        <form method="POST" action="">

            <h1 class="h3 mb-4 fw-normal">Create an Account</h1>

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

            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                <label for="confirm_password">Confirm Password</label>
            </div>

            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="address" name="address" placeholder="Address" required>
                <label for="address">Address</label>
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">Register</button>

            <p class="mt-4 mb-2 text-body-secondary">
                Already have an account? <a href="/day4/frontend/pages/login.php">Sign in</a>
            </p>
            <p class="text-body-secondary">&copy; 2025</p>
        </form>
    </main>

    <?php include_once(__DIR__ . '/../layouts/scripts.php'); ?>
</body>
</html>
