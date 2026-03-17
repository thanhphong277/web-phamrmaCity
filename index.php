<?php 
session_start(); 
require_once 'db.php'; 
?>

<?php require_once 'header.php'; ?>

<main>
    <section class="hero">
        <div class="container hero-content">
            <h1 class="hero-title">Sức Khỏe Của Bạn,<br />Ưu Tiên Của Chúng Tôi</h1>
            <p class="hero-subtitle">Nhà thuốc trực tuyến đáng tin cậy cung cấp các loại thuốc chất lượng và lời khuyên y tế chuyên nghiệp ngay tại nhà bạn.</p>
            <div class="hero-buttons">
                <a href="<?php echo isset($_SESSION['ten_khach_hang']) ? 'cuahang.php' : 'cuahang_khach.php'; ?>" class="btn btn-primary btn-lg">
                    Mua thuốc ngay <i class="fa-solid fa-arrow-right" style="margin-left: 8px"></i>
                </a>
            </div>
        </div>
    </section>
</main>

<?php require_once 'footer.php'; ?>