<?php
// Bắt đầu session
if (session_id() === '') {
    session_start();
}

// Nhúng file kết nối CSDL
include_once(__DIR__ . '/../../../dbconnect.php'); // Đường dẫn đến dbconnect.php

$id = $_GET['id']; // Lấy ID sản phẩm từ URL

// Kiểm tra nếu không có ID hoặc ID không hợp lệ
if (!isset($id) || !is_numeric($id)) {
    echo '<script>alert("ID sản phẩm không hợp lệ!"); window.location.href = "index.php";</script>';
    exit();
}

// --------------------------------------------------
// Lấy thông tin ảnh để xóa file ảnh vật lý trước khi xóa bản ghi trong CSDL
// --------------------------------------------------
$sqlSelectImage = "SELECT image_url FROM products WHERE id = ?";
$stmtSelectImage = $conn->prepare($sqlSelectImage);
$stmtSelectImage->bind_param("i", $id);
$stmtSelectImage->execute();
$resultSelectImage = $stmtSelectImage->get_result();
$productImage = $resultSelectImage->fetch_assoc();
$stmtSelectImage->close();

$image_to_delete = '';
if ($productImage && !empty($productImage['image_url'])) {
    $image_to_delete = __DIR__ . '/../../../assets/' . $productImage['image_url'];
}

// --------------------------------------------------
// Xóa sản phẩm khỏi CSDL
// --------------------------------------------------
$sqlDelete = "DELETE FROM products WHERE id = ?";
$stmtDelete = $conn->prepare($sqlDelete);
$stmtDelete->bind_param("i", $id);

if ($stmtDelete->execute()) {
    // Nếu xóa thành công bản ghi trong CSDL, tiến hành xóa file ảnh vật lý
    if (!empty($image_to_delete) && file_exists($image_to_delete)) {
        unlink($image_to_delete); // Xóa file ảnh
    }
    echo '<script>alert("Xóa sản phẩm thành công!"); window.location.href = "index.php";</script>';
} else {
    echo '<script>alert("Lỗi khi xóa sản phẩm: ' . $stmtDelete->error . '");</script>';
}

$stmtDelete->close();
$conn->close();
?>