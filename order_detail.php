<?php
require 'config.php';
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$order_id = $_GET['order_id'];

// Lấy thông tin chi tiết đơn hàng
$sql = "SELECT products.product_name, order_details.quantity, order_details.price
        FROM order_details
        INNER JOIN products ON order_details.product_id = products.product_id
        WHERE order_details.order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng</title>
    <link rel="stylesheet" type="text/css" href="css/order_history.css">
    <link rel="stylesheet" type="text/css" href="css/order_detail.css">
</head>
<body>
    <h2>Chi tiết đơn hàng #<?php echo htmlspecialchars($order_id); ?></h2>

    <?php
if ($result->num_rows > 0) {
    echo '<table class="order-detail-table">';
    echo '<tr><th>Tên sản phẩm</th><th>Số lượng</th><th>Giá</th><th>Tổng tiền</th></tr>';
    while ($row = $result->fetch_assoc()) {
        $subtotal = $row['quantity'] * $row['price'];
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['product_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['quantity']) . '</td>';
        echo '<td>' . htmlspecialchars(number_format($row['price'], 0, ',', '.')) . ' VND</td>';
        echo '<td>' . number_format($subtotal, 0, ',', '.') . ' VND</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo '<p>Không có chi tiết đơn hàng.</p>';
}
?>


    <a href="order_history.php">Quay lại lịch sử đơn hàng</a>
</body>
</html>
