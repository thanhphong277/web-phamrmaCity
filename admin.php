<?php 
session_start(); 

// 1. KIỂM TRA QUYỀN TRUY CẬP (Code cũ của bạn)
// Nếu chưa có thẻ VIP, HOẶC có thẻ nhưng quyền hạn = 0 (là khách)
if (!isset($_SESSION['ten_khach_hang']) || $_SESSION['quyen_han'] == 0) {
    // Đuổi cổ ra ngoài trang chủ ngay lập tức!
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

// 3. XỬ LÝ CHỨC NĂNG XÓA SẢN PHẨM
if (isset($_GET['xoa_id'])) {
    $id_candi = $_GET['xoa_id'];
    $sql_delete = "DELETE FROM products WHERE id = $id_candi";
    if(mysqli_query($conn, $sql_delete)) {
        $thongbao = "<div class='alert alert-success' style='color: green; margin-bottom: 15px;'>Đã xóa sản phẩm thành công!</div>";
    } else {
        $thongbao = "<div class='alert alert-error' style='color: red; margin-bottom: 15px;'>Lỗi khi xóa: " . mysqli_error($conn) . "</div>";
    }
}

// 4. LẤY DANH SÁCH SẢN PHẨM TỪ DATABASE
$sql = "SELECT * FROM products ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản trị viên - PharmaCity</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="hehe.css">
    <style>
        /* CSS bổ sung riêng cho bảng Admin để hiển thị đẹp mắt */
        .admin-wrapper {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            min-height: 60vh;
        }
        .admin-header-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .admin-table th, .admin-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .admin-table th {
            background-color: #19a695; /* var(--primary-color) */
            color: white;
            font-weight: 600;
        }
        .admin-table tr:hover {
            background-color: #f9f9f9;
        }
        .action-btn {
            padding: 6px 12px;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            font-size: 14px;
            margin-right: 5px;
            display: inline-block;
        }
        .btn-edit { background-color: #f59e0b; }
        .btn-delete { background-color: #ef4444; }
        .action-btn:hover { opacity: 0.8; }
        .product-img-small { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; }
    </style>
</head>
<body>

    <header>
        <div class="container navbar">
            <div class="logo">
                <i class="fa-solid fa-heart-pulse"></i> PharmaCity (Khu vực Admin)
            </div>
            
            <nav class="nav-links">
                <a href="admin.php" class="active"><i class="fa-solid fa-boxes-stacked"></i> Quản lý sản phẩm</a>
                </nav>

            <div class="auth-buttons">
                <div class="user-menu">
                    <button class="btn-user">
                        <i class="fa-solid fa-user-shield"></i> Chào Admin: <?php echo $_SESSION['ten_khach_hang']; ?> <i class="fa-solid fa-caret-down"></i>
                    </button>
                    
                    <div class="dropdown-content">
                        <a href="trangchu.php" class="logout-text"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="admin-wrapper">
        <div class="admin-header-title">
            <h2><i class="fa-solid fa-list-check"></i> Danh sách sản phẩm</h2>
            <a href="#" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm sản phẩm mới</a>
        </div>

        <?php 
        // Hiển thị thông báo (nếu có khi xóa thành công/thất bại)
        if(isset($thongbao)) echo $thongbao; 
        ?>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá bán</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td>
                        <?php if($row['image_url'] != "") { ?>
                            <img src="<?php echo $row['image_url']; ?>" class="product-img-small" alt="Ảnh SP">
                        <?php } else { ?>
                            <span>Không có ảnh</span>
                        <?php } ?>
                    </td>
                    <td><strong><?php echo $row['name']; ?></strong></td>
                    <td><span class="product-tag"><?php echo $row['category']; ?></span></td>
                    <td><?php echo number_format($row['price'], 0, ',', '.'); ?> đ</td>
                    <td>
                        <a href="#" class="action-btn btn-edit" title="Sửa"><i class="fa-solid fa-pen-to-square"></i> Sửa</a>
                        <a href="admin.php?xoa_id=<?php echo $row['id']; ?>" class="action-btn btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?');"><i class="fa-solid fa-trash"></i> Xóa</a>
                    </td>
                </tr>
                <?php 
                    } 
                } else {
                    echo "<tr><td colspan='6' style='text-align:center;'>Chưa có sản phẩm nào trong kho.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>

    <footer>
        <div class="container">
            <div class="footer-bottom" style="text-align: center; padding: 20px 0;">
                <p>© 2026 PharmaCity. Bảng điều khiển dành cho Quản trị viên.</p>
            </div>
        </div>
    </footer>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const nutUser = document.querySelector(".btn-user");
        const menuXoXuong = document.querySelector(".dropdown-content");

        if (nutUser && menuXoXuong) {
            nutUser.addEventListener("click", function(event) {
                event.stopPropagation(); 
                menuXoXuong.classList.toggle("show"); 
            });

            window.addEventListener("click", function(event) {
                if (!menuXoXuong.contains(event.target) && !nutUser.contains(event.target)) {
                    menuXoXuong.classList.remove("show");
                }
            });
        }
    });
    </script>
</body>
</html>