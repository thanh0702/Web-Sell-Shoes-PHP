<?php
// Import file config.php để kết nối với cơ sở dữ liệu
include 'config.php';
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['username'])) {
    echo "Vui lòng đăng nhập để tiếp tục.";
    exit();
}

// Lấy thông tin người dùng từ session
$username = $_SESSION['username'];
$role = $_SESSION['role']; // Lấy role của người dùng từ session

// Chỉ cho phép admin xóa sản phẩm
if ($role != 'Admin') {
    echo "Bạn không có quyền xóa sản phẩm này.";
    exit();
}

// Lấy product_id sản phẩm cần xóa từ URL
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Chuẩn bị câu truy vấn để xóa sản phẩm
    $sql = "DELETE FROM products WHERE product_id = '$product_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Sản phẩm đã được xóa thành công.";
    } else {
        echo "Lỗi: " . $conn->error;
    }
} else {
    echo "Không tìm thấy sản phẩm để xóa.";
}

// Đóng kết nối
$conn->close();

// Điều hướng trở lại trang danh sách sản phẩm sau khi xóa
header("Location: hienthisanpham.php");
exit();
?>
