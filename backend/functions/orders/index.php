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
// Fetch data from 'orders' table by joining with 'users' table
// --------------------------------------------------
// We select columns from 'orders' (aliased as 'o') and 'users' (aliased as 'u')
// 'u.username' is selected and aliased as 'customer_name' to match previous usage
$sql = "SELECT o.id, o.order_date, o.total_amount, u.username AS customer_name, o.status, o.shipping_address
        FROM orders AS o
        JOIN users AS u ON o.user_id = u.id
        ORDER BY o.id DESC";

// Execute query
$result = $conn->query($sql);

// Get data
$orders = [];
if ($result) {
    while ($row = $result->fetch_array(MYSQLI_NUM)) {
        $orders[] = $row;
    }
    $result->free_result();
} else {
    // Handle query error with SweetAlert2
    echo '<script>';
    echo 'Swal.fire({';
    echo '    title: "Error!",';
    echo '    text: "Error fetching order data: ' . $conn->error . '",';
    echo '    icon: "error",';
    echo '    confirmButtonText: "Close"';
    echo '});';
    echo '</script>';
}
$conn->close();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>

    <!-- Include common CSS/JS from layout -->
    <?php include_once(__DIR__ . '/../../layouts/partials/head.php'); ?>
</head>

<body class="d-flex flex-column h-100">
    <!-- header -->
    <?php include_once(__DIR__ . '/../../layouts/partials/header.php'); ?>
    <!-- end header -->

    <div class="container-fluid">
        <div class="row">
            <!-- sidebar -->
            <?php include_once(__DIR__ . '/../../layouts/partials/sidebar.php'); ?>
            <!-- end sidebar -->

<main role="main" class="col-md-10 ml-sm-auto px-4 mb-2 flex-grow-1">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Order List</h1>
                </div>

                <!-- Block content -->
                <a href="create.php" class="btn btn-primary">Create New Order</a>
                <table id="tblDanhSach" class="table table-bordered table-hover table-sm table-responsive mt-2">
                    <thead class="thead-dark">
                        <tr>
                            <th>Id</th>
                            <th>Order Date</th>
                            <th>Total Amount</th>
                            <th>Customer Name</th>
                            <th>Status</th>
                            <th>Shipping Address</th> <!-- Added Shipping Address column -->
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $item) : ?>
                            <tr>
                                <td><?= htmlspecialchars($item[0]) ?></td>
                                <td><?= htmlspecialchars($item[1]) ?></td>
                                <td><?= htmlspecialchars($item[2]) ?></td>
                                <td><?= htmlspecialchars($item[3]) ?></td> <!-- This will be customer_name -->
                                <td><?= htmlspecialchars($item[4]) ?></td>
                                <td><?= htmlspecialchars($item[5]) ?></td> <!-- This will be shipping_address -->
                                <td>
                                    <a href="detail.php?id=<?= htmlspecialchars($item[0]) ?>" class="btn btn-info">View Details</a>
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
</body>

</html>
