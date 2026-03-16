<?php 
session_start(); 

// Nếu chưa có thẻ VIP, HOẶC có thẻ nhưng quyền hạn = 0 (là khách)
if (!isset($_SESSION['ten_khach_hang']) || $_SESSION['quyen_han'] == 0) {
    // Đuổi cổ ra ngoài trang chủ ngay lập tức!
    header("Location: index.php");
    exit();
}
?>
