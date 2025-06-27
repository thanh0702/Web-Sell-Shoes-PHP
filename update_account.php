<?php
session_start();
require 'config.php'; // Kết nối đến cơ sở dữ liệu

// Kiểm tra xem người dùng có gửi yêu cầu POST không
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $current_username = $_SESSION['username']; // Giả sử username hiện tại được lưu trong session

    // Kiểm tra xem tất cả trường có được điền không
    if (empty($username) || empty($phone_number) || empty($email) || empty($address)) {
        echo "Vui lòng điền đầy đủ thông tin!";
        exit();
    }

    // Cập nhật thông tin vào cơ sở dữ liệu (không bao gồm mật khẩu)
    $sql = "UPDATE users SET username = ?, phone_number = ?, email = ?, address = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $username, $phone_number, $email, $address, $current_username);

    if ($stmt->execute()) {
        // Cập nhật session với username mới
        $_SESSION['username'] = $username;
        $_SESSION['phone_number'] = $phone_number;
        $_SESSION['email'] = $email;
        $_SESSION['address'] = $address;

        echo "Cập nhật thông tin thành công!";
        header("Location: profile.php"); // Quay lại trang thông tin tài khoản
        exit();
    } else {
        echo "Lỗi: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
