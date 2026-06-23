<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['ten_khach_hang'])) {
    echo json_encode(['success' => false, 'message' => 'Hết phiên làm việc']);
    exit();
}

$conn = new mysqli("localhost", "root", "", "pharmacity");
mysqli_set_charset($conn, "utf8");

$input = json_decode(file_get_contents('php://input'), true);

if ($input) {
    $username = $_SESSION['ten_khach_hang'];
    $sql = "UPDATE users SET fullname=?, email=?, sodt=?, ngaysinh=?, gioitinh=?, diachi=? WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $input['fullname'], $input['email'], $input['sodt'], $input['ngaysinh'], $input['gioitinh'], $input['diachi'], $username);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}
?>