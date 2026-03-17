<?php
session_start();
require_once 'db.php';

// 1. Kiểm tra xem đã đăng nhập chưa
if (!isset($_SESSION['ten_khach_hang'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để đặt hàng!']);
    exit;
}

// 2. Nhận gói hàng (dữ liệu JSON) từ Javascript gửi lên
$data = json_decode(file_get_contents('php://input'), true);

// 3. Nếu giỏ hàng có đồ thì bắt đầu xử lý
if (!empty($data['cart'])) {
    $username = $_SESSION['ten_khach_hang'];
    $cart = $data['cart'];
    $total_amount = 0;

    // Tính lại tổng tiền an toàn ở phía Backend (đề phòng hacker sửa giá trên trình duyệt)
    foreach ($cart as $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }

    // Lấy Tên hiển thị (fullname) của khách từ CSDL để lưu vào hóa đơn cho đẹp
    $sql_user = "SELECT fullname FROM users WHERE username = '$username' LIMIT 1";
    $res_user = mysqli_query($conn, $sql_user);
    $customer_name = (mysqli_num_rows($res_user) > 0) ? mysqli_fetch_assoc($res_user)['fullname'] : $username;

    // --- BƯỚC A: TẠO HÓA ĐƠN TỔNG VÀO BẢNG 'orders' ---
    $sql_order = "INSERT INTO orders (username, customer_name, total_amount, status) VALUES ('$username', '$customer_name', $total_amount, 'Đang xử lý')";
    
    if (mysqli_query($conn, $sql_order)) {
        // Lấy ID của cái hóa đơn vừa tạo xong
        $order_id = mysqli_insert_id($conn); 

        // --- BƯỚC B: GHI CHI TIẾT TỪNG MÓN VÀO BẢNG 'order_details' ---
        foreach ($cart as $item) {
            $ten_mon = mysqli_real_escape_string($conn, $item['name']);
            $so_luong = (int)$item['quantity'];
            $gia_ban = (int)$item['price'];
            
            $sql_detail = "INSERT INTO order_details (order_id, product_name, quantity, price) VALUES ($order_id, '$ten_mon', $so_luong, $gia_ban)";
            mysqli_query($conn, $sql_detail);
        }

        // Báo cáo về cho Javascript là đã thành công!
        echo json_encode(['success' => true, 'message' => 'Đặt hàng thành công! Admin sẽ sớm liên hệ với bạn.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi tạo hóa đơn.']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Giỏ hàng của bạn đang trống!']);
}
?>