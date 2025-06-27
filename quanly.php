<?php
session_start();

// Kiểm tra nếu người dùng đã đăng nhập và là admin
if (!isset($_SESSION['username'])) {
    // Chưa đăng nhập, chuyển hướng về trang login
    header("Location: login.php");
    exit();
}

// Kết nối đến cơ sở dữ liệu
require 'config.php';

// Lấy thông tin user hiện tại
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Kiểm tra nếu người dùng không phải là admin
if ($user['role'] !== 'Admin') {
    echo "Bạn không có quyền truy cập trang này!";
    exit();
}

// Hiển thị thông báo chào mừng Admin
echo "<h2>Chào mừng: " . htmlspecialchars($username) . " đến trang quản lý!</h2>";

// Truy vấn để tính tổng doanh thu từ các đơn hàng đã giao (delivered)
$revenue_query = "
    SELECT SUM(od.quantity * od.price) AS total_revenue
    FROM order_details od
    JOIN orders o ON od.order_id = o.order_id
    WHERE o.status = 'delivered'";
$revenue_result = $conn->query($revenue_query);
$revenue_row = $revenue_result->fetch_assoc();
$total_revenue = $revenue_row['total_revenue'];

// Đóng kết nối cơ sở dữ liệu
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Lý</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
        }
        .welcome-message {
            font-size: 24px;
            margin-bottom: 30px;
        }
        .button-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .button-container a {
            display: inline-block;
            padding: 10px 20px;
            text-decoration: none;
            background-color:rgb(10, 10, 10);
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .button-container a:hover {
            background-color:rgb(19, 19, 19);
        }
        .logout-btn {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            text-decoration: none;
            background-color: #f44336;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .logout-btn:hover {
            background-color: #e53935;
        }
        .revenue {
            font-size: 20px;
            margin-top: 20px;
            color: #2c3e50;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="welcome-message">
        <p>Chào mừng, Admin!</p>
    </div>

    <div class="button-container">
        <!-- Button để điều hướng đến các trang khác -->
        <a href="hienthisanpham.php">Hiển thị sản phẩm</a>
        <a href="hienthidonhang.php">Hiển thị đơn hàng</a>
        <a href="hienthikhachhang.php">Hiển thị khách hàng</a>
    </div>

    <!-- Hiển thị tổng doanh thu -->
    <div class="revenue">
        <p>Tổng doanh thu từ đơn hàng đã giao: <?php echo number_format($total_revenue, 0, ',', '.') ?> VND</p>
    </div>

    <!-- Nút đăng xuất -->
    <a href="logout.php" class="logout-btn">Đăng xuất</a>
</div>

</body>
</html>
