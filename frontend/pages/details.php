<?php
session_start();

// --- DATABASE & DATA FETCHING ---
include_once(__DIR__ . '/../../dbconnect.php');

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = null;

if ($product_id > 0) {
    // SECURITY FIX: Use prepared statements to prevent SQL Injection
    $sql = "SELECT id, name, price, description, stock_quantity, image_url, category FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Use fetch_assoc for readable associative array
    $product = $result->fetch_assoc();
    
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $product ? htmlspecialchars($product['name']) : 'Product Not Found' ?></title>
    <?php include_once(__DIR__ . '/../layouts/styles.php'); ?>
    <style>
        .preview-pic img {
            max-width: 100%;
            max-height: 400px;
            object-fit: contain;
        }
        .preview-thumbnail.nav-tabs {
            border: none;
            margin-top: 15px;
        }
        .preview-thumbnail.nav-tabs li {
            margin: 0 5px;
        }
        .preview-thumbnail.nav-tabs img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            cursor: pointer;
        }
        .details {
            display: flex;
            flex-direction: column;
        }
        .add-to-cart, .like {
            background: #ff9f1a;
            border: none;
            color: #fff;
            transition: background .3s ease;
        }
        .add-to-cart:hover, .like:hover {
            background: #b36800;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include_once(__DIR__ . '/../layouts/partials/header.php'); ?>

<main role="main" class="container d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 160px); padding: 40px 0;">
        <!-- Alert for notifications -->
        <div id="alert-container"></div>

        <?php if ($product): // Check if product exists ?>
        <div class="card">
            <div class="row">
                <div class="col-md-6 preview">
                    <div class="preview-pic tab-content">
                        <div class="tab-pane active" id="pic-1">
<img class="border rounded" style="border: 1px solid #dee2e6; padding: 5px; max-height: 450px; object-fit: contain;" src="<?= empty($product['image_url']) ? '/day5/assets/shared/img/product1.jpg' : '/day5/assets/' . htmlspecialchars($product['image_url']) ?>" />
                        </div>
                        <!-- Add more tab-panes here for more images if available -->
                    </div>
                    <!-- Thumbnails for more images can be added here -->
                </div>
                <div class="col-md-6 details">
                    <h3 class="product-title"><?= htmlspecialchars($product['name']) ?></h3>
                    <div class="rating mb-2">
                        <div class="stars">
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star"></span>
                        </div>
                        <span class="review-no">41 reviews</span>
                    </div>
                    <p class="product-description"><?= htmlspecialchars($product['description']) ?></p>
                    <h4 class="price">Price: <span>$<?= number_format($product['price'], 2) ?></span></h4>
                    
                    <div class="form-group mb-3" style="max-width: 150px;">
                        <label for="quantity">Quantity:</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" max="<?= $product['stock_quantity'] ?>" value="1">
                    </div>
                    
                    <div class="action">
                        <!-- LOGIC FIX: Add hidden inputs to store product data for JS -->
                        <input type="hidden" id="product_id" value="<?= $product['id'] ?>" />
                        <input type="hidden" id="product_name" value="<?= htmlspecialchars($product['name']) ?>" />
                        <input type="hidden" id="product_price" value="<?= $product['price'] ?>" />
                        <input type="hidden" id="product_image" value="<?= htmlspecialchars($product['image_url']) ?>" />

                        <button class="add-to-cart btn btn-lg" type="button">Add to Cart</button>
                        <button class="like btn btn-lg btn-outline-secondary" type="button"><span class="fa fa-heart"></span></button>
                    </div>
                </div>
            </div>
        </div>
        <?php else: // If product does not exist ?>
            <div class="alert alert-danger text-center" role="alert">
                <h2>Product Not Found</h2>
                <p>The product you are looking for does not exist or has been removed.</p>
                <a href="/day4/frontend/index.php" class="btn btn-primary">Back to Homepage</a>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <?php include_once(__DIR__ . '/../layouts/partials/footer.php'); ?>
    
    <!-- Scripts -->
    <?php include_once(__DIR__ . '/../layouts/scripts.php'); ?>
    
    <script>
    $(document).ready(function() {
        // Function to add item to cart via AJAX
        function handleAddCart() {
            // Get data from the hidden input fields
            const productData = {
                id: $('#product_id').val(),
                name: $('#product_name').val(),
                price: $('#product_price').val(),
                image: $('#product_image').val(),
                quantity: $('#quantity').val(),
            };

            $.ajax({
                url: '/day4/frontend/api/addCart.php',
                method: "POST",
                dataType: 'json',
                data: productData,
                success: function(response) {
                    const alertHtml = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Product added to cart. <a href="/day4/frontend/pages/view_cart.php" class="alert-link">View Cart</a>.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
                    $('#alert-container').html(alertHtml);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    const errorHtml = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> Could not add product to cart. Please try again.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
                    $('#alert-container').html(errorHtml);
                }
            });
        }

        // Attach click event to the "Add to Cart" button
        $('.add-to-cart').on('click', function(event) {
            event.preventDefault();
            handleAddCart();
        });
    });
    </script>
</body>
</html>
