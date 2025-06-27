<?php
require 'config.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Kiểm tra xem có nhận được yêu cầu hành động từ người dùng hay không
if (isset($_POST['action']) && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $action = $_POST['action'];

    // Xử lý các hành động: tăng, giảm số lượng hoặc xóa sản phẩm
    if ($action == 'increase') {
        $sql = "UPDATE cart SET quantity = quantity + 1 WHERE username = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $username, $product_id);
        $stmt->execute();
    } elseif ($action == 'decrease') {
        // Giảm số lượng, nhưng không để số lượng nhỏ hơn 1
        $sql = "UPDATE cart SET quantity = quantity - 1 WHERE username = ? AND product_id = ? AND quantity > 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $username, $product_id);
        $stmt->execute();
    } elseif ($action == 'delete') {
        $sql = "DELETE FROM cart WHERE username = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $username, $product_id);
        $stmt->execute();
    }
}

// Quay trở lại trang giỏ hàng sau khi xử lý
header("Location: cart.php");
exit();
?>
