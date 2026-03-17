<?php 
session_start(); 

if (!isset($_SESSION['ten_khach_hang']) || $_SESSION['quyen_han'] == 0) {
    header("Location: index.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "pharmacity");
if (!$conn) die("Kết nối thất bại: " . mysqli_connect_error());

$show_alert = false; // Biến cờ để gọi SweetAlert

// XỬ LÝ CẬP NHẬT TRẠNG THÁI
if (isset($_POST['cap_nhat_trang_thai'])) {
    $order_id = (int)$_POST['order_id'];
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $sql_update = "UPDATE orders SET status = '$new_status' WHERE id = $order_id";
    mysqli_query($conn, $sql_update);
    $show_alert = true; // Bật cờ hiển thị thông báo
}

// LẤY DỮ LIỆU THỐNG KÊ (DASHBOARD)
$thongke_tongdon = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as total FROM orders"))['total'];
$thongke_dangcho = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as total FROM orders WHERE status = 'Đang xử lý'"))['total'];
$thongke_doanhthu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) as total FROM orders WHERE status = 'Đã giao'"))['total'];

// LẤY DANH SÁCH ĐƠN HÀNG
$sql_orders = "SELECT * FROM orders ORDER BY order_date DESC";
$result_orders = mysqli_query($conn, $sql_orders);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Đơn Hàng - Admin VIP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="hehe.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { background-color: #f0f2f5; }
        .admin-wrapper { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        
        /* Dashboard Thống Kê */
        .dashboard-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
        .card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: flex; align-items: center; justify-content: space-between; }
        .card-info h3 { font-size: 14px; color: #666; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px; }
        .card-info p { font-size: 24px; font-weight: bold; color: #333; margin: 0; }
        .card-icon { width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; }
        .icon-blue { background: #e0f2fe; color: #0284c7; }
        .icon-orange { background: #ffedd5; color: #ea580c; }
        .icon-green { background: #dcfce7; color: #16a34a; }

        /* Bảng Đơn Hàng */
        .order-container { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .order-container h2 { margin-bottom: 20px; font-size: 20px; color: #1f2937; border-bottom: 2px solid #f3f4f6; padding-bottom: 15px; }
        .table-orders { width: 100%; border-collapse: collapse; }
        .table-orders th { background-color: #f8fafc; color: #475569; font-weight: 600; text-transform: uppercase; font-size: 13px; padding: 15px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .table-orders td { padding: 15px; border-bottom: 1px solid #e2e8f0; vertical-align: middle; color: #334155; }
        .table-orders tr:hover { background-color: #f8fafc; }
        
        /* Trạng thái */
        .badge-status { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block; }
        .status-dang-xu-ly { background-color: #fef3c7; color: #d97706; }
        .status-da-giao { background-color: #d1fae5; color: #059669; }
        .status-da-huy { background-color: #fee2e2; color: #dc2626; }
        
        /* Nút và Select */
        .action-flex { display: flex; gap: 8px; align-items: center; }
        .status-select { padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; outline: none; font-family: inherit; color: #334155; }
        .btn-update { background-color: #19a695; color: white; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-weight: 500; transition: 0.2s; }
        .btn-update:hover { background-color: #128072; transform: translateY(-1px); }
    </style>
</head>
<body>
    <header>
        <div class="container navbar">
            <div class="logo"><i class="fa-solid fa-heart-pulse"></i> Admin Panel</div>
            <nav class="nav-links">
                <a href="admin.php"><i class="fa-solid fa-boxes-stacked"></i> QL Sản phẩm</a>
                <a href="quanly_donhang.php" class="active"><i class="fa-solid fa-file-invoice-dollar"></i> QL Đơn hàng</a>
                <a href="hoso_admin.php"><i class="fa-solid fa-user-shield"></i> Hồ sơ</a>
                <a href="dangxuat.php" style="color: red;"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
            </nav>
        </div>
    </header>

    <main class="admin-wrapper">
        <div class="dashboard-cards">
            <div class="card">
                <div class="card-info">
                    <h3>Tổng đơn hàng</h3>
                    <p><?php echo $thongke_tongdon; ?></p>
                </div>
                <div class="card-icon icon-blue"><i class="fa-solid fa-box-open"></i></div>
            </div>
            <div class="card">
                <div class="card-info">
                    <h3>Đang chờ xử lý</h3>
                    <p><?php echo $thongke_dangcho; ?></p>
                </div>
                <div class="card-icon icon-orange"><i class="fa-solid fa-clock"></i></div>
            </div>
            <div class="card">
                <div class="card-info">
                    <h3>Tổng doanh thu</h3>
                    <p style="color: #16a34a;"><?php echo number_format((float)$thongke_doanhthu, 0, ',', '.'); ?> VND</p>
                </div>
                <div class="card-icon icon-green"><i class="fa-solid fa-money-bill-trend-up"></i></div>
            </div>
        </div>

        <div class="order-container">
            <h2><i class="fa-solid fa-list-check"></i> Danh sách Đơn hàng mới nhất</h2>
            
            <table class="table-orders">
                <thead>
                    <tr>
                        <th>Mã ĐH</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($result_orders) > 0) { 
                        while($row = mysqli_fetch_assoc($result_orders)) { 
                            $status_class = 'status-dang-xu-ly';
                            if ($row['status'] == 'Đã giao') $status_class = 'status-da-giao';
                            if ($row['status'] == 'Đã hủy') $status_class = 'status-da-huy';
                    ?>
                    <tr>
                        <td><strong>#<?php echo $row['id']; ?></strong></td>
                        <td>
                            <strong style="color: #0f172a;"><?php echo $row['customer_name']; ?></strong><br>
                            <small style="color: #64748b;"><i class="fa-solid fa-user"></i> <?php echo $row['username']; ?></small>
                        </td>
                        <td><i class="fa-regular fa-calendar-days"></i> <?php echo date('d/m/Y H:i', strtotime($row['order_date'])); ?></td>
                        <td style="color: #dc2626; font-weight: 600;"><?php echo number_format($row['total_amount'], 0, ',', '.'); ?> VND</td>
                        <td><span class="badge-status <?php echo $status_class; ?>"><?php echo $row['status']; ?></span></td>
                        <td>
                            <form action="quanly_donhang.php" method="POST" class="action-flex">
                                <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                <select name="status" class="status-select">
                                    <option value="Đang xử lý" <?php if($row['status']=='Đang xử lý') echo 'selected'; ?>>Đang xử lý</option>
                                    <option value="Đã giao" <?php if($row['status']=='Đã giao') echo 'selected'; ?>>Đã giao</option>
                                    <option value="Đã hủy" <?php if($row['status']=='Đã hủy') echo 'selected'; ?>>Đã hủy</option>
                                </select>
                                <button type="submit" name="cap_nhat_trang_thai" class="btn-update"><i class="fa-solid fa-check"></i> Lưu</button>
                            </form>
                        </td>
                    </tr>
                    <?php } } else { ?>
                    <tr><td colspan="6" style="text-align: center; padding: 40px; color: #94a3b8;">Chưa có đơn hàng nào trong hệ thống!</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php if($show_alert) { ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: 'Đã cập nhật trạng thái đơn hàng.',
                confirmButtonColor: '#19a695',
                timer: 2000
            });
        </script>
    <?php } ?>
</body>
</html>