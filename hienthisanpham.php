<?php
session_start();
include 'config.php';

// Lấy thông tin user hiện tại
$username = $_SESSION['username'];

// Truy vấn lấy role của user từ cơ sở dữ liệu
$stmt = $conn->prepare("SELECT role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$userRole = $user['role'];

// Truy vấn lấy tất cả các sản phẩm
$sql = "SELECT product_id, product_code, product_name, price, size, quantity, image_url0, image_url1, image_url2, image_url3 FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sản phẩm</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            max-width: 100px;
            height: auto;
        }
        .action-links {
            margin-top: 10px;
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
    <h2>Danh sách sản phẩm</h2>
    <p>Xin chào, <?php echo htmlspecialchars($username); ?>!</p>
    <a href="quanly.php" class="back-btn">Quay lại trang Quản lý</a>
    <a href="themsanpham.php" class="back-btn">Thêm sản phẩm</a>
    <table>
        <tr>
            <th>Mã sản phẩm</th>
            <th>Tên sản phẩm</th>
            <th>Giá</th>
            <th>Kích thước</th>
            <th>Số lượng</th>
            <th>Hình ảnh</th>
            <th>Hành động</th>
        </tr>

        <?php
        // Kiểm tra xem có sản phẩm nào trong cơ sở dữ liệu hay không
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["product_code"] . "</td>";
                echo "<td>" . $row["product_name"] . "</td>";
                echo "<td>" . number_format($row["price"], 2) . " VND</td>";
                echo "<td>" . $row["size"] . "</td>";
                echo "<td>" . $row["quantity"] . "</td>";
                echo "<td>";

                // Hiển thị hình ảnh sản phẩm nếu có
                if (!empty($row["image_url0"])) {
                    echo "<img src='" . $row["image_url0"] . "' alt='Hình ảnh sản phẩm'><br>";
                }
                if (!empty($row["image_url1"])) {
                    echo "<img src='" . $row["image_url1"] . "' alt='Hình ảnh phụ 1'><br>";
                }
                if (!empty($row["image_url2"])) {
                    echo "<img src='" . $row["image_url2"] . "' alt='Hình ảnh phụ 2'><br>";
                }
                if (!empty($row["image_url3"])) {
                    echo "<img src='" . $row["image_url3"] . "' alt='Hình ảnh phụ 3'><br>";
                }

                if (empty($row["image_url0"]) && empty($row["image_url1"]) && empty($row["image_url2"]) && empty($row["image_url3"])) {
                    echo "Không có ảnh";
                }

                echo "</td>";

                // Kiểm tra quyền của user
                echo "<td class='action-links'>";
                if ($userRole == 'Admin') {
                   
                    echo "<a href='edit_product.php?product_id=" . $row["product_id"] . "'>Sửa</a> | ";
                    echo "<a href='delete_product.php?product_id=" . $row["product_id"] . "' onclick='return confirm(\"Bạn có chắc chắn muốn xóa sản phẩm này?\")'>Xóa</a>";
                } else {
                    echo "Bạn không đủ quyền";
                }
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>Không có sản phẩm nào.</td></tr>";
        }
        ?>

    </table>

    <?php
    // Đóng kết nối
    $conn->close();
    ?>
</body>
</html>
