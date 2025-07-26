<?php
// Start session if not already started
if (session_id() === '') {
    session_start();
}

// Include general configuration file
include_once(__DIR__ . '/../../config.php');

// Include database connection file
// Path to dbconnect.php (assuming it's in C:\xampp\htdocs\day4\dbconnect.php)
include_once(__DIR__ . '/../../../dbconnect.php');

// --------------------------------------------------
// PHP Logic for Reset User IDs (TRUNCATE TABLE)
// This logic will delete all users and reset IDs in the database.
// It also handles foreign key constraints by truncating dependent tables first.
// --------------------------------------------------
if (isset($_GET['reset_user_ids']) && $_GET['reset_user_ids'] === 'true') {
    // Disable foreign key checks temporarily
    $conn->query("SET FOREIGN_KEY_CHECKS = 0;");

    // Truncate dependent tables first if they reference 'users'
    // Common dependent tables might be 'orders' and 'cart' if they have a user_id foreign key.
    $truncate_orders_sql = "TRUNCATE TABLE orders"; // Assuming 'orders' table
    $truncate_cart_sql = "TRUNCATE TABLE cart";     // Assuming 'cart' table

    $success = true; // Flag to track overall success

    // Try to truncate orders table
    if ($conn->query($truncate_orders_sql) === TRUE) {
        // Try to truncate cart table
        if ($conn->query($truncate_cart_sql) === TRUE) {
            // Now truncate the users table
            $truncate_users_sql = "TRUNCATE TABLE users";
            if ($conn->query($truncate_users_sql) === TRUE) {
                // Success: Redirect directly to refresh the list
            } else {
                // Error truncating users table
                echo '<script>alert("Error resetting user IDs: ' . $conn->error . '");</script>';
                $success = false;
            }
        } else {
            // Error truncating cart table
            echo '<script>alert("Error truncating cart table: ' . $conn->error . '");</script>';
            $success = false;
        }
    } else {
        // Error truncating orders table
        echo '<script>alert("Error truncating orders table: ' . $conn->error . '");</script>';
        $success = false;
    }

    // Re-enable foreign key checks
    $conn->query("SET FOREIGN_KEY_CHECKS = 1;");

    // Redirect back to the same page (without GET parameters) to refresh the list
    header('Location: ' . strtok($_SERVER["REQUEST_URI"], '?'));
    exit();
}

// --------------------------------------------------
// Fetch data from 'users' table
// --------------------------------------------------
$sql = "SELECT id, username, email, role FROM users ORDER BY id DESC";

// Execute query
$result = $conn->query($sql);

// Get data
$users = [];
if ($result) {
    while ($row = $result->fetch_array(MYSQLI_NUM)) {
        $users[] = $row;
    }
    $result->free_result();
} else {
    // Handle query error (optional: add alert here if needed)
    // echo '<script>alert("Error fetching user data: ' . $conn->error . '");</script>';
}
$conn->close();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>

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
                    <h1 class="h2">User List</h1>
                </div>

                <!-- Block content -->
                <div class="mb-2 d-flex align-items-center">
                    <a href="create.php" class="btn btn-primary mr-2">Create New User</a>
                    <!-- Form cho nút Reset ID -->
                    <form method="GET" action="" onsubmit="return confirm('WARNING: This will DELETE ALL user data and reset IDs. This action cannot be undone! Are you sure?');">
                        <button type="submit" name="reset_user_ids" value="true" class="btn btn-danger">Reset User IDs</button>
                    </form>
                </div>

                <table id="tblDanhSach" class="table table-bordered table-hover table-sm table-responsive mt-2">
                    <thead class="thead-dark">
                        <tr>
                            <th>Id</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $item) : ?>
                            <tr>
                                <td><?= htmlspecialchars($item[0]) ?></td>
                                <td><?= htmlspecialchars($item[1]) ?></td>
                                <td><?= htmlspecialchars($item[2]) ?></td>
                                <td><?= htmlspecialchars($item[3]) ?></td>
                                <td>
                                    <a href="update.php?id=<?= htmlspecialchars($item[0]) ?>" class="btn btn-warning">Update</a>
                                    <a href="delete.php?id=<?= htmlspecialchars($item[0]) ?>" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <!-- End block content -->
            </main>
        </div>
    </div>

    <!-- footer -->
    <?php include_once(__DIR__ . '/../../layouts/partials/footer.php'); ?>
    <!-- end footer -->

    <!-- Include JavaScript management file -->
    <?php include_once(__DIR__ . '/../../layouts/partials/scripts.php'); ?>

    <!-- No custom JavaScript needed for the reset button anymore, as it's handled by form submit -->
</body>

</html>
