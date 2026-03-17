<footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="logo">
                        <i class="fa-solid fa-heart-pulse"></i> PharmaCity
                    </div>
                    <p>Nhà thuốc kỹ thuật số và người bạn đồng hành chăm sóc sức khỏe đáng tin cậy của bạn.</p>
                </div>
                
                <div class="footer-links">
                    <h4>Liên kết nhanh</h4>
                    <ul>
                        <li><a href="cuahang.php">Cửa hàng thuốc</a></li>
                    </ul>
                </div>
                
                <div class="footer-links">
                    <h4>Pháp lý</h4>
                    <ul>
                        <li><a href="#">Chính sách bảo mật</a></li>
                        <li><a href="#">Điều khoản dịch vụ</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>© 2026 PharmaCity. Hệ thống nhà thuốc trực tuyến.</p>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const nutUser = document.querySelector(".btn-user");
        const menuXoXuong = document.querySelector(".dropdown-content");
        if (nutUser && menuXoXuong) {
            nutUser.addEventListener("click", function(e) { 
                e.stopPropagation(); 
                menuXoXuong.classList.toggle("show"); 
            });
            window.addEventListener("click", function(e) { 
                if (!menuXoXuong.contains(e.target) && !nutUser.contains(e.target)) {
                    menuXoXuong.classList.remove("show");
                }
            });
        }
    });
    </script>
</body>
</html>