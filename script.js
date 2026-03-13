document.addEventListener("DOMContentLoaded", function () {
  // 1. Lấy giỏ hàng từ bộ nhớ với tên chuẩn
  let gioHang = JSON.parse(localStorage.getItem("gioHangCuaToi")) || [];

  // 2. BƯỚC DỌN RÁC THẦN THÁNH
  if (gioHang.length > 0 && gioHang[0].quantity === undefined) {
    gioHang = [];
    localStorage.setItem("gioHangCuaToi", JSON.stringify(gioHang));
  }

  // --- CÁC HÀM XỬ LÝ CHÍNH ---
  function capNhatGioHang() {
    localStorage.setItem("gioHangCuaToi", JSON.stringify(gioHang));
    veGioHangRaManHinh();
  }

  function veGioHangRaManHinh() {
    const divChuaHang = document.getElementById("cart-items");
    const theHienTongTien = document.getElementById("cart-total-price");
    const cucDoBaoSoLuong = document.getElementById("cart-count");

    divChuaHang.innerHTML = "";
    let tongTien = 0;
    let tongSoLuong = 0;

    if (gioHang.length === 0) {
      divChuaHang.innerHTML =
        '<p style="text-align:center; color:#888; margin-top: 50px;">Chưa có sản phẩm nào trong giỏ.</p>';
      theHienTongTien.innerText = "$0.00";
      cucDoBaoSoLuong.style.display = "none";
      return;
    }

    gioHang.forEach((monHang, viTri) => {
      tongTien += monHang.price * monHang.quantity;
      tongSoLuong += monHang.quantity;

      divChuaHang.innerHTML += `
                <div class="cart-item">
                    <div class="cart-item-info">
                        <h4>${monHang.name}</h4>
                        <span class="cart-item-price">$${monHang.price.toFixed(2)}</span>
                    </div>
                    <div class="qty-controls">
                        <button class="qty-btn" onclick="thayDoiSoLuong(${viTri}, -1)">-</button>
                        <span>${monHang.quantity}</span>
                        <button class="qty-btn" onclick="thayDoiSoLuong(${viTri}, 1)">+</button>
                    </div>
                </div>
            `;
    });
    //Tính tổng
    theHienTongTien.innerText = "$" + tongTien.toFixed(2);
    cucDoBaoSoLuong.style.display = "block";
    cucDoBaoSoLuong.innerText = tongSoLuong;
  }

  window.thayDoiSoLuong = function (viTri, soLuongThayDoi) {
    gioHang[viTri].quantity += soLuongThayDoi;
    if (gioHang[viTri].quantity <= 0) {
      gioHang.splice(viTri, 1);
    }
    capNhatGioHang();
  };

  // --- BẮT SỰ KIỆN NÚT BẤM ---
  const nutThemVaoGio = document.querySelectorAll(".btn-add-cart");
  nutThemVaoGio.forEach((button) => {
    button.addEventListener("click", function () {
      const ten = this.getAttribute("data-name");
      const gia = parseFloat(this.getAttribute("data-price"));

      let monDaCo = gioHang.find((item) => item.name === ten);

      if (monDaCo) {
        monDaCo.quantity += 1;
      } else {
        gioHang.push({ name: ten, price: gia, quantity: 1 });
      }

      capNhatGioHang();

      // Hiện thông báo (Shopee style)
      alert("🛒 Đã thêm thành công: " + ten + " vào giỏ hàng!");
    });
  });
  // Mở giỏ hàng khi bấm vào nút "Giỏ hàng" trên Header
  document.getElementById("cart-btn").addEventListener("click", () => {
    document.getElementById("cart-modal").style.display = "block";
  });

  // Đóng giỏ hàng khi bấm dấu X
  document.getElementById("close-cart").addEventListener("click", () => {
    document.getElementById("cart-modal").style.display = "none";
  });

  // Chạy lần đầu tiên khi vừa vào web để hiển thị giỏ cũ
  veGioHangRaManHinh();
});
