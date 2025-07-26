<?php
session_start();

// Display all errors during development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. Include database connection
include_once(__DIR__ . '/../dbconnect.php');

// 2. Prepare the SQL query to select products
$sql = "SELECT id, name, price, description, stock_quantity, image_url, category FROM products";

// 3. Execute the query
$result = $conn->query($sql);

// 4. Fetch data into an associative array for better readability
$products = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    $result->free_result();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DemoShop</title>
    <?php include_once(__DIR__ . '/layouts/styles.php'); ?>
    <style>
        .carousel-item {
            height: 450px;
        }
        .carousel-item img {
            object-fit: cover;
            height: 100%;
        }
        .card-icon {
            width: 120px;
            height: 120px;
            line-height: 120px;
            text-align: center;
            font-size: 52px;
            border-radius: 5px;
            margin-bottom: 15px;
            background-color: #4A90E2;
            color: #FFF;
            margin: 0 auto;
        }
    </style>
</head>
<body class="d-flex flex-column h-100">
    <!-- Header -->
    <?php include_once(__DIR__ . '/layouts/partials/header.php'); ?>
    <!-- End Header -->

    <main role="main" class="mb-2">
        <!-- Carousel - Slider -->
        <div id="myCarousel" class="carousel slide">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="/Day5/assets/uploads/slider/slider1.webp" class="d-block w-100" />
                    <div class="container">
                        <div class="carousel-caption text-start">
                            <h1>A Wonderful Place to Shop.</h1>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="/Day5/assets/uploads/slider/slider2.webp" class="d-block w-100" />
                    <div class="container">
                        <div class="carousel-caption">
                            <h1>Millions of Products. Endless Choices.</h1>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="/Day5/assets/uploads/slider/slider3.webp" class="d-block w-100" />
                    <div class="container">
                        <div class="carousel-caption text-end">
                            <h1>Quality Comes First.</h1>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <!-- Marketing Features -->
        <div class="container marketing mt-5">
            <div class="row">
                <div class="col-lg-4 text-center">
                    <div class="card-icon"><span class="fa fa-credit-card" aria-hidden="true"></span></div>
                    <h2>Place Order</h2>
                    <p>Choose your favorite products and place an order.</p>
                </div>
                <div class="col-lg-4 text-center">
                    <div class="card-icon"><span class="fa fa-archive" aria-hidden="true"></span></div>
                    <h2>Create Order</h2>
                    <p>Track your order status.</p>
                </div>
                <div class="col-lg-4 text-center">
                    <div class="card-icon"><span class="fa fa-truck" aria-hidden="true"></span></div>
                    <h2>Shipping</h2>
                    <p>Fast delivery to your doorstep.</p>
                </div>
            </div>

            <hr class="featurette-divider">
            <div class="row featurette">
                <div class="col-md-7">
                    <h2 class="featurette-heading">Order, Create, and Ship. <span class="text-muted">It's that fast.</span></h2>
                    <p class="lead">The perfect shopping destination for all ages.</p>
                </div>
                <div class="col-md-5">
                    <img src="/Day5/assets/uploads/hinh1.jpg" class="img-fluid" />
                </div>
            </div>

            <hr class="featurette-divider">
            <div class="row featurette">
                <div class="col-md-7 order-md-2">
                    <h2 class="featurette-heading">Great Sales Reports. <span class="text-muted">Track your orders.</span></h2>
                    <p class="lead">A detailed order tracking system, with information available anytime, anywhere.</p>
                </div>
                <div class="col-md-5 order-md-1">
                    <img src="/Day5/assets/uploads/hinh2.jpg" class="img-fluid" />
                </div>
            </div>
            <hr class="featurette-divider">
        </div>

        <!-- Product List Section -->
        <section class="jumbotron text-center">
            <div class="container">
                <h1 class="jumbotron-heading">Product List</h1>
                <p class="lead text-muted">Products with quality, prestige, and commitment from official manufacturers, distributors, and warranty providers.</p>
            </div>
        </section>

        <div class="album py-5 bg-light">
            <div class="container">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    <?php foreach ($products as $product) : ?>
                        <div class="col">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <?php if (!empty($product['image_url'])) : ?>
                                        <a href="/Day5/frontend/pages/details.php?id=<?= $product['id'] ?>">
<img class="bd-placeholder-img card-img-top" style="object-fit: cover; height: 500px;" width="100%" src="/day5/assets/<?= htmlspecialchars($product['image_url']) ?>" />
                                        </a>
                                    <?php else : ?>
                                        <a href="/Day5/frontend/pages/details.php?id=<?= $product['id'] ?>">
                                            <img class="bd-placeholder-img card-img-top" width="100%" height="225" src="/Day5/assets/shared/img/default-image_600.png" />
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <a href="/Day5/frontend/pages/details.php?id=<?= $product['id'] ?>" class="text-decoration-none text-dark">
                                        <h5><?= htmlspecialchars($product['name']) ?></h5>
                                    </a>
                                    <h6><?= htmlspecialchars($product['category']) ?></h6>
                                    <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="btn-group">
                                            <a class="btn btn-sm btn-outline-secondary" href="/Day5/frontend/pages/details.php?id=<?= $product['id'] ?>">View Details</a>
                                        </div>
                                        <small class="text-muted text-end">
                                            <b><?= number_format($product['price'] ) ?> $</b>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php include_once(__DIR__ . '/layouts/partials/footer.php'); ?>
    <!-- End Footer -->

    <!-- Scripts -->
    <?php include_once(__DIR__ . '/layouts/scripts.php'); ?>
</body>
</html>
