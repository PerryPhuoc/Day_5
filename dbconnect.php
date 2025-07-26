<?php
$servername = "localhost";
$username = "root"; // Tên người dùng MySQL của bạn
$password = "";     // Mật khẩu MySQL của bạn (thường là rỗng với XAMPP)
$dbname = "Myshop"; // Tên cơ sở dữ liệu của bạn

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully"; // Có thể bỏ dòng này sau khi test
?>