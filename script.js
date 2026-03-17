document.addEventListener("DOMContentLoaded", function () {
  let gioHang = JSON.parse(localStorage.getItem("gioHangCuaToi")) || [];

  if (gioHang.length > 0 && gioHang[0].quantity === undefined) {
    gioHang = [];
    localStorage.setItem("gioHangCuaToi", JSON.stringify(gioHang));
  }

  function capNhatGioHang() {
    localStorage.setItem("gioHangCuaToi", JSON.stringify(gioHang));
    veGioHangRaManHinh();
  }

  function veGioHangRaManHinh() {
    const divChuaHang = document.getElementById("cart-items");
    const theHienTongTien = document.getElementById("cart-total-price");
    const cucDoBaoSoLuong = document.getElementById("cart-count");

    if (!divChuaHang || !theHienTongTien || !cucDoBaoSoLuong) return;

    divChuaHang.innerHTML = "";
    let tongTien = 0;
    let tongSoLuong = 0;

    if (gioHang.length === 0) {
      divChuaHang.innerHTML =
        '<p style="text-align:center; color:#888; margin-top: 50px;">Chưa có sản phẩm nào trong giỏ.</p>';
      theHienTongTien.innerText = "0 đ";
      cucDoBaoSoLuong.style.display = "none";
      return;
    }

    gioHang.forEach((monHang, viTri) => {
      let thanhTienMon = monHang.price * monHang.quantity; // Tính thành tiền của món
      tongTien += thanhTienMon;
      tongSoLuong += monHang.quantity;

      divChuaHang.innerHTML += `
                <div class="cart-item" style="border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 10px;">
                    <div class="cart-item-info" style="flex: 1;">
                        <h4 style="margin: 0 0 5px 0;">${monHang.name}</h4>
                        <span style="color: #888; font-size: 0.9em;">Đơn giá: ${monHang.price.toLocaleString("vi-VN")} đ</span>
                        <div style="color: #19a695; font-weight: bold; margin-top: 4px;">
                            Thành tiền: ${thanhTienMon.toLocaleString("vi-VN")} đ
                        </div>
                    </div>
                    <div class="qty-controls" style="display: flex; align-items: center; gap: 8px;">
                        <button class="qty-btn" style="padding: 2px 8px; cursor: pointer;" onclick="thayDoiSoLuong(${viTri}, -1)">-</button>
                        <span style="font-weight: bold; width: 20px; text-align: center;">${monHang.quantity}</span>
                        <button class="qty-btn" style="padding: 2px 8px; cursor: pointer;" onclick="thayDoiSoLuong(${viTri}, 1)">+</button>
                    </div>
                </div>
            `;
    });

    theHienTongTien.innerText = tongTien.toLocaleString("vi-VN") + " đ";
    cucDoBaoSoLuong.style.display = "block";
    cucDoBaoSoLuong.innerText = tongSoLuong;
  }

  window.thayDoiSoLuong = function (viTri, soLuongThayDoi) {
    gioHang[viTri].quantity += soLuongThayDoi;
    if (gioHang[viTri].quantity <= 0) gioHang.splice(viTri, 1);
    capNhatGioHang();
  };

  // --- BẮT SỰ KIỆN NÚT BẤM THÊM VÀO GIỎ ---
  const nutThemVaoGio = document.querySelectorAll(
    ".btn-add-cart:not(.btn-yeucau-dangnhap)",
  );
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

      // THÔNG BÁO THÊM VÀO GIỎ BẰNG SWEETALERT TOAST
      if (typeof Swal !== "undefined") {
        Swal.fire({
          toast: true,
          position: "top-end",
          icon: "success",
          title: "Đã thêm " + ten + " vào giỏ!",
          showConfirmButton: false,
          timer: 2000,
          timerProgressBar: true,
        });
      } else {
        alert("🛒 Đã thêm thành công: " + ten + " vào giỏ hàng!");
      }
    });
  });

  const cartBtn = document.getElementById("cart-btn");
  const closeCartBtn = document.getElementById("close-cart");
  const cartModal = document.getElementById("cart-modal");

  if (cartBtn && cartModal) {
    cartBtn.addEventListener(
      "click",
      () => (cartModal.style.display = "block"),
    );
  }
  if (closeCartBtn && cartModal) {
    closeCartBtn.addEventListener(
      "click",
      () => (cartModal.style.display = "none"),
    );
  }

  function veGioHangRaManHinh() {
    const divChuaHang = document.getElementById("cart-items");
    const theHienTongTien = document.getElementById("cart-total-price");
    const cucDoBaoSoLuong = document.getElementById("cart-count");

    if (!divChuaHang || !theHienTongTien || !cucDoBaoSoLuong) return;

    divChuaHang.innerHTML = "";
    let tongTien = 0;
    let tongSoLuong = 0;

    if (gioHang.length === 0) {
      divChuaHang.innerHTML =
        '<p style="text-align:center; color:#888; margin-top: 50px;">Chưa có sản phẩm nào trong giỏ.</p>';
      theHienTongTien.innerText = "0 VND"; // Đổi ở đây
      cucDoBaoSoLuong.style.display = "none";
      return;
    }

    gioHang.forEach((monHang, viTri) => {
      let thanhTienMon = monHang.price * monHang.quantity;
      tongTien += thanhTienMon;
      tongSoLuong += monHang.quantity;

      divChuaHang.innerHTML += `
                <div class="cart-item" style="border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 10px;">
                    <div class="cart-item-info" style="flex: 1;">
                        <h4 style="margin: 0 0 5px 0;">${monHang.name}</h4>
                        <span style="color: #888; font-size: 0.9em;">Đơn giá: ${monHang.price.toLocaleString("vi-VN")} VND</span>
                        <div style="color: #19a695; font-weight: bold; margin-top: 4px;">
                            Thành tiền: ${thanhTienMon.toLocaleString("vi-VN")} VND
                        </div>
                    </div>
                    <div class="qty-controls" style="display: flex; align-items: center; gap: 8px;">
                        <button class="qty-btn" style="padding: 2px 8px; cursor: pointer;" onclick="thayDoiSoLuong(${viTri}, -1)">-</button>
                        <span style="font-weight: bold; width: 20px; text-align: center;">${monHang.quantity}</span>
                        <button class="qty-btn" style="padding: 2px 8px; cursor: pointer;" onclick="thayDoiSoLuong(${viTri}, 1)">+</button>
                    </div>
                </div>
            `;
    });

    // Đổi chữ hiển thị tổng tiền ở đây
    theHienTongTien.innerText = tongTien.toLocaleString("vi-VN") + " VND";
    cucDoBaoSoLuong.style.display = "block";
    cucDoBaoSoLuong.innerText = tongSoLuong;
  }

  // XỬ LÝ NÚT ĐẶT ĐƠN
  const nutDatDon = document.querySelector(".btn-checkout");
  if (nutDatDon) {
    nutDatDon.addEventListener("click", function () {
      if (gioHang.length === 0) {
        if (typeof Swal !== "undefined")
          Swal.fire("Oops...", "Giỏ hàng đang trống!", "warning");
        else alert("Giỏ hàng của bạn đang trống!");
        return;
      }

      nutDatDon.innerHTML =
        '<i class="fa-solid fa-spinner fa-spin"></i> Đang xử lý...';
      nutDatDon.disabled = true;

      fetch("xuly_dathang.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ cart: gioHang }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            if (typeof Swal !== "undefined") {
              Swal.fire("Thành công!", data.message, "success");
            } else {
              alert("🎉 " + data.message);
            }
            gioHang = [];
            capNhatGioHang();
            if (cartModal) cartModal.style.display = "none";
          } else {
            if (typeof Swal !== "undefined")
              Swal.fire("Lỗi", data.message, "error");
            else alert("❌ Lỗi: " + data.message);
          }
        })
        .catch((error) => {
          console.error("Lỗi mạng:", error);
          alert("Có lỗi kết nối, vui lòng thử lại!");
        })
        .finally(() => {
          nutDatDon.innerHTML = "Đặt đơn";
          nutDatDon.disabled = false;
        });
    });
  }
});
