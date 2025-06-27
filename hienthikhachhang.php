<?php
// Bắt đầu session để kiểm tra thông tin đăng nhập và quyền
session_start();

// Kiểm tra nếu người dùng đã đăng nhập chưa
if (!isset($_SESSION['username'])) {
    echo "Vui lòng đăng nhập để tiếp tục.";
    exit();
}

// Lấy thông tin người dùng hiện tại từ session
$username = $_SESSION['username'];
$role = $_SESSION['role']; // Lấy vai trò của người dùng hiện tại

// Kết nối cơ sở dữ liệu
require 'config.php';

// Truy vấn lấy danh sách người dùng
$sql = "SELECT id, username, email, address, phone_number, role FROM users";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách người dùng</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Danh sách người dùng</h2>
    <p>Xin chào, <strong><?php echo htmlspecialchars($username); ?></strong>! Vai trò của bạn là: <strong><?php echo htmlspecialchars($role); ?></strong></p>

    <?php
    if ($result->num_rows > 0) {
        echo '<table class="table table-bordered">';
        echo '<thead>';
        echo '<tr><th>ID</th><th>Username</th><th>Email</th><th>Address</th><th>Phone Number</th><th>Role</th>';

        // Chỉ hiển thị cột "Hành động" cho Admin
        if ($role === 'Admin') {
            echo '<th>Hành động</th>';
        }

        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        // Hiển thị từng hàng dữ liệu
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['id']) . '</td>';
            echo '<td>' . htmlspecialchars($row['username']) . '</td>';
            echo '<td>' . htmlspecialchars($row['email']) . '</td>';
            echo '<td>' . htmlspecialchars($row['address']) . '</td>';
            echo '<td>' . htmlspecialchars($row['phone_number']) . '</td>';
            echo '<td>' . htmlspecialchars($row['role']) . '</td>';

            // Chỉ Admin mới có quyền sửa và xóa
            if ($role === 'Admin') {
                echo '<td>';
                echo '<a href="sua_user.php?id=' . $row['id'] . '" class="btn btn-warning btn-sm">Sửa</a> ';
                echo '<a href="xoa_user.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Bạn có chắc chắn muốn xóa tài khoản này?\');">Xóa</a>';
                echo '</td>';
            }

            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>Không có người dùng nào.</p>';
    }

    // Đóng kết nối cơ sở dữ liệu
    $conn->close();
    ?>

</div>

</body>
</html>
