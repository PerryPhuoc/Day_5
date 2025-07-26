<?php
// Start session
if (session_id() === '') {
    session_start();
}

// Include general configuration file
include_once(__DIR__ . '/../../config.php');

// Include database connection file
include_once(__DIR__ . '/../../../dbconnect.php');

$id = $_GET['id']; // Get user ID from URL

// Check if ID is invalid
if (!isset($id) || !is_numeric($id)) {
    echo '<script>alert("Invalid user ID!"); window.location.href = "index.php";</script>';
    exit();
}

// --------------------------------------------------
// Get current user data to display in Form
// --------------------------------------------------
$sqlSelect = "SELECT id, username, email, role FROM users WHERE id = ?";
$stmtSelect = $conn->prepare($sqlSelect);
$stmtSelect->bind_param("i", $id);
$stmtSelect->execute();
$resultSelect = $stmtSelect->get_result();
$user = $resultSelect->fetch_assoc();

// If user not found
if (!$user) {
    echo '<script>alert("User not found with this ID!"); window.location.href = "index.php";</script>';
    exit();
}
$stmtSelect->close();

// --------------------------------------------------
// Process data when Form is SUBMITTED (Update)
// --------------------------------------------------
if (isset($_POST['btnUpdate'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $new_password = $_POST['password']; // New password (can be empty)
    $role = $_POST['role'];

    // Start building the SQL UPDATE statement
    $sqlUpdate = "UPDATE users SET username = ?, email = ?, role = ? ";
    $params = "sss"; // Initial parameter types for username, email, role
    $values = [&$username, &$email, &$role]; // Initial values

    // If a new password is provided, hash it and add to the UPDATE statement
    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sqlUpdate .= ", password = ? ";
        $params .= "s"; // Add 's' for string (hashed password)
        $values[] = &$hashed_password; // Add hashed password to values
    }

    $sqlUpdate .= "WHERE id = ?";
    $params .= "i"; // Add 'i' for integer (id)
    $values[] = &$id; // Add id to values

    $stmtUpdate = $conn->prepare($sqlUpdate);

    // Dynamically bind parameters using call_user_func_array
    call_user_func_array([$stmtUpdate, 'bind_param'], array_merge([$params], $values));

    if ($stmtUpdate->execute()) {
        // Success: Redirect directly without a pop-up message
        echo '<script>window.location.href = "index.php";</script>';
    } else {
        // Error: Show a basic browser alert
        echo '<script>alert("Error updating user: ' . $stmtUpdate->error . '");</script>';
    }

    $stmtUpdate->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>

    <!-- Include common CSS/JS from layout -->
    <?php include_once(__DIR__ . '/../../layouts/partials/head.php'); ?>
</head>

<body class="d-flex flex-column h-100">
    <!-- header -->
    <?php include_once(__DIR__ . '/../../layouts/partials/header.php'); ?>
    <!-- end header -->

    <div class="container-fluid flex-grow-1">
        <div class="row">
            <!-- sidebar -->
            <?php include_once(__DIR__ . '/../../layouts/partials/sidebar.php'); ?>
            <!-- end sidebar -->

            <main role="main" class="col-md-10 ml-sm-auto px-4 mb-2 flex-grow-1">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Update User</h1>
                </div>

                <!-- Block content -->
                <form action="" method="post" name="frmUpdate" id="frmUpdate">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password">New Password (leave blank if no change):</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="form-text text-muted">Leave blank if you don't want to change the password.</small>
                    </div>
                    <div class="form-group">
                        <label for="role">Role:</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="user" <?= ($user['role'] == 'user') ? 'selected' : ''; ?>>User</option>
                            <option value="admin" <?= ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>
                    <button type="submit" name="btnUpdate" class="btn btn-primary">Update</button>
                    <a href="index.php" class="btn btn-secondary">Back to List</a>
                </form>
                <!-- End block content -->
            </main>
        </div>
    </div>

    <!-- footer -->
    <?php include_once(__DIR__ . '/../../layouts/partials/footer.php'); ?>
    <!-- end footer -->

    <!-- Include JavaScript management file -->
    <?php include_once(__DIR__ . '/../../layouts/partials/scripts.php'); ?>
</body>

</html>
