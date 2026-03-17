<?php
// Tệp db.php - Xử lý kết nối Database tập trung
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "pharmacity";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Thiết lập font chữ tiếng Việt để không bị lỗi font khi lưu database
mysqli_set_charset($conn, "utf8mb4");

if (!$conn) {
    die("Hệ thống đang bảo trì, lỗi kết nối cơ sở dữ liệu: " . mysqli_connect_error());
}
?>