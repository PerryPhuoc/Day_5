<?php
// Start session if not already started
if (session_id() === '') {
    session_start();
}

// Include database connection file
// Path to dbconnect.php (assuming it's in C:\xampp\htdocs\day4\dbconnect.php)
include_once(__DIR__ . '/../../../dbconnect.php');

header('Content-Type: application/json'); // Thiết lập header để trả về JSON

// --------------------------------------------------
// Process Reset ID action
// --------------------------------------------------

// SQL to truncate the table (deletes all data and resets AUTO_INCREMENT)
$sqlTruncate = "TRUNCATE TABLE products";

if ($conn->query($sqlTruncate) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'All products deleted and IDs reset successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error resetting product IDs: ' . $conn->error]);
}

$conn->close();
exit(); // Đảm bảo không có nội dung nào khác được xuất ra
?>
