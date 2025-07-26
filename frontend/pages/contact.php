<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên hệ - MyShop</title>
    <?php include_once(__DIR__ . '/../layouts/styles.php'); ?>
</head>
<body class="d-flex flex-column h-100">
    <?php include_once(__DIR__ . '/../layouts/styles.php'); ?>
<?php include_once(__DIR__ . '/../layouts/partials/header.php'); ?>

<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4 text-success">Liên Hệ Với Chúng Tôi</h2>
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Họ tên</label>
                            <input type="text" class="form-control" id="name" placeholder="Nhập họ tên của bạn">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Địa chỉ Email</label>
                            <input type="email" class="form-control" id="email" placeholder="email@example.com">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Nội dung</label>
                            <textarea class="form-control" id="message" rows="5" placeholder="Viết tin nhắn..."></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success px-5">Gửi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once(__DIR__ . '/../layouts/partials/footer.php'); ?>
<?php include_once(__DIR__ . '/../layouts/scripts.php'); ?>

</body>
</html>
