<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - MyShop</title>
    <?php include_once(__DIR__ . '/../layouts/styles.php'); ?>
</head>
<body class="d-flex flex-column h-100">
    <?php include_once(__DIR__ . '/../layouts/styles.php'); ?>
<?php include_once(__DIR__ . '/../layouts/partials/header.php'); ?>

<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-5">
                    <h1 class="mb-4 text-center text-primary">Về Chúng Tôi</h1>
                    <p class="fs-5 text-muted text-center">
                        Chào mừng bạn đến với <strong>MyShop</strong> – nơi cung cấp những sản phẩm chất lượng với giá cả hợp lý.
                    </p>
                    <p class="text-justify">
                        Chúng tôi là một đội ngũ trẻ đam mê công nghệ và thương mại điện tử. Với mục tiêu đem lại trải nghiệm mua sắm tốt nhất cho khách hàng,
                        <strong>MyShop</strong> luôn nỗ lực cải tiến dịch vụ mỗi ngày, từ sản phẩm đến hỗ trợ khách hàng.
                    </p>
                    <p class="text-justify">
                        Chúng tôi tin rằng sự tin tưởng của bạn là nền tảng vững chắc để chúng tôi phát triển. Cảm ơn bạn đã đồng hành cùng chúng tôi!
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once(__DIR__ . '/../layouts/partials/footer.php'); ?>
<?php include_once(__DIR__ . '/../layouts/scripts.php'); ?>

</body>
</html>
