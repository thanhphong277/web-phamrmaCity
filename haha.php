<?php
// 1. KẾT NỐI DATABASE
$servername = "localhost";
$username = "root"; // Mặc định của XAMPP
$password = "";     // Mặc định của XAMPP không có mật khẩu
$dbname = "pharmacity";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}


// 2. LOGIC LỌC & TÌM KIẾM SẢN PHẨM Ở ĐÂY
    if (isset($_GET['timkiem']) && $_GET['timkiem'] != '') {
        // TRƯỜNG HỢP 1: NGƯỜI DÙNG GÕ TỪ KHÓA TÌM KIẾM
        $tukhoa = $_GET['timkiem'];
        $sql = "SELECT * FROM products WHERE name LIKE '%$tukhoa%' OR description LIKE '%$tukhoa%'";
        
    } elseif (isset($_GET['danhmuc']) && $_GET['danhmuc'] != '') {
        // TRƯỜNG HỢP 2: NGƯỜI DÙNG BẤM LỌC THEO DANH MỤC
        $danhmuc_duoc_chon = $_GET['danhmuc'];
        $sql = "SELECT * FROM products WHERE category = '$danhmuc_duoc_chon'";
        
    } else {
        // TRƯỜNG HỢP 3: KHÔNG TÌM, CŨNG KHÔNG LỌC -> LÔI HẾT RA
        $sql = "SELECT * FROM products";
    }

    // Tiến hành chạy lệnh và lấy kết quả
    $result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cửa hàng thuốc - PharmaCity</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="hehe.css">
</head>
<body>

    <header>
        <div class="container navbar">
            <div class="logo">
                <i class="fa-solid fa-heart-pulse"></i>
                PharmaCity
            </div>
            
            <nav class="nav-links">
                <a href="index.php"><i class="fa-solid fa-wave-square"></i> Trang chủ</a>
                <a href="haha.php" class="active"><i class="fa-solid fa-bag-shopping"></i> Nhà thuốc</a>
            </nav>
            <div class="auth-buttons">
                <button id="cart-btn" class="btn btn-outline" style="position: relative; cursor: pointer;">
                    <i class="fa-solid fa-cart-shopping"></i> Giỏ hàng
                    <span id="cart-count" style="position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px; display: none;">0</span>
                </button>

            </div>
        </div>
    </header>

    <main class="container">
        <div class="store-header">
            <div class="store-title">
                <h1>Cửa hàng thuốc</h1>
                <p>Duyệt qua danh mục các sản phẩm y tế đã được kiểm định của chúng tôi.</p>
            </div>
            <form action="haha.php" method="GET" class="search-bar">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="timkiem" placeholder="Tìm kiếm sản phẩm..." value="<?php echo isset($_GET['timkiem']) ? $_GET['timkiem'] : ''; ?>">
                <button type="submit" style="display: none;"></button>
            </form>
        </div>

        <div class="store-layout">
            <aside class="sidebar">
                <h3><i class="fa-solid fa-filter"></i> Danh mục</h3>
                <div class="category-list">
                    <?php
                    // Lấy danh mục hiện tại từ thanh URL
                    $dm_hientai = isset($_GET['danhmuc']) ? $_GET['danhmuc'] : '';
                    ?>
                    <a href="haha.php" class="category-item <?php echo ($dm_hientai == '') ? 'active' : ''; ?>">Tất cả</a>
                    <a href="haha.php?danhmuc=Thuốc" class="category-item <?php echo ($dm_hientai == 'Thuốc') ? 'active' : ''; ?>">Thuốc</a>
                    <a href="haha.php?danhmuc=Thực phẩm chức năng" class="category-item <?php echo ($dm_hientai == 'Thực phẩm chức năng') ? 'active' : ''; ?>">Thực phẩm chức năng</a>
                    <a href="haha.php?danhmuc=Sơ cứu" class="category-item <?php echo ($dm_hientai == 'Sơ cứu') ? 'active' : ''; ?>">Sơ cứu</a>
                    <a href="haha.php?danhmuc=Chăm sóc cá nhân" class="category-item <?php echo ($dm_hientai == 'Chăm sóc cá nhân') ? 'active' : ''; ?>">Chăm sóc cá nhân</a>
                </div>
            </aside>
            <div class="product-grid">
                <?php
                // Nếu có sản phẩm trong kho thì bắt đầu vòng lặp   
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                ?>
                        <div class="product-card">
                            <div class="product-image">
                                <?php if($row['image_url'] != "") { ?>
                                    <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>">
                                <?php } else { ?>
                                    <span style="color: #9ca3af; font-size: 0.9rem;">Không có ảnh</span>
                                <?php } ?>
                                <button class="btn-favorite"><i class="fa-regular fa-heart"></i></button>
                            </div>
                            <div class="product-info">
                                <span class="product-tag"><?php echo $row['category']; ?></span>
                                <h3 class="product-title"><?php echo $row['name']; ?></h3>
                                <p class="product-desc"><?php echo $row['description']; ?></p>
                                <div class="product-footer">
                                    <span class="product-price">$<?php echo $row['price']; ?></span>
                                <button class="btn-add-cart" 
            data-name="<?php echo $row['name']; ?>" 
            data-price="<?php echo $row['price']; ?>">
        <i class="fa-solid fa-cart-plus"></i>
    </button>
                                </div>
                            </div>
                        </div>
                <?php
                    } // Kết thúc vòng lặp
                } else {
                    echo "<p>Hiện tại chưa có sản phẩm nào trong cửa hàng.</p>";
                }
                ?>
            </div>

        </div>
    </main>

   <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="logo">
                        <i class="fa-solid fa-heart-pulse"></i>
                        PharmaCity
                    </div>
                    <p>Nhà thuốc kỹ thuật số và người bạn đồng hành chăm sóc sức khỏe đáng tin cậy của bạn.</p>
                </div>
                
                <div class="footer-links">
                    <h4>Liên kết nhanh</h4>
                    <ul>
                        <li><a href="haha.php">Cửa hàng thuốc</a></li>
                    </ul>
                </div>
                
                <div class="footer-links">
                    <h4>Pháp lý</h4>
                    <ul>
                        <li><a href="#">Chính sách bảo mật</a></li>
                        <li><a href="#">Điều khoản dịch vụ</a></li>
                        <li><a href="#">Tuyên bố từ chối trách nhiệm y tế</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>© 2026 PharmaCity. Chỉ mang tính chất cung cấp thông tin.</p>
            </div>
        </div>
    </footer>
    <div id="cart-modal" class="cart-modal">
        <div class="cart-content">
            <div class="cart-header">
                <h2>Tóm tắt đơn hàng</h2>
                <span id="close-cart" class="close-btn">&times;</span>
            </div>
            
            <div id="cart-items" class="cart-items">
                </div>
            
            <div class="cart-footer">
                <div class="cart-total">
                    <span>Tổng cộng:</span>
                    <span id="cart-total-price">$0.00</span>
                </div>
                <button class="btn btn-primary btn-checkout">Đặt đơn</button>
            </div>
        </div>
    </div>
<script src="script.js"></script>
</body>
</html>