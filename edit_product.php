<?php
// Import file config.php để kết nối với cơ sở dữ liệu
include 'config.php';
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['username'])) {
    echo "Vui lòng đăng nhập để tiếp tục.";
    exit();
}

// Lấy thông tin người dùng từ session
$username = $_SESSION['username'];
$role = $_SESSION['role']; // Lấy role của người dùng từ session

// Chỉ cho phép admin chỉnh sửa sản phẩm
if ($role != 'Admin') {
    echo "Bạn không có quyền chỉnh sửa sản phẩm này.";
    exit();
}

// Kiểm tra nếu product_id sản phẩm được cung cấp để sửa
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Truy vấn để lấy thông tin sản phẩm hiện tại
    $sql = "SELECT * FROM products WHERE product_id = '$product_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Không tìm thấy sản phẩm.";
        exit();
    }
}

// Kiểm tra nếu biểu mẫu đã được gửi để cập nhật
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_code = $_POST['product_code'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $size = $_POST['size'];
    $quantity = $_POST['quantity'];
    $image_url0 = $_POST['image_url0'];
    $image_url1 = $_POST['image_url1'];
    $image_url2 = $_POST['image_url2'];
    $image_url3 = $_POST['image_url3'];

    // Cập nhật thông tin sản phẩm
    $sql = "UPDATE products SET 
            product_code = '$product_code', 
            product_name = '$product_name', 
            price = '$price', 
            size = '$size', 
            quantity = '$quantity',
            image_url0 = '$image_url0',
            image_url1 = '$image_url1',
            image_url2 = '$image_url2',
            image_url3 = '$image_url3'
            WHERE product_id = '$product_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Cập nhật sản phẩm thành công.";
        header("Location: hienthisanpham.php"); // Điều hướng về trang danh sách sau khi cập nhật
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa sản phẩm</title>
</head>
<body>
    <h2>Sửa sản phẩm</h2>
    <p>Xin chào, <?php echo $username; ?> (Role: <?php echo $role; ?>)</p>
    <form action="" method="post">
        <label for="product_code">Mã sản phẩm:</label><br>
        <input type="text" id="product_code" name="product_code" value="<?php echo $product['product_code']; ?>" required><br><br>

        <label for="product_name">Tên sản phẩm:</label><br>
        <input type="text" id="product_name" name="product_name" value="<?php echo $product['product_name']; ?>" required><br><br>

        <label for="price">Giá:</label><br>
        <input type="number" step="0.01" id="price" name="price" value="<?php echo $product['price']; ?>" required><br><br>

        <label for="size">Kích thước:</label><br>
        <input type="text" id="size" name="size" value="<?php echo $product['size']; ?>" required><br><br>

        <label for="quantity">Số lượng:</label><br>
        <input type="number" id="quantity" name="quantity" value="<?php echo $product['quantity']; ?>" required><br><br>

        <label for="image_url0">Hình ảnh chính (URL):</label><br>
        <input type="text" id="image_url0" name="image_url0" value="<?php echo $product['image_url0']; ?>"><br><br>

        <label for="image_url1">Hình ảnh phụ 1 (URL):</label><br>
        <input type="text" id="image_url1" name="image_url1" value="<?php echo $product['image_url1']; ?>"><br><br>

        <label for="image_url2">Hình ảnh phụ 2 (URL):</label><br>
        <input type="text" id="image_url2" name="image_url2" value="<?php echo $product['image_url2']; ?>"><br><br>

        <label for="image_url3">Hình ảnh phụ 3 (URL):</label><br>
        <input type="text" id="image_url3" name="image_url3" value="<?php echo $product['image_url3']; ?>"><br><br>

        <input type="submit" value="Cập nhật">
    </form>
</body>
</html>
