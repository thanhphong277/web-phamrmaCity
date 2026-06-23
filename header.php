<?php 
// Đảm bảo session_start() luôn ở dòng đầu tiên của file index.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaCity - Nền tảng y tế số</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> 
    <style>
        /* Thêm CSS để Dropdown hoạt động mượt mà */
        .user-menu { position: relative; display: inline-block; }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #fff;
            min-width: 200px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.1);
            z-index: 1000;
            border-radius: 8px;
            padding: 10px 0;
        }
        .dropdown-content a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: 0.2s;
        }
        .dropdown-content a:hover { background-color: #f1f1f1; color: #00b36b; }
        .show { display: block !important; }
        .logout-text { color: #ff4d4d !important; }
    </style>
</head>
<body>
    <header>
        <div class="container navbar">
            <div class="logo">
                <a href="index.php" style="text-decoration: none; color: #00b36b; font-weight: bold; font-size: 24px;">
                    <i class="fa-solid fa-heart-pulse"></i> PharmaCity
                </a>
            </div>
            
            <nav class="nav-links">
                <a href="index.php"><i class="fa-solid fa-wave-square"></i> Trang chủ</a>
                <a href="<?php echo isset($_SESSION['ten_khach_hang']) ? 'cuahang.php' : 'cuahang_khach.php'; ?>">
                    <i class="fa-solid fa-bag-shopping"></i> Nhà thuốc
                </a>
            </nav>

            <div class="auth-buttons">
                <?php if(isset($_SESSION['ten_khach_hang'])): ?>
                    <button id="cart-btn" class="btn btn-outline" onclick="location.href='giohang.php'">
                        <i class="fa-solid fa-cart-shopping"></i> Giỏ hàng
                        <span id="cart-count">0</span>
                    </button>

                    <div class="user-menu">
                        <button class="btn-user" id="userBtn">
                            <i class="fa-solid fa-user-circle"></i> Xin chào, <?php echo htmlspecialchars($_SESSION['ten_khach_hang']); ?> <i class="fa-solid fa-caret-down"></i>
                        </button>
                        <div class="dropdown-content" id="userDropdown">
                            <a href="profile.php"><i class="fa-solid fa-id-card"></i> Hồ sơ của tôi</a>
                            <a href="donmua.php"><i class="fa-solid fa-clock-rotate-left"></i> Lịch sử mua hàng</a>
                            <hr>
                            <a href="dangxuat.php" class="logout-text"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
                        </div>
                    </div>

                <?php else: ?>
                    <a href="dangnhap.php" class="btn btn-outline"><i class="fa-solid fa-right-to-bracket"></i> Đăng nhập</a>
                    <a href="dangky.php" class="btn btn-primary"><i class="fa-solid fa-user-plus"></i> Đăng ký</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <script>
        // JavaScript xử lý đóng mở Menu người dùng
        const userBtn = document.getElementById("userBtn");
        const userDropdown = document.getElementById("userDropdown");

        if (userBtn) {
            userBtn.addEventListener("click", function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle("show");
            });
        }

        // Click ra ngoài thì đóng menu
        window.onclick = function(e) {
            if (userDropdown && !userBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.classList.remove("show");
            }
        };
    </script>
</body>
</html>