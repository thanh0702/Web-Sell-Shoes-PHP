<?php
require 'config.php';
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$totalPrice = $_POST['total_price'] ?? 0;

// Bắt đầu transaction
$conn->begin_transaction();

try {
    // Bước 1: Thêm đơn hàng vào bảng orders
    $sql = "INSERT INTO orders (username, total_price, order_date, status) VALUES (?, ?, NOW(), 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sd", $username, $totalPrice);
    $stmt->execute();
    
    // Lấy ID của đơn hàng vừa thêm
    $order_id = $conn->insert_id;

    // Bước 2: Lấy thông tin giỏ hàng và giá sản phẩm từ bảng products để thêm vào order_details
    $sql = "SELECT cart.product_id, cart.quantity, products.price 
            FROM cart 
            INNER JOIN products ON cart.product_id = products.product_id 
            WHERE cart.username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $product_id = $row['product_id'];
        $quantity = $row['quantity'];
        $price = $row['price'];

        // Thêm vào order_details
        $sql = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
        $stmt->execute();
    }

    // Bước 3: Xóa giỏ hàng sau khi thanh toán
    $sql = "DELETE FROM cart WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    // Commit transaction
    $conn->commit();

    // Thông báo thành công
    echo '<h2>Thanh toán thành công!</h2>';
    echo '<p>Cảm ơn bạn đã mua sắm tại cửa hàng của chúng tôi.</p>';
    echo '<a href="order_history.php">Xem đơn hàng của bạn</a>';

} catch (Exception $e) {
    // Rollback nếu có lỗi xảy ra
    $conn->rollback();
    echo '<h2>Thanh toán thất bại!</h2>';
    echo '<p>Đã xảy ra lỗi trong quá trình xử lý thanh toán. Lỗi chi tiết: ' . $e->getMessage() . '</p>';
}
?>
