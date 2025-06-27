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
$role = $_SESSION['role'];

// Chỉ cho phép Admin truy cập trang này
if ($role !== 'Admin') {
    echo "Bạn không có quyền truy cập trang này.";
    exit();
}

// Kết nối cơ sở dữ liệu
require 'config.php';

// Kiểm tra nếu có ID người dùng trong URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Truy vấn lấy thông tin người dùng dựa trên ID
    $sql = "SELECT * FROM users WHERE id = '$user_id'";
    $result = $conn->query($sql);

    // Nếu tìm thấy người dùng
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "Người dùng không tồn tại.";
        exit();
    }
} else {
    echo "Không có ID người dùng.";
    exit();
}

// Xử lý form khi admin submit để cập nhật thông tin người dùng
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $role = $_POST['role'];

    // Cập nhật thông tin người dùng
    $update_sql = "UPDATE users SET username = '$username', email = '$email', address = '$address', phone_number = '$phone_number', role = '$role' WHERE id = '$user_id'";

    if ($conn->query($update_sql) === TRUE) {
        echo "Cập nhật thông tin người dùng thành công!";
        // Điều hướng về trang danh sách người dùng sau khi cập nhật
        header("Location: hienthikhachhang.php");
        exit();
    } else {
        echo "Lỗi: " . $conn->error;
    }
}

// Đóng kết nối
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa thông tin người dùng</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Sửa thông tin người dùng</h2>
    <form method="post" action="">
        <div class="form-group">
            <label for="username">Tên đăng nhập:</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="address">Địa chỉ:</label>
            <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>
        </div>
        <div class="form-group">
            <label for="phone_number">Số điện thoại:</label>
            <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required>
        </div>
        <div class="form-group">
            <label for="role">Vai trò:</label>
            <select class="form-control" id="role" name="role" required>
                <option value="Admin" <?php if ($user['role'] === 'Admin') echo 'selected'; ?>>Admin</option>
                <option value="Member" <?php if ($user['role'] === 'Member') echo 'selected'; ?>>Member</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>

</body>
</html>
