<?php
// Start session if not already started
if (session_id() === '') {
    session_start();
}

// Include general configuration file
include_once(__DIR__ . '/../../config.php');

// Include database connection file
include_once(__DIR__ . '/../../../dbconnect.php');

// --------------------------------------------------
// Process data when Form is SUBMITTED
// --------------------------------------------------
if (isset($_POST['btnSave'])) {
    // Get data submitted via Form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Hash the password before storing it in the database for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Create SQL INSERT statement
    $sqlInsert = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";

    // Use Prepared Statements to prevent SQL Injection
    $stmt = $conn->prepare($sqlInsert);
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        // Success: Redirect directly without a pop-up message
        echo '<script>window.location.href = "index.php";</script>';
    } else {
        // Error: Show a basic browser alert
        echo '<script>alert("Error adding user: ' . $stmt->error . '");</script>';
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>

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
                    <h1 class="h2">Add New User</h1>
                </div>

                <!-- Block content -->
                <form action="" method="post" name="frmCreate" id="frmCreate">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role:</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" name="btnSave" class="btn btn-primary">Save</button>
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
