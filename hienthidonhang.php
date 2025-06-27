<?php
session_start();
require 'config.php';

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['username'])) {
    // Chưa đăng nhập, chuyển hướng về trang login
    header("Location: login.php");
    exit();
}

// Lấy thông tin user hiện tại
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Kiểm tra role của người dùng
$is_admin = ($user['role'] === 'Admin');

// Truy vấn để lấy danh sách các đơn hàng
$sql = "SELECT * FROM orders";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .back-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<!-- Hiển thị lời chào user -->
<h2>Xin chào, <?php echo htmlspecialchars($username); ?>!</h2>

<!-- Nút quay lại trang quản lý -->
<a href="quanly.php" class="back-btn">Quay lại trang Quản lý</a>

<h2>Order List</h2>

<?php if ($result->num_rows > 0): ?>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Username</th>
            <th>Order Date</th>
            <th>Total Price</th>
            <th>Status</th>
            <?php if ($is_admin): ?>
                <th>Update Status</th>
            <?php endif; ?>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['order_id']); ?></td>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo htmlspecialchars($row['order_date']); ?></td>
            <td><?php echo htmlspecialchars($row['total_price']); ?> VND</td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <?php if ($is_admin): ?>
                <td>
                    <!-- Form để cập nhật trạng thái đơn hàng (Admin only) -->
                    <form action="update_order_status.php" method="POST">
                        <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                        <select name="status">
                            <option value="pending" <?php if ($row['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                            <option value="processed" <?php if ($row['status'] == 'processed') echo 'selected'; ?>>Processed</option>
                            <option value="shipped" <?php if ($row['status'] == 'shipped') echo 'selected'; ?>>Shipped</option>
                            <option value="delivered" <?php if ($row['status'] == 'delivered') echo 'selected'; ?>>Delivered</option>
                            <option value="cancelled" <?php if ($row['status'] == 'cancelled') echo 'selected'; ?>>Cancelled</option>
                        </select>
                        <button type="submit">Update</button>
                        <td>
    <a href="order_details.php?order_id=<?php echo $row['order_id']; ?>">Xem chi tiết</a>
</td>
                    </form>
                </td>
            <?php else: ?>
                <td>Bạn không đủ quyền để chỉnh sửa trạng thái đơn hàng.</td>
            <?php endif; ?>
        </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No orders found.</p>
<?php endif; ?>


</body>
</html>

<?php
$conn->close();
?>
