<?php
// Bắt đầu session để kiểm tra thông tin đăng nhập và quyền
session_start();

// Kiểm tra nếu người dùng đã đăng nhập chưa
if (!isset($_SESSION['username'])) {
    echo "Vui lòng đăng nhập để tiếp tục.";
    exit();
}

// Lấy thông tin người dùng từ session
$username = $_SESSION['username'];
$role = $_SESSION['role']; // Lấy vai trò người dùng (admin hoặc member)

// Kiểm tra quyền admin
if ($role != 'Admin') {
    echo "Xin lỗi $username, bạn không đủ quyền để thêm sản phẩm.";
    exit();
}

// Import file config.php để kết nối với cơ sở dữ liệu
include 'config.php';

// Kiểm tra xem form đã được submit hay chưa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_code = $_POST['product_code'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];

    // Lấy kích thước từ checkbox và chuyển thành chuỗi
    $sizes = isset($_POST['size']) ? implode(',', $_POST['size']) : ''; // Chuyển mảng thành chuỗi, ngăn cách bằng dấu phẩy

    $image_url0 = $_POST['image_url0'];
    $image_url1 = $_POST['image_url1'];
    $image_url2 = $_POST['image_url2'];
    $image_url3 = $_POST['image_url3'];
    $quantity = $_POST['quantity'];

    // Chuẩn bị câu lệnh SQL để thêm sản phẩm
    $sql = "INSERT INTO products (product_code, product_name, price, size, image_url0, image_url1, image_url2, image_url3, quantity) 
            VALUES ('$product_code', '$product_name', '$price', '$sizes', '$image_url0', '$image_url1', '$image_url2', '$image_url3', '$quantity')";

    // Thực hiện câu lệnh SQL
    if ($conn->query($sql) === TRUE) {
        echo "Thêm sản phẩm thành công!";
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
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
    <title>Thêm sản phẩm</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 600px;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
            color: #555;
        }

        input[type="text"], input[type="number"], .checkbox-container {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }

        .checkbox-container {
            display: flex;
            gap: 10px;
        }

        .checkbox-container input {
            margin-right: 5px;
        }

        .checkbox-container label {
            margin-right: 20px;
        }

        p {
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Thêm sản phẩm mới</h2>
        <p>Xin chào, <strong><?php echo $username; ?></strong>! Vai trò của bạn là: <strong><?php echo $role; ?></strong></p>
        <a href="quanly.php" class="back-btn">Quay lại trang Quản lý</a>
        <form action="" method="post">
            <label for="product_code">Mã sản phẩm:</label>
            <input type="text" id="product_code" name="product_code" required>

            <label for="product_name">Tên sản phẩm:</label>
            <input type="text" id="product_name" name="product_name" required>

            <label for="price">Giá:</label>
            <input type="number" step="0.01" id="price" name="price" required>

            <label for="size">Kích thước:</label>
            <div class="checkbox-container">
                <input type="checkbox" id="size39" name="size[]" value="39">
                <label for="size39">39</label>
                <input type="checkbox" id="size40" name="size[]" value="40">
                <label for="size40">40</label>
                <input type="checkbox" id="size41" name="size[]" value="41">
                <label for="size41">41</label>
                <input type="checkbox" id="size42" name="size[]" value="42">
                <label for="size42">42</label>
                <input type="checkbox" id="size43" name="size[]" value="43">
                <label for="size43">43</label>
            </div>

            <label for="image_url0">Đường dẫn ảnh chính:</label>
            <input type="text" id="image_url0" name="image_url0">

            <label for="image_url1">Đường dẫn ảnh 1:</label>
            <input type="text" id="image_url1" name="image_url1">

            <label for="image_url2">Đường dẫn ảnh 2:</label>
            <input type="text" id="image_url2" name="image_url2">

            <label for="image_url3">Đường dẫn ảnh 3:</label>
            <input type="text" id="image_url3" name="image_url3">

            <label for="quantity">Số lượng:</label>
            <input type="number" id="quantity" name="quantity" min="1" required>

            <input type="submit" value="Thêm sản phẩm">
        </form>
    </div>
</body>
</html>
