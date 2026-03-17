<?php session_start(); ?>
<?php
// 1. KẾT NỐI DATABASE
require_once 'db.php';
$thongbao = "";

// 2. XỬ LÝ KHI BẤM NÚT ĐĂNG NHẬP
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password_nhap_vao = $_POST['password']; // Mật khẩu người dùng gõ vào (vd: 123456)

    // Bước A: Thần chú tìm xem CÓ AI TÊN NÀY TRONG KHO KHÔNG? (Chưa xét mật khẩu)
    $check_sql = "SELECT * FROM users WHERE username = '$username'";
    $check_result = mysqli_query($conn, $check_sql);

    // Nếu tìm thấy 1 người có tên này
    if (mysqli_num_rows($check_result) == 1) {
        
        // Lôi toàn bộ thông tin người đó ra (thành một cái mảng)
        $row = mysqli_fetch_assoc($check_result);
        $mat_khau_trong_kho = $row['password']; // Đây là chuỗi đã băm ngoằn ngoèo

        // Bước B: Yêu cầu PHP đối chiếu mật khẩu nhập vào với chuỗi băm trong kho
        if (password_verify($password_nhap_vao, $mat_khau_trong_kho)) {
            $thongbao = "<div class='alert alert-success'>Đăng nhập thành công!</div>";
            $_SESSION['ten_khach_hang'] = $row['username'];
            $_SESSION['quyen_han'] = $row['role'];
            if ($_SESSION['quyen_han'] == 1) {
                header("Location: admin.php"); 
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $thongbao = "<div class='alert alert-error'>Tên đăng nhập hoặc mật khẩu không chính xác</div>";
        }

    } else {
        $thongbao = "<div class='alert alert-error'>Tên đăng nhập hoặc mật khẩu không chính xác</div>";
    }
}
?>

<?php require_once 'header.php'; ?>

<main class="auth-wrapper">
    <div class="auth-box">
        <h2>Đăng nhập hệ thống</h2>
        
        <?php echo $thongbao; ?>

        <form action="dangnhap.php" method="POST">
            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập" required>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
            </div>

            <button type="submit" class="btn btn-outline btn-submit">
                <i class="fa-solid fa-right-to-bracket"></i> Đăng nhập
            </button>
        </form>
        
        <div class="auth-link-text">
            Chưa có tài khoản? <a href="dangky.php">Đăng ký ngay</a>
        </div>
    </div>
</main>

<?php require_once 'footer.php'; ?>