<?php
// Bắt đầu session để kiểm tra thông tin đăng nhập và quyền
session_start();

// Kiểm tra nếu người dùng đã đăng nhập chưa
if (!isset($_SESSION['username'])) {
    echo "Vui lòng đăng nhập để tiếp tục.";
    exit();
}

// Lấy thông tin người dùng hiện tại từ session
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Chỉ cho phép Admin truy cập trang này
if ($role !== 'Admin') {
    echo "Bạn không có quyền truy cập trang này.";
    exit();
}

// Kết nối cơ sở dữ liệu
require 'config.php';

// Kiểm tra nếu có ID người dùng trong URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Xác nhận hành động xóa
    if (isset($_POST['confirm'])) {
        // Xóa người dùng dựa trên ID
        $delete_sql = "DELETE FROM users WHERE id = '$user_id'";

        if ($conn->query($delete_sql) === TRUE) {
            echo "Xóa người dùng thành công!";
            // Điều hướng về trang danh sách người dùng sau khi xóa
            header("Location: hienthikhachhang.php");
            exit();
        } else {
            echo "Lỗi: " . $conn->error;
        }
    }
} else {
    echo "Không có ID người dùng.";
    exit();
}

// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa người dùng</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Xóa người dùng</h2>
    <p>Bạn có chắc chắn muốn xóa người dùng này không?</p>

    <form method="post" action="">
        <button type="submit" name="confirm" class="btn btn-danger">Xác nhận xóa</button>
        <a href="hienthikhachhang.php" class="btn btn-secondary">Hủy</a>
    </form>
</div>

</body>
</html>
