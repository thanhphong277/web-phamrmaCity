<?php 
session_start(); 

// 1. KIỂM TRA QUYỀN TRUY CẬP
if (!isset($_SESSION['ten_khach_hang']) || $_SESSION['quyen_han'] == 0) {
    header("Location: index.php");
    exit();
}

// 2. KẾT NỐI DATABASE
require_once 'db.php';
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

$ten_admin = $_SESSION['ten_khach_hang'];
$thongbao = "";

// 3. XỬ LÝ KHI BẤM NÚT CẬP NHẬT
if(isset($_POST['cap_nhat_ho_so'])) {
    $ten_moi = $_POST['fullname'];
    $mat_khau_moi = $_POST['new_password'];
    
    if(!empty($mat_khau_moi)) {
        $hashed_password = password_hash($mat_khau_moi, PASSWORD_DEFAULT);
        $sql_update = "UPDATE users SET fullname='$ten_moi', password='$hashed_password' WHERE username='$ten_admin'";
    } else {
        $sql_update = "UPDATE users SET fullname='$ten_moi' WHERE username='$ten_admin'";
    }
    
    if(mysqli_query($conn, $sql_update)) {
        $thongbao = "<div class='alert' style='color: #059669; background: #d1fae5; padding: 15px; border-radius: 8px; margin-bottom: 20px;'><i class='fa-solid fa-circle-check'></i> Cập nhật hồ sơ thành công!</div>";
    } else {
        $thongbao = "<div class='alert' style='color: #dc2626; background: #fee2e2; padding: 15px; border-radius: 8px; margin-bottom: 20px;'><i class='fa-solid fa-triangle-exclamation'></i> Lỗi cập nhật: " . mysqli_error($conn) . "</div>";
    }
}

// 4. LẤY DỮ LIỆU HIỂN THỊ
$sql = "SELECT * FROM users WHERE username = '$ten_admin' LIMIT 1";
$result = mysqli_query($conn, $sql);
$admin_info = mysqli_fetch_assoc($result);
if(!$admin_info) {
    $admin_info = ['fullname' => 'Không xác định', 'username' => $ten_admin, 'role' => 1];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ của tôi - PharmaCity</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="hehe.css">
    <style>
        body { background-color: #f3f4f6; }
        .profile-wrapper { max-width: 1000px; margin: 50px auto; display: flex; gap: 30px; }
        
        /* Cột bên trái: Ảnh đại diện */
        .profile-sidebar { flex: 1; background: white; padding: 40px 20px; border-radius: 12px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.05); height: fit-content; }
        .avatar-circle { width: 150px; height: 150px; background: #e0f2fe; color: #19a695; font-size: 80px; display: flex; align-items: center; justify-content: center; border-radius: 50%; margin: 0 auto 20px auto; border: 4px solid #19a695; }
        .admin-name { font-size: 22px; font-weight: bold; margin-bottom: 5px; color: #111827; }
        .admin-role { background: #fee2e2; color: #dc2626; padding: 5px 15px; border-radius: 20px; font-size: 14px; font-weight: 600; display: inline-block; }
        
        /* Cột bên phải: Form nhập liệu */
        .profile-main { flex: 2; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .profile-main h2 { margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid #f3f4f6; color: #111827; }
        .form-group-pro { margin-bottom: 25px; }
        .form-group-pro label { display: block; font-weight: 600; margin-bottom: 8px; color: #374151; }
        .form-group-pro input { width: 100%; padding: 12px 15px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 16px; transition: border-color 0.3s; }
        .form-group-pro input:focus { outline: none; border-color: #19a695; box-shadow: 0 0 0 3px rgba(25, 166, 149, 0.2); }
        .form-group-pro input:disabled { background-color: #f3f4f6; cursor: not-allowed; color: #6b7280; }
        .btn-save { background-color: #19a695; color: white; border: none; padding: 14px 30px; font-size: 16px; font-weight: 600; border-radius: 8px; cursor: pointer; width: 100%; transition: background-color 0.3s; }
        .btn-save:hover { background-color: #128072; }
    </style>
</head>
<body>

    <header>
        <div class="container navbar">
            <div class="logo">
                <i class="fa-solid fa-heart-pulse"></i> PharmaCity (Admin)
            </div>
            
            <nav class="nav-links" style="position: relative; z-index: 9999;">
                <a href="admin.php?page=products"><i class="fa-solid fa-boxes-stacked"></i> Sản phẩm</a>
                <a href="admin.php?page=orders"><i class="fa-solid fa-file-invoice-dollar"></i> Đơn hàng</a>
                <a href="hoso_admin.php" class="active"><i class="fa-solid fa-address-card"></i> Hồ sơ của tôi</a>
            </nav>

            <div class="auth-buttons" style="position: relative; z-index: 1;">
                <div class="user-menu">
                    <button class="btn-user">
                        <i class="fa-solid fa-user-shield"></i> Trở về <i class="fa-solid fa-caret-down"></i>
                    </button>
                    <div class="dropdown-content">
                        <a href="admin.php"><i class="fa-solid fa-gauge"></i> Về Bảng điều khiển</a>
                        <a href="trangchu.php" class="logout-text"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="profile-wrapper">
        
        <div class="profile-sidebar">
            <div class="avatar-circle">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <div class="admin-name"><?php echo $admin_info['fullname']; ?></div>
            <div class="admin-role"><i class="fa-solid fa-crown"></i> Quản trị viên tối cao</div>
            <p style="color: #6b7280; font-size: 14px; margin-top: 15px;">Thành viên từ: 2026</p>
        </div>

        <div class="profile-main">
            <h2><i class="fa-solid fa-pen-to-square"></i> Cập nhật thông tin</h2>
            
            <?php echo $thongbao; ?>

            <form action="hoso_admin.php" method="POST">
                <div class="form-group-pro">
                    <label>Tên đăng nhập (Username):</label>
                    <input type="text" value="<?php echo $admin_info['username']; ?>" disabled title="Tên đăng nhập không thể thay đổi">
                </div>

                <div class="form-group-pro">
                    <label>Họ và tên hiển thị:</label>
                    <input type="text" name="fullname" value="<?php echo $admin_info['fullname']; ?>" required placeholder="Nhập tên mới của bạn...">
                </div>

                <div class="form-group-pro">
                    <label>Đổi mật khẩu mới (Tùy chọn):</label>
                    <input type="password" name="new_password" placeholder="Bỏ trống nếu không muốn đổi mật khẩu...">
                </div>

                <button type="submit" name="cap_nhat_ho_so" class="btn-save">
                    <i class="fa-solid fa-floppy-disk"></i> Lưu Thay Đổi
                </button>
            </form>
        </div>

    </main>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const nutUser = document.querySelector(".btn-user");
        const menuXoXuong = document.querySelector(".dropdown-content");
        if (nutUser && menuXoXuong) {
            nutUser.addEventListener("click", function(e) { e.stopPropagation(); menuXoXuong.classList.toggle("show"); });
            window.addEventListener("click", function(e) { if (!menuXoXuong.contains(e.target) && !nutUser.contains(e.target)) menuXoXuong.classList.remove("show"); });
        }
    });
    </script>
</body>
</html>