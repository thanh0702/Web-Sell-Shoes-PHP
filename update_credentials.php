password_hash<?php
session_start();
require 'config.php'; // Kết nối đến cơ sở dữ liệu

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $current_username = $_SESSION['username']; // Giả sử username hiện tại được lưu trong session

    // Kiểm tra mật khẩu khớp
    if ($password !== $confirm_password) {
        echo "Mật khẩu và xác nhận mật khẩu không khớp!";
        exit();
    }

    // Hash mật khẩu mới để bảo mật
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Cập nhật thông tin vào cơ sở dữ liệu
    $sql = "UPDATE users SET username='$username', password='$hashed_password' WHERE username='$current_username'";
    if (mysqli_query($conn, $sql)) {
        // Cập nhật session với username mới
        $_SESSION['username'] = $username;
        echo "Cập nhật thông tin thành công!";
        header("Location: profile.php"); // Quay lại trang thông tin tài khoản
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
}
?>
