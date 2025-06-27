<?php
// Kết nối với cơ sở dữ liệu từ file config.php
include 'config.php';
// Kiểm tra nếu có từ khóa tìm kiếm từ form
$search_keyword = isset($_GET['search']) ? $_GET['search'] : '';

// Tạo truy vấn SQL dựa trên từ khóa tìm kiếm
if (!empty($search_keyword)) {
    // Tìm kiếm sản phẩm theo tên
    $sql = "SELECT * FROM products WHERE product_name LIKE '%" . $conn->real_escape_string($search_keyword) . "%' ORDER BY product_code";
} else {
    // Nếu không có từ khóa tìm kiếm, hiển thị sản phẩm mặc định
    $sql = "SELECT * FROM products WHERE product_code BETWEEN 1 AND 5 ORDER BY product_code";
}

$result = $conn->query($sql);
// Kiểm tra nếu có sản phẩm
if ($result->num_rows > 0) {
    $products = $result->fetch_all(MYSQLI_ASSOC);
} else {
    echo "Không có sản phẩm nào!";
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin tài khoản</title>
    
    <link rel="stylesheet" type="text/css" href="css/profile.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="tongthe">
    <nav class="navbar">
        <div class="nav">
            <a href="Index.php"><img src="images/logonike.jpg" class="brand-logo" alt="Logo"></a>
            <div class="nav-items">
            <div class="search">
                    <form method="GET" action="index.php">
                        <input type="text-area" name="search" class="search-box" placeholder="Tìm kiếm thương hiệu, sản phẩm" value="<?php echo htmlspecialchars($search_keyword); ?>">
                        
                    </form>
                    
                </div>
                <div class="tentaikhoan">
                    <div class="dropdown">
                        <a href="profile.php"><img src="images/dangnhap.png" alt="Đăng nhập"></a>
                        <div class="dropdown-content">
                            <?php
                            session_start();
                            if (isset($_SESSION['username'])) {
                                echo '<a href="profile.php">Thông tin tài khoản</a>';
                                echo '<a href="cart.php">Giỏ hàng</a>';
                                echo '<a href="logout.php">Đăng xuất</a>';
                            } else {
                                echo '<a href="login.php">Đăng nhập</a>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                if (isset($_SESSION['username'])) {
                    echo '<div class="user-info">';
                    echo '<p>Xin chào, ' . htmlspecialchars($_SESSION['username']) . '</p>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
        <ul class="links-container">
        <li class="link-item"><a href="Index.php" class="link">Home</a></li>
        <li class="link-item"><a href="products.php" class="link">Women</a></li>
        <li class="link-item"><a href="products.php" class="link">Men</a></li>
        <li class="link-item"><a href="Index.php" class="link">Kids</a></li>
        <li class="link-item"><a href="#" class="link">Accessories</a></li>
    </ul>
    </nav>
   
    
    <?php
    // Lấy thông tin tài khoản từ session
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
    $phone_number = isset($_SESSION['phone_number']) ? $_SESSION['phone_number'] : '';
    $address = isset($_SESSION['address']) ? $_SESSION['address'] : '';
    ?>

    <div class="account-section">
        <div class="account-info">
            <h3>Thông tin tài khoản</h3>
            <p>Xin chào, <?php echo htmlspecialchars($username); ?>!</p>
            <p>Đây là thông tin tài khoản của bạn. Bạn có thể thay đổi tên tài khoản, số điện thoại, email, và địa chỉ bên dưới.</p>
        </div>
        <div class="edit-profile-user">
            <h3>Chỉnh sửa Thông tin Tài khoản</h3>
            
            <form action="update_account.php" method="POST" class="profile-user-form">
                <label for="username">Username mới:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" placeholder="Nhập username mới" required>
                
                <label for="phone_number">Số điện thoại mới:</label>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>" placeholder="Nhập số điện thoại mới" required>
                
                <label for="email">Email mới:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Nhập email mới" required>
                
                <label for="address">Địa chỉ mới:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>" placeholder="Nhập địa chỉ mới" required>
                
                <button type="submit" class="save-profile">Lưu thay đổi</button>
            </form>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <img src="img/light-logo.png" class="logo" alt="">
            <div class="footer-ul-container">
                <ul class="category">
                    <li class="category-title">men</li>
                    <li><a href="#" class="footer-link">t-shirts</a></li>
                    <li><a href="#" class="footer-link">sweatshirts</a></li>
                    <li><a href="#" class="footer-link">shirts</a></li>
                    <li><a href="#" class="footer-link">jeans</a></li>
                    <li><a href="#" class="footer-link">trousers</a></li>
                    <li><a href="#" class="footer-link">shoes</a></li>
                    <li><a href="#" class="footer-link">casuals</a></li>
                    <li><a href="#" class="footer-link">formals</a></li>
                    <li><a href="#" class="footer-link">sports</a></li>
                    <li><a href="#" class="footer-link">watch</a></li>
                </ul>
                <ul class="category">
                    <li class="category-title">women</li>
                    <li><a href="#" class="footer-link">t-shirts</a></li>
                    <li><a href="#" class="footer-link">sweatshirts</a></li>
                    <li><a href="#" class="footer-link">shirts</a></li>
                    <li><a href="#" class="footer-link">jeans</a></li>
                    <li><a href="#" class="footer-link">trousers</a></li>
                    <li><a href="#" class="footer-link">shoes</a></li>
                    <li><a href="#" class="footer-link">casuals</a></li>
                    <li><a href="#" class="footer-link">formals</a></li>
                    <li><a href="#" class="footer-link">sports</a></li>
                    <li><a href="#" class="footer-link">watch</a></li>
                </ul>
            </div>
        </div>
    </footer>
    <footer>
        <p class="footer-title">about company</p>
        <p class="info">Webbangiay là trang thương mại điện tử... [Tiếp tục phần thông tin giới thiệu]</p>
        <p class="info">support emails - 20221455@eaut.edu.vn, 20221455@eaut.edu.vn</p>
        <p class="info">telephone - 180 00 00 001, 180 00 00 002</p>
        <div class="footer-social-container">
            <div>
                <a href="#" class="social-link">terms & services</a>
                <a href="#" class="social-link">privacy page</a>
            </div>
            <div>
                <a href="#" class="social-link">instagram</a>
                <a href="#" class="social-link">facebook</a>
                <a href="#" class="social-link">twitter</a>
            </div>
        </div>
        <p class="footer-credit">Clothing, Best apparels online store</p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
