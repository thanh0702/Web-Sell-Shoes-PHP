<?php
require 'config.php';
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Lấy danh sách đơn hàng của người dùng từ bảng orders
$sql = "SELECT order_id, order_date, total_price, status FROM orders WHERE username = ? ORDER BY order_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$order_result = $stmt->get_result(); // Lưu kết quả đơn hàng

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

$product_result = $conn->query($sql); // Lưu kết quả sản phẩm

// Kiểm tra nếu có sản phẩm
if ($product_result->num_rows > 0) {
    $products = $product_result->fetch_all(MYSQLI_ASSOC);
} else {
    echo "Không có sản phẩm nào!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch sử đơn hàng</title>
    <link rel="stylesheet" type="text/css" href="css/order_history.css">
</head>
<body class="tongthe">
<nav class="navbar">
    <div class="nav">
        <a href="Index.php"><img src="images/logonike.jpg" class="brand-logo" alt="Logo"></a>
        <div class="nav-items">
            <div class="search">
                <form method="GET" action="index.php">
                    <input type="text" name="search" class="search-box" placeholder="Tìm kiếm thương hiệu, sản phẩm" value="<?php echo htmlspecialchars($search_keyword); ?>">
                </form>
            </div>
            <div class="tentaikhoan">
                <div class="dropdown">
                    <a href="profile.php"><img src="images/dangnhap.png" alt="Đăng nhập"></a>
                    <div class="dropdown-content">
                        <?php
                        if (isset($_SESSION['username'])) {
                            echo '<a href="profile.php">Thông tin tài khoản</a>';
                            echo '<a href="cart.php">Giỏ hàng</a>';
                            echo '<a href="logout.php">Đăng xuất</a>';
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

<h2>Lịch sử đơn hàng của bạn</h2>

<?php
if ($order_result->num_rows > 0) {
    echo '<table class="order-table">';
    echo '<tr><th>Mã đơn hàng</th><th>Ngày đặt hàng</th><th>Tổng tiền</th><th>Trạng thái</th><th>Chi tiết</th></tr>';
    while ($row = $order_result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['order_id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['order_date']) . '</td>';
        echo '<td>' . htmlspecialchars($row['total_price']) . ' VND</td>';
        echo '<td>' . htmlspecialchars($row['status']) . '</td>';
        echo '<td><a href="order_detail.php?order_id=' . $row['order_id'] . '">Xem chi tiết</a></td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo '<p>Bạn chưa có đơn hàng nào.</p>';
}
?>
<footer>
        <!-- Thông tin footer -->
 

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
        <p class="info">Nếu bạn đang tìm kiếm một trang web để mua và bán hàng trực tuyến thì webbangiay là một sự lựa chọn tuyệt vời dành cho bạn. webbangiay là trang thương mại điện tử cho phép người mua và người bán tương tác và trao đổi dễ dàng thông tin về sản phẩm và chương trình khuyến mãi của shop. Do đó, việc mua bán trên webbangiay trở nên nhanh chóng và đơn giản hơn. Bạn có thể trò chuyện trực tiếp với nhà bán hàng để hỏi trực tiếp về mặt hàng cần mua. Còn nếu bạn muốn tìm mua những dòng sản phẩm chính hãng, uy tín. Để bạn có thể dễ dàng khi tìm hiểu và sử dụng sản phẩm, webbangiay Blog - trang blog thông tin chính thức của webbangiay - sẽ giúp bạn có thể tìm được cho mình các kiến thức về xu hướng thời trang, mẹo làm đẹp, tin tức tiêu dùng và deal giá tốt bất ngờ. Đến với webbangiay. Chỉ với vài thao tác trên ứng dụng, bạn đã có thể đăng bán ngay những sản phẩm của mình. Không những thế, các nhà bán hàng có thể tự tạo chương trình khuyến mãi trên webbangiay để thu hút người mua với những sản phẩm có mức giá hấp dẫn. Khi đăng nhập tại Shopee Kênh người bán, bạn có thể dễ dàng phân loại sản phẩm, theo dõi đơn hàng, chăm sóc khách hàng và cập nhập ngay các hoạt động của shop. Bên cạnh đó, webbangiay hợp tác với nhiều đơn vị vận chuyển uy tín trên thị trường như SPX,... nhằm cung cấp dịch vu giao nhận và vận chuyển tiện lợi cho cả khách hàng và người bán. Cùng với nhiều ưu đãi với chi phí giao hàng hợp lý, Shopee đảm bảo cho khách hàng trải nghiệm mua sắm thuận tiện nhất.</p>
        <p class="info">support emails - help@clothing.com, customersupport@clothing.com</p>
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
    <script src="js/scriptt.js"></script>
</body>
</html>
