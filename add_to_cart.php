<?php
session_start();
include 'config.php';

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$product_id = $_POST['product_id'];
$size = $_POST['size'];

// Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
$sql = "SELECT * FROM cart WHERE username = ? AND product_id = ? AND size = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sis', $username, $product_id, $size);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Nếu sản phẩm đã tồn tại, tăng số lượng
    $row = $result->fetch_assoc();
    $newQuantity = $row['quantity'] + 1;
    $sql = "UPDATE cart SET quantity = ? WHERE username = ? AND product_id = ? AND size = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isis', $newQuantity, $username, $product_id, $size);
} else {
    // Nếu sản phẩm chưa tồn tại, thêm sản phẩm vào giỏ hàng
    $sql = "INSERT INTO cart (username, product_id, size, quantity) VALUES (?, ?, ?, 1)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sis', $username, $product_id, $size);
}

$stmt->execute();

// Chuyển hướng về trang giỏ hàng
header("Location: cart.php");
exit();
?>
