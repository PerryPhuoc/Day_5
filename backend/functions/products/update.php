<?php
// Bắt đầu session
if (session_id() === '') {
    session_start();
}

// Nhúng file cấu hình chung
include_once(__DIR__ . '/../../config.php');

// Nhúng file kết nối CSDL
include_once(__DIR__ . '/../../../dbconnect.php'); // Đường dẫn đến dbconnect.php

$id = $_GET['id']; // Lấy ID sản phẩm từ URL

// Kiểm tra nếu không có ID hoặc ID không hợp lệ
if (!isset($id) || !is_numeric($id)) {
    echo '<script>alert("ID sản phẩm không hợp lệ!"); window.location.href = "index.php";</script>';
    exit();
}

// --------------------------------------------------
// Lấy dữ liệu sản phẩm hiện tại để hiển thị lên Form
// --------------------------------------------------
$sqlSelect = "SELECT id, name, price, stock_quantity, image_url, category FROM products WHERE id = ?";
$stmtSelect = $conn->prepare($sqlSelect);
$stmtSelect->bind_param("i", $id);
$stmtSelect->execute();
$resultSelect = $stmtSelect->get_result();
$product = $resultSelect->fetch_assoc();

// Nếu không tìm thấy sản phẩm
if (!$product) {
    echo '<script>alert("Không tìm thấy sản phẩm với ID này!"); window.location.href = "index.php";</script>';
    exit();
}
$stmtSelect->close();

// --------------------------------------------------
// Xử lý dữ liệu khi Form được SUBMIT (Cập nhật)
// --------------------------------------------------
if (isset($_POST['btnUpdate'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $category = $_POST['category'];
    $current_image_url = $_POST['current_image_url']; // Lấy đường dẫn ảnh hiện tại

    // Xử lý upload ảnh mới
    $new_image_url = $current_image_url; // Mặc định giữ ảnh cũ
    $uploadDir = __DIR__ . '/../../../assets/uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image_file']['tmp_name'];
        $fileName = $_FILES['image_file']['name'];
        $fileSize = $_FILES['image_file']['size'];
        $fileType = $_FILES['image_file']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $destPath = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            // Xóa ảnh cũ nếu có và ảnh cũ không phải là ảnh mặc định (nếu có)
            if (!empty($current_image_url) && file_exists($uploadDir . $current_image_url)) {
                 unlink($uploadDir . $current_image_url);
            }
            $new_image_url = 'uploads/' . $newFileName;
        } else {
            echo "Lỗi khi upload file ảnh mới.";
        }
    }

    // Tạo câu lệnh SQL UPDATE
    $sqlUpdate = "UPDATE products SET name = ?, price = ?, stock_quantity = ?, image_url = ?, category = ? WHERE id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
$stmtUpdate->bind_param("sssssi", $name, $price, $stock_quantity, $new_image_url, $category, $id);

    if ($stmtUpdate->execute()) {
        echo '<script>alert("Cập nhật sản phẩm thành công!"); window.location.href = "index.php";</script>';
    } else {
        echo '<script>alert("Lỗi khi cập nhật sản phẩm: ' . $stmtUpdate->error . '");</script>';
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
    <title>Cập Nhật Sản Phẩm</title>

    <?php include_once(__DIR__ . '/../../layouts/partials/head.php'); ?>
</head>

<body class="d-flex flex-column h-100">
    <?php include_once(__DIR__ . '/../../layouts/partials/header.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <?php include_once(__DIR__ . '/../../layouts/partials/sidebar.php'); ?>
            <main role="main" class="col-md-10 ml-sm-auto px-4 mb-2">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Cập Nhật Sản Phẩm</h1>
                </div>

                <form action="" method="post" name="frmUpdate" id="frmUpdate" enctype="multipart/form-data">
                    <input type="hidden" name="current_image_url" value="<?= htmlspecialchars($product['image_url']); ?>">
                    <div class="form-group">
                        <label for="name">Tên Sản Phẩm:</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($product['name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Giá:</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?= htmlspecialchars($product['price']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="stock_quantity">Số Lượng Tồn Kho:</label>
                        <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" value="<?= htmlspecialchars($product['stock_quantity']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="image_file">Ảnh Sản Phẩm Hiện Tại:</label>
                        <?php if (!empty($product['image_url'])) : ?>
                            <img src="/day4/assets/<?= htmlspecialchars($product['image_url']); ?>" alt="Ảnh sản phẩm" style="width: 150px; height: auto; display: block; margin-bottom: 10px;">
                        <?php else : ?>
                            <p>Chưa có ảnh</p>
                        <?php endif; ?>
                        <input type="file" class="form-control-file" id="image_file" name="image_file">
                        <small class="form-text text-muted">Chọn ảnh mới để thay thế (để trống nếu không muốn thay đổi).</small>
                    </div>
                    <div class="form-group">
                        <label for="category">Danh Mục:</label>
                        <input type="text" class="form-control" id="category" name="category" value="<?= htmlspecialchars($product['category']); ?>" required>
                    </div>
                    <button type="submit" name="btnUpdate" class="btn btn-primary">Cập Nhật</button>
                    <a href="index.php" class="btn btn-secondary">Quay về danh sách</a>
                </form>
                </main>
        </div>
    </div>

    <?php include_once(__DIR__ . '/../../layouts/partials/footer.php'); ?>
    <?php include_once(__DIR__ . '/../../layouts/partials/scripts.php'); ?>
</body>

</html>