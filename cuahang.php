<?php 
session_start(); 
if (!isset($_SESSION['ten_khach_hang'])) {
    header("Location: dangnhap.php");
    exit(); 
}

// 1. KẾT NỐI DATABASE
require_once 'db.php';

// 2. LOGIC LỌC & TÌM KIẾM SẢN PHẨM Ở ĐÂY
if (isset($_GET['timkiem']) && $_GET['timkiem'] != '') {
    $tukhoa = $_GET['timkiem'];
    $sql = "SELECT * FROM products WHERE name LIKE '%$tukhoa%' OR description LIKE '%$tukhoa%'";
} elseif (isset($_GET['danhmuc']) && $_GET['danhmuc'] != '') {
    $danhmuc_duoc_chon = $_GET['danhmuc'];
    $sql = "SELECT * FROM products WHERE category = '$danhmuc_duoc_chon'";
} else {
    $sql = "SELECT * FROM products";
}

$result = mysqli_query($conn, $sql);
?>

<?php require_once 'header.php'; ?>

<main class="container">
    <div class="store-header">
        <div class="store-title">
            <h1>Cửa hàng thuốc</h1>
            <p>Duyệt qua danh mục các sản phẩm y tế đã được kiểm định của chúng tôi.</p>
        </div>
        <form action="cuahang.php" method="GET" class="search-bar">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="timkiem" placeholder="Tìm kiếm sản phẩm..." value="<?php echo isset($_GET['timkiem']) ? $_GET['timkiem'] : ''; ?>">
            <button type="submit" style="display: none;"></button>
        </form>
    </div>

    <div class="store-layout">
        <aside class="sidebar">
            <h3><i class="fa-solid fa-filter"></i> Danh mục</h3>
            <div class="category-list">
                <?php $dm_hientai = isset($_GET['danhmuc']) ? $_GET['danhmuc'] : ''; ?>
                <a href="cuahang.php" class="category-item <?php echo ($dm_hientai == '') ? 'active' : ''; ?>">Tất cả</a>
                <a href="cuahang.php?danhmuc=Thuốc" class="category-item <?php echo ($dm_hientai == 'Thuốc') ? 'active' : ''; ?>">Thuốc</a>
                <a href="cuahang.php?danhmuc=Thực phẩm chức năng" class="category-item <?php echo ($dm_hientai == 'Thực phẩm chức năng') ? 'active' : ''; ?>">Thực phẩm chức năng</a>
                <a href="cuahang.php?danhmuc=Sơ cứu" class="category-item <?php echo ($dm_hientai == 'Sơ cứu') ? 'active' : ''; ?>">Sơ cứu</a>
                <a href="cuahang.php?danhmuc=Chăm sóc cá nhân" class="category-item <?php echo ($dm_hientai == 'Chăm sóc cá nhân') ? 'active' : ''; ?>">Chăm sóc cá nhân</a>
            </div>
        </aside>
        
        <div class="product-grid">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
            ?>
                    <div class="product-card">
                        <div class="product-image">
                            <?php if($row['image_url'] != "") { ?>
                                <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>">
                            <?php } else { ?>
                                <span class="no-image-text">Không có ảnh</span>
                            <?php } ?>
                            <button class="btn-favorite"><i class="fa-regular fa-heart"></i></button>
                        </div>
                        <div class="product-info">
                            <span class="product-tag"><?php echo $row['category']; ?></span>
                            <h3 class="product-title"><?php echo $row['name']; ?></h3>
                            <p class="product-desc"><?php echo $row['description']; ?></p>
                            <div class="product-footer">
                                <span class="product-price"><?php echo number_format($row['price'], 0, ',', '.'); ?> đ</span>
                                <button class="btn-add-cart" data-name="<?php echo $row['name']; ?>" data-price="<?php echo $row['price']; ?>">
                                    <i class="fa-solid fa-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p>Hiện tại chưa có sản phẩm nào trong cửa hàng.</p>";
            }
            ?>
        </div>
    </div>
</main>

<div id="cart-modal" class="cart-modal">
    <div class="cart-content">
        <div class="cart-header">
            <h2>Tóm tắt đơn hàng</h2>
            <span id="close-cart" class="close-btn">&times;</span>
        </div>
        
        <div id="cart-items" class="cart-items"></div>
        
        <div class="cart-footer">
            <div class="cart-total">
                <span>Tổng cộng:</span>
                <span id="cart-total-price"><span id="cart-total-price">0 VND</span></span>
            </div>
            <button class="btn btn-primary btn-checkout">Đặt đơn</button>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>