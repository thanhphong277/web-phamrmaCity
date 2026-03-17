<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaCity - Nền tảng y tế số</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <header>
        <div class="container navbar">
            <div class="logo">
                <a href="index.php" style="text-decoration: none; color: inherit;">
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
                    <button id="cart-btn" class="btn btn-outline">
                        <i class="fa-solid fa-cart-shopping"></i> Giỏ hàng
                        <span id="cart-count">0</span>
                    </button>

                    <div class="user-menu">
                        <button class="btn-user">
                            <i class="fa-solid fa-user-circle"></i> Xin chào, <?php echo $_SESSION['ten_khach_hang']; ?> <i class="fa-solid fa-caret-down"></i>
                        </button>
                        <div class="dropdown-content">
                            <?php if(isset($_SESSION['quyen_han']) && $_SESSION['quyen_han'] == 1): ?>
                                <a href="admin.php"><i class="fa-solid fa-gauge"></i> Trang Quản Trị Admin</a>
                            <?php else: ?>
                                <a href="hoso_khachhang.php"><i class="fa-solid fa-id-card"></i> Hồ sơ của tôi</a>
                            <?php endif; ?>
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