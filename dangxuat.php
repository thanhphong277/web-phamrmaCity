<?php
// Bắt đầu phiên làm việc để tìm thấy thông tin đang đăng nhập
session_start();

// Xóa sạch mọi thông tin của khách hàng/admin khỏi bộ nhớ
session_unset();
session_destroy();

// Đá người dùng về lại trang chủ sau khi đăng xuất xong
header("Location: index.php");
exit();
?>