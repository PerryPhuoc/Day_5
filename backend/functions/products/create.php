<?php
// Bắt đầu session nếu chưa có, để sử dụng các hàm session như $_SESSION
if (session_id() === '') {
    session_start();
}

// Nhúng file cấu hình chung (nếu cần thiết, ví dụ để lấy tên/tiêu đề trang)
include_once(__DIR__ . '/../../config.php');

// Nhúng file kết nối CSDL
include_once(__DIR__ . '/../../../dbconnect.php'); // Đường dẫn đến dbconnect.php

// --------------------------------------------------
// Xử lý dữ liệu khi Form được SUBMIT
// --------------------------------------------------
if (isset($_POST['btnSave'])) {
    // Lấy dữ liệu người dùng gửi lên qua Form
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $image_url = $_POST['image_url']; // Tạm thời lấy tên file ảnh từ input
    $category = $_POST['category'];

    // THỰC TẾ: Xử lý Upload ảnh tại đây
    // Đây là phần rất quan trọng để quản lý ảnh, tôi sẽ cung cấp một ví dụ đơn giản.
    // Nếu bạn chưa có thư mục 'uploads' trong 'assets', hãy tạo nó.
    // Ví dụ: C:\xampp\htdocs\day4\assets\uploads\

    $uploadDir = __DIR__ . '/../../../assets/uploads/'; // Đường dẫn thư mục lưu trữ ảnh
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Tạo thư mục nếu chưa có
    }

    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image_file']['tmp_name'];
        $fileName = $_FILES['image_file']['name'];
        $fileSize = $_FILES['image_file']['size'];
        $fileType = $_FILES['image_file']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $newFileName = md5(time() . $fileName) . '.' . $fileExtension; // Đổi tên file để tránh trùng lặp
        $destPath = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $image_url = 'uploads/' . $newFileName; // Lưu đường dẫn tương đối vào CSDL
        } else {
            echo "Lỗi khi upload file ảnh.";
            $image_url = ''; // Đặt rỗng nếu upload thất bại
        }
    } else {
        $image_url = ''; // Nếu không có file được chọn
    }


    // Tạo câu lệnh SQL INSERT
    $sqlInsert = "INSERT INTO products (name, price, stock_quantity, image_url, category) VALUES (?, ?, ?, ?, ?)";

    // Sử dụng Prepared Statements để tránh SQL Injection
    $stmt = $conn->prepare($sqlInsert);
    $stmt->bind_param("sdisi", $name, $price, $stock_quantity, $image_url, $category); // "sdisi": string, double, integer, string, integer (thay đổi tùy theo kiểu dữ liệu của bạn)

    if ($stmt->execute()) {
        echo '<script>alert("Thêm sản phẩm thành công!"); window.location.href = "index.php";</script>';
    } else {
        echo '<script>alert("Lỗi khi thêm sản phẩm: ' . $stmt->error . '");</script>';
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
    <title>Thêm Sản Phẩm Mới</title>

    <?php include_once(__DIR__ . '/../../layouts/partials/head.php'); ?>
</head>

<body class="d-flex flex-column h-100">
    <?php include_once(__DIR__ . '/../../layouts/partials/header.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <?php include_once(__DIR__ . '/../../layouts/partials/sidebar.php'); ?>
            <main role="main" class="col-md-10 ml-sm-auto px-4 mb-2">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Thêm Sản Phẩm Mới</h1>
                </div>

                <form action="" method="post" name="frmCreate" id="frmCreate" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                    </div>
                    <div class="form-group">
                        <label for="stock_quantity">Quantity</label>
                        <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="image_file">Image</label>
                        <input type="file" class="form-control-file" id="image_file" name="image_file">
                        <small class="form-text text-muted">Choose image product (image .JPG ).</small>
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <input type="text" class="form-control" id="category" name="category" required>
                    </div>
                    <button type="submit" name="btnSave" class="btn btn-primary">Lưu</button>
                    <a href="index.php" class="btn btn-secondary">Quay về danh sách</a>
                </form>
                </main>
        </div>
    </div>

    <?php include_once(__DIR__ . '/../../layouts/partials/footer.php'); ?>
    <?php include_once(__DIR__ . '/../../layouts/partials/scripts.php'); ?>
</body>

</html>