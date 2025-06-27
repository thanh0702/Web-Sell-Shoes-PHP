<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_system"; // Thay bằng tên cơ sở dữ liệu của bạn

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Nhận dữ liệu từ AJAX
$size = $_POST['size'];
$product_id = $_POST['product_id'];

// Truy xuất kích thước có sẵn từ cơ sở dữ liệu
$sql = "SELECT sizes_available FROM sanpham WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->bind_result($sizes_available);
$stmt->fetch();
$stmt->close();

$sizes_array = explode(',', $sizes_available);

// Kiểm tra xem kích thước có nằm trong danh sách kích thước có sẵn không
if (in_array($size, $sizes_array)) {
    echo 'available';
} else {
    echo 'out_of_stock';
}

$conn->close();
?>
