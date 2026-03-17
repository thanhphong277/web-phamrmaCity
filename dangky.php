<?php session_start(); ?>
<?php
// 1. KẾT NỐI DATABASE
require_once 'db.php';
$thongbao = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // BƯỚC A: Kiểm tra xem tên đăng nhập đã có ai dùng chưa
    $check_sql = "SELECT id FROM users WHERE username = ?";
    $stmt_check = mysqli_prepare($conn, $check_sql);
    
    mysqli_stmt_bind_param($stmt_check, "s", $username);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        $thongbao = "<div class='alert alert-error'>Tên đăng nhập này đã có người sử dụng. Vui lòng chọn tên khác!</div>";
    } else {
        // BƯỚC B: Băm mật khẩu 
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // BƯỚC C: Cất thông tin vào database
        $insert_sql = "INSERT INTO users (fullname, username, password) VALUES (?, ?, ?)";
        $stmt_insert = mysqli_prepare($conn, $insert_sql);
        
        mysqli_stmt_bind_param($stmt_insert, "sss", $fullname, $username, $hashed_password);
        
        if (mysqli_stmt_execute($stmt_insert)) {
            $thongbao = "<div class='alert alert-success'>Đăng ký thành công! <a href='dangnhap.php'>Bấm vào đây để đăng nhập</a></div>";
        } else {
            $thongbao = "<div class='alert alert-error'>Đã xảy ra lỗi hệ thống, vui lòng thử lại sau.</div>";
        }
        mysqli_stmt_close($stmt_insert);
    }
    mysqli_stmt_close($stmt_check);
}
?>

<?php require_once 'header.php'; ?>

<main class="auth-wrapper">
    <div class="auth-box">
        <h2>Tạo tài khoản mới</h2>
        
        <?php echo $thongbao; ?>

        <form action="dangky.php" method="POST">
            <div class="form-group">
                <label for="fullname">Họ và tên</label>
                <input type="text" id="fullname" name="fullname" placeholder="VD: Nguyễn Văn A" required>
            </div>

            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" id="username" name="username" placeholder="Viết liền, không dấu" required>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu của bạn" required>
            </div>

            <button type="submit" class="btn btn-primary btn-submit">
                <i class="fa-solid fa-user-plus"></i> Đăng ký ngay
            </button>
        </form>
        
        <div class="auth-link-text">
            Đã có tài khoản? <a href="dangnhap.php">Đăng nhập</a>
        </div>
    </div>
</main>

<?php require_once 'footer.php'; ?>