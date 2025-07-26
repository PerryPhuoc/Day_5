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
// PHP Logic for Reset Product IDs (TRUNCATE TABLE)
// This logic is similar to what you have in view_accounts.php
// --------------------------------------------------
if (isset($_GET['reset_product_ids']) && $_GET['reset_product_ids'] === 'true') {
    // Disable foreign key checks temporarily
    $conn->query("SET FOREIGN_KEY_CHECKS = 0;");

    // Truncate dependent tables first (e.g., 'cart' and 'order_items' if they exist and reference 'products')
    // The error specifically mentioned 'cart', so we truncate 'cart' first.
    // If you have an 'order_items' table that also references 'products', add it here.
    $truncate_cart_sql = "TRUNCATE TABLE cart";
    $truncate_order_items_sql = "TRUNCATE TABLE order_items"; // Assuming you might have this table

    // Try to truncate cart table
    if ($conn->query($truncate_cart_sql) === TRUE) {
        // Try to truncate order_items table (if it exists)
        // You might want to add a check if the table exists before truncating
        if (@$conn->query($truncate_order_items_sql) === FALSE && $conn->errno != 1146) { // Error 1146 means table doesn't exist
            echo '<script>alert("Error truncating order_items table: ' . $conn->error . '");</script>';
        }

        // Now truncate the products table
        $truncate_products_sql = "TRUNCATE TABLE products";
        if ($conn->query($truncate_products_sql) === TRUE) {
            // Success: Redirect directly
            // $_SESSION['message'] = "All products and related cart/order items deleted and IDs reset successfully.";
        } else {
            // Error truncating products table
            // $_SESSION['error'] = "Error resetting product IDs: " . $conn->error;
            echo '<script>alert("Error resetting product IDs: ' . $conn->error . '");</script>';
        }
    } else {
        // Error truncating cart table
        // $_SESSION['error'] = "Error truncating cart table: " . $conn->error;
        echo '<script>alert("Error truncating cart table: ' . $conn->error . '");</script>';
    }

    // Re-enable foreign key checks
    $conn->query("SET FOREIGN_KEY_CHECKS = 1;");

    // Redirect back to the same page (without GET parameters) to refresh the list
    header('Location: ' . strtok($_SERVER["REQUEST_URI"], '?'));
    exit();
}

// --------------------------------------------------
// Fetch data from 'products' table (existing logic)
// --------------------------------------------------
$sql ="SELECT id, name, price, stock_quantity, image_url, category FROM products ORDER BY id DESC";

// Execute query
$result = $conn->query($sql);

// Get data
$prods = [];
if ($result) {
    while ($row = $result->fetch_array(MYSQLI_NUM)) {
        $prods[] = $row;
    }
    $result->free_result();
} else {
    // Handle query error (optional: add alert here if needed)
    // echo '<script>alert("Error fetching product data: ' . $conn->error . '");</script>';
}
$conn->close();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>

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
                    <h1 class="h2">Product List</h1>
                </div>

                <!-- Block content -->
                <div class="mb-2 d-flex align-items-center">
                    <a href="create.php" class="btn btn-primary mr-2">Create New Product</a>
                    <!-- Form cho nút Reset ID -->
                    <form method="GET" action="" onsubmit="return confirm('WARNING: This will DELETE ALL product data and reset IDs. This action cannot be undone! Are you sure?');">
                        <button type="submit" name="reset_product_ids" value="true" class="btn btn-danger">Reset Product IDs</button>
                    </form>
                </div>

                <table id="tblDanhSach" class="table table-bordered table-hover table-sm table-responsive mt-2">
                    <thead class="thead-dark">
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Image</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prods as $item) : ?>
                            <tr>
                                <td><?= htmlspecialchars($item[0]) ?></td>
                                <td><?= htmlspecialchars($item[1]) ?></td>
                                <td><?= htmlspecialchars($item[2]) ?></td>
                                <td><?= htmlspecialchars($item[3]) ?></td>
                                <td>
                                    <img src="/day4/assets/<?= htmlspecialchars($item[4]) ?>" alt="" style="width:200px;height:auto;"/>
                                </td>
                                <td><?= htmlspecialchars($item[5]) ?></td>
                                <td>
                                    <a href="update.php?id=<?= htmlspecialchars($item['0']) ?>" class="btn btn-warning">Update</a>
                                    <a href="delete.php?id=<?= htmlspecialchars($item['0']) ?>" class="btn btn-danger">Delete</a>
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

    <!-- Nhúng file quản lý phần SCRIPT JAVASCRIPT -->
    <?php include_once(__DIR__ . '/../../layouts/partials/scripts.php'); ?>

    <!-- No custom JavaScript needed for the reset button anymore, as it's handled by form submit -->
</body>

</html>
