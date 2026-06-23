<?php 
session_start(); 

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['ten_khach_hang'])) {
    header("Location: dangnhap.php");
    exit(); 
}

// 2. Kết nối CSDL
$host = "localhost";
$user = "root";
$pass = "";
$db   = "pharmacity";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
mysqli_set_charset($conn, "utf8");

// 3. Lấy dữ liệu người dùng dựa trên session
$currentUser = $_SESSION['ten_khach_hang']; 
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $currentUser);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $userData = $result->fetch_assoc();
} else {
    die("Không tìm thấy thông tin tài khoản.");
}

// 4. Xử lý hiển thị
$fullName = $userData['fullname'];
$nameParts = explode(" ", $fullName);
$lastName = end($nameParts);
$avatarLetter = mb_strtoupper(mb_substr($lastName, 0, 1));
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ - <?php echo htmlspecialchars($fullName); ?></title>
    <link rel="stylesheet" href="profile.css"> 
    <link rel="stylesheet" href="hehe.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="navbar">
        <div class="nav-left">
            <div class="logo">
                <a href="index.php" style="text-decoration: none; display: flex; align-items: center; color: #00b36b; font-weight: bold;">
                    <i class="fa-solid fa-heart-pulse"></i> PharmaCity
                </a>
            </div>
            <nav class="nav-links">
                <a href="index.php"><i class="fa-solid fa-wave-square"></i> Trang chủ</a>
                <a href="haha.php"><i class="fa-solid fa-bag-shopping"></i> Nhà thuốc</a>
            </nav>
        </div>
        
        <div class="auth-buttons">
            <div class="user-menu" style="position: relative;">
                <button class="btn-user" id="userBtn">
                    <i class="fa-solid fa-user-circle"></i> Xin chào, <?php echo htmlspecialchars($currentUser); ?> <i class="fa-solid fa-caret-down"></i>
                </button>
                <div class="dropdown-content" id="userDropdown">
                    <a href="profile.php"><i class="fa-solid fa-id-card"></i> Hồ sơ của tôi</a>
                    <a href="donmua.php"><i class="fa-solid fa-clock-rotate-left"></i> Lịch sử mua hàng</a>
                    <hr> 
                    <a href="dangxuat.php" class="logout-text"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
                </div>
            </div>
        </div>
    </header>

    <div class="container" style="margin-top: 30px;">
        <aside class="sidebar">
            <div class="menu-item active"><i class="fa-regular fa-user"></i> Hồ sơ của tôi</div>
            <div class="menu-item"><i class="fa-solid fa-receipt"></i> Lịch sử mua hàng</div>
            <a href="dangxuat.php" style="text-decoration: none; color: inherit;">
                <div class="logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> Đăng xuất</div>
            </a>
        </aside>

        <main class="main-content">
            <div class="top-row">
                <div class="profile-card">
                    <div class="avatar-section">
                        <div class="avatar"><?php echo $avatarLetter; ?></div>
                        <div class="camera-icon"><i class="fa-solid fa-camera"></i></div>
                    </div>
                    <div class="user-info">
                        <h2 id="display-name"><?php echo htmlspecialchars($fullName); ?></h2>
                        <p><i class="fa-regular fa-circle-check"></i> Thành viên từ: <?php echo date("d/m/Y", strtotime($userData['ngaysinh'])); ?></p>
                    </div>
                </div>
                <div class="points-card">
                    <div class="points-label"><i class="fa-solid fa-award"></i> Điểm tích lũy</div>
                    <div class="points-value"><?php echo number_format($userData['points'] ?? 0); ?></div>
                </div>
            </div>

            <div class="info-section">
                <form id="profileForm">
                    <div class="section-header">
                        <h3>Thông tin cá nhân</h3>
                        <button type="button" class="edit-btn" id="btnEdit" onclick="toggleEdit()">
                            <i class="fa-solid fa-pencil"></i> Chỉnh sửa
                        </button>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Họ và tên</label>
                            <input type="text" name="fullname" value="<?php echo htmlspecialchars($userData['fullname']); ?>" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="text" name="sodt" value="<?php echo htmlspecialchars($userData['sodt']); ?>" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label>Ngày sinh</label>
                            <input type="date" name="ngaysinh" value="<?php echo $userData['ngaysinh']; ?>" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label>Giới tính</label>
                            <input type="text" name="gioitinh" value="<?php echo htmlspecialchars($userData['gioitinh']); ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Địa chỉ</label>
                        <input type="text" name="diachi" value="<?php echo htmlspecialchars($userData['diachi']); ?>" readonly>
                    </div>
                    
                    <div id="saveArea" style="display:none; text-align:right; margin-top:20px;">
                        <button type="button" onclick="saveData()" class="save-btn" style="background-color: #00b36b; color: white; padding: 10px 30px; border: none; border-radius: 25px; cursor: pointer; font-weight: bold;">
                            Lưu thông tin
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        // Điều khiển Dropdown Menu
        const nutUser = document.getElementById("userBtn");
        const menuXoXuong = document.getElementById("userDropdown");

        nutUser.onclick = (e) => {
            e.stopPropagation();
            menuXoXuong.classList.toggle("show");
        };

        window.onclick = () => menuXoXuong.classList.remove("show");

        // Chế độ chỉnh sửa
        function toggleEdit() {
            const inputs = document.querySelectorAll('#profileForm input');
            const btn = document.getElementById('btnEdit');
            const saveArea = document.getElementById('saveArea');
            const isReadOnly = inputs[0].readOnly;

            inputs.forEach(i => {
                i.readOnly = !isReadOnly;
                i.style.backgroundColor = isReadOnly ? "#fff" : "#fafafa";
                i.style.border = isReadOnly ? "1px solid #00b36b" : "none";
            });

            btn.innerHTML = isReadOnly ? '<i class="fa-solid fa-xmark"></i> Hủy' : '<i class="fa-solid fa-pencil"></i> Chỉnh sửa';
            saveArea.style.display = isReadOnly ? "block" : "none";
        }

        // Gửi dữ liệu cập nhật
        async function saveData() {
            const formData = new FormData(document.getElementById('profileForm'));
            const data = Object.fromEntries(formData.entries());

            try {
                const resp = await fetch('update_profile.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(data)
                });
                const res = await resp.json();
                if(res.success) { 
                    alert("Cập nhật thành công!"); 
                    location.reload(); 
                } else {
                    alert("Lỗi: " + res.message);
                }
            } catch (err) {
                alert("Lỗi kết nối server.");
            }
        }
    </script>
</body>
</html>