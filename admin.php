<?php 
session_start(); 

// 1. KIỂM TRA QUYỀN TRUY CẬP
if (!isset($_SESSION['ten_khach_hang']) || $_SESSION['quyen_han'] == 0) {
    header("Location: index.php");
    exit();
}

// 2. KẾT NỐI DATABASE
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "pharmacity";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// Lấy trang hiện tại (mặc định là trang sản phẩm)
$page = isset($_GET['page']) ? $_GET['page'] : 'products';

// Xử lý Xóa sản phẩm
if (isset($_GET['xoa_id'])) {
    $id_candi = (int)$_GET['xoa_id']; // <-- Thêm (int) vào ngay trước $_GET
    mysqli_query($conn, "DELETE FROM products WHERE id = $id_candi");
    $thongbao = "<div class='alert alert-success' style='color: green; margin-bottom: 15px;'>Đã xóa sản phẩm thành công!</div>";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng điều khiển Admin - PharmaCity</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="hehe.css">
    <style>
        .admin-wrapper { max-width: 1200px; margin: 40px auto; padding: 0 20px; min-height: 60vh; }
        .admin-header-title { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .admin-table { width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; }
        .admin-table th, .admin-table td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        .admin-table th { background-color: #19a695; color: white; font-weight: 600; }
        .admin-table tr:hover { background-color: #f9f9f9; }
        .action-btn { padding: 6px 12px; border-radius: 4px; color: white; text-decoration: none; font-size: 14px; margin-right: 5px; display: inline-block; }
        .btn-edit { background-color: #f59e0b; }
        .btn-delete { background-color: #ef4444; }
        .btn-success { background-color: #10b981; }
        .action-btn:hover { opacity: 0.8; }
        .product-img-small { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; }
        .status-badge { padding: 4px 8px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .status-badge.pending { background: #fef3c7; color: #d97706; }
        .status-badge.completed { background: #d1fae5; color: #059669; }
        .profile-card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); max-width: 500px; margin: 0 auto; }
        .profile-item { margin-bottom: 15px; font-size: 16px; border-bottom: 1px solid #f1f1f1; padding-bottom: 10px; }
    </style>
</head>
<body>

    <header>
        <div class="container navbar">
            <div class="logo">
                <i class="fa-solid fa-heart-pulse"></i> PharmaCity (Admin)
            </div>
            
        <nav class="nav-links" style="position: relative; z-index: 9999;">
                <a href="admin.php?page=products" class="<?php echo $page=='products' ? 'active' : ''; ?>"><i class="fa-solid fa-boxes-stacked"></i> Sản phẩm</a>
                <a href="admin.php?page=orders" class="<?php echo $page=='orders' ? 'active' : ''; ?>"><i class="fa-solid fa-file-invoice-dollar"></i> Đơn hàng</a>
                <a href="admin.php?page=profile" class="<?php echo $page=='profile' ? 'active' : ''; ?>"><i class="fa-solid fa-address-card"></i> Hồ sơ</a>
            </nav>

           <div class="auth-buttons" style="position: relative; z-index: 1;">
                <div class="user-menu">
                    <button class="btn-user">
                        <i class="fa-solid fa-user-shield"></i> Chào: <?php echo $_SESSION['ten_khach_hang']; ?> <i class="fa-solid fa-caret-down"></i>
                    </button>
                    <div class="dropdown-content">
                        <a href="trangchu.php" class="logout-text"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="admin-wrapper">
        <?php if(isset($thongbao)) echo $thongbao; ?>

        <?php 
        // ==========================================
        // GIAO DIỆN 1: QUẢN LÝ SẢN PHẨM
        // ==========================================
        if ($page == 'products') { 
            $sql = "SELECT * FROM products ORDER BY id DESC";
            $result = mysqli_query($conn, $sql);
        ?>
            <div class="admin-header-title">
                <h2><i class="fa-solid fa-boxes-stacked"></i> Kho sản phẩm</h2>
                <a href="#" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm sản phẩm</a>
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá bán</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><strong><?php echo $row['name']; ?></strong></td>
                        <td><?php echo $row['category']; ?></td>
                        <td><?php echo number_format($row['price'], 0, ',', '.'); ?> đ</td>
                        <td>
                            <a href="#" class="action-btn btn-edit"><i class="fa-solid fa-pen"></i></a>
                            <a href="admin.php?page=products&xoa_id=<?php echo $row['id']; ?>" class="action-btn btn-delete" onclick="return confirm('Xóa sản phẩm này?');"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

        <?php 
        // ==========================================
        // GIAO DIỆN 2: LỊCH SỬ ĐƠN HÀNG
        // ==========================================
        } elseif ($page == 'orders') { 
            $sql = "SELECT * FROM orders ORDER BY id DESC";
            $result = mysqli_query($conn, $sql);
        ?>
            <div class="admin-header-title">
                <h2><i class="fa-solid fa-file-invoice-dollar"></i> Lịch sử đơn hàng</h2>
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Mã ĐH</th>
                        <th>Tên khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Ngày đặt</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) { 
                            $statusClass = ($row['status'] == 'Đang xử lý') ? 'pending' : 'completed';
                    ?>
                    <tr>
                        <td>#<?php echo $row['id']; ?></td>
                        <td><?php echo $row['customer_name']; ?></td>
                        <td><strong style="color: red;"><?php echo number_format($row['total_amount'], 0, ',', '.'); ?> đ</strong></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($row['order_date'])); ?></td>
                        <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $row['status']; ?></span></td>
                        <td><a href="#" class="action-btn btn-success"><i class="fa-solid fa-eye"></i> Xem</a></td>
                    </tr>
                    <?php 
                        } 
                    } else {
                        echo "<tr><td colspan='6' style='text-align:center'>Chưa có đơn hàng nào</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

        <?php 
        // ==========================================
        // GIAO DIỆN 3: HỒ SƠ ADMIN
        // ==========================================
        } elseif ($page == 'profile') { 
            $ten_admin = $_SESSION['ten_khach_hang'];
            // Lấy thông tin từ database
            $sql = "SELECT * FROM users WHERE fullname = '$ten_admin' LIMIT 1";
            $result = mysqli_query($conn, $sql);
            $admin_info = mysqli_fetch_assoc($result);
        ?>
            <div class="admin-header-title">
                <h2><i class="fa-solid fa-address-card"></i> Hồ sơ quản trị viên</h2>
            </div>
            <div class="profile-card">
                <div style="text-align: center; margin-bottom: 20px;">
                    <i class="fa-solid fa-circle-user" style="font-size: 80px; color: #19a695;"></i>
                </div>
                <div class="profile-item"><strong>Họ và tên:</strong> <?php echo $admin_info['fullname']; ?></div>
                <div class="profile-item"><strong>Tên đăng nhập:</strong> <?php echo $admin_info['username']; ?></div>
                <div class="profile-item"><strong>Vai trò:</strong> <span style="color: red; font-weight:bold;">Quản trị viên tối cao (Role 1)</span></div>
                <div style="text-align: center; margin-top: 20px;">
                    <button class="btn btn-primary"><i class="fa-solid fa-key"></i> Đổi mật khẩu</button>
                </div>
            </div>
        <?php } ?>

    </main>

    <footer>
        <div class="container" style="text-align: center; padding: 20px 0;">
            <p>© 2026 PharmaCity. Hệ thống quản trị nội bộ.</p>
        </div>
    </footer>

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