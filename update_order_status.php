<?php
// Kết nối đến cơ sở dữ liệu
require 'config.php';

// Kiểm tra nếu form đã được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Truy vấn để cập nhật trạng thái đơn hàng
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
        echo "Order status updated successfully!";

        // Kiểm tra nếu trạng thái đơn hàng là 'delivered'
        if ($status === 'delivered') {
            // Truy vấn để lấy danh sách sản phẩm trong đơn hàng
            $order_items_sql = "SELECT product_id, quantity FROM order_details WHERE order_id = ?";
            $stmt_items = $conn->prepare($order_items_sql);
            $stmt_items->bind_param("i", $order_id);
            $stmt_items->execute();
            $result_items = $stmt_items->get_result();

            // Trừ số lượng sản phẩm trong kho tương ứng với số lượng mua
            while ($item = $result_items->fetch_assoc()) {
                $product_id = $item['product_id'];
                $quantity_bought = $item['quantity'];

                // Cập nhật số lượng sản phẩm trong kho
                $update_stock_sql = "UPDATE products SET quantity = quantity - ? WHERE product_id = ?";
                $stmt_update_stock = $conn->prepare($update_stock_sql);
                $stmt_update_stock->bind_param("ii", $quantity_bought, $product_id);
                $stmt_update_stock->execute();
                $stmt_update_stock->close();
            }
            $stmt_items->close();
        }

    } else {
        echo "Error updating status: " . $conn->error;
    }

    // Đóng kết nối
    $stmt->close();
    $conn->close();
}

// Quay lại trang danh sách đơn hàng
header("Location: hienthidonhang.php");
exit();
?>
