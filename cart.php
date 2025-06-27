<?php
    require 'config.php';
    session_start();

    // Kiểm tra xem người dùng đã đăng nhập hay chưa
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }

    $username = $_SESSION['username'];

    // Truy vấn sản phẩm trong giỏ hàng của người dùng
    $sql = "SELECT products.product_name, products.price, cart.quantity, cart.product_id, cart.size 
            FROM cart 
            INNER JOIN products ON cart.product_id = products.product_id 
            WHERE cart.username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Tổng tiền
    $totalPrice = 0;

    // Tìm kiếm sản phẩm (nếu có từ khóa tìm kiếm)
    $search_keyword = isset($_GET['search']) ? $_GET['search'] : '';
    if (!empty($search_keyword)) {
        $search_sql = "SELECT * FROM products WHERE product_name LIKE '%" . $conn->real_escape_string($search_keyword) . "%' ORDER BY product_code";
        $search_result = $conn->query($search_sql);
        if ($search_result->num_rows > 0) {
            $products = $search_result->fetch_all(MYSQLI_ASSOC);
        } else {
            echo "Không có sản phẩm nào!";
        }
    }
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng của bạn</title>
    <link rel="stylesheet" type="text/css" href="css/cart.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
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

<h2>Giỏ hàng của bạn</h2>
<!-- Button to view order history -->
<button onclick="window.location.href='order_history.php';" class="btn-profile">
    Xem thông tin đơn hàng
</button>

<?php
// Display the cart
if ($result->num_rows > 0) {
    echo '<table class="cart-table">';
    echo '<tr><th class="cart-header">Tên sản phẩm</th><th class="cart-header">Giá</th><th class="cart-header">Số lượng</th><th class="cart-header">Kích thước</th><th class="cart-header">Tổng tiền</th><th class="cart-header">Thao tác</th></tr>';
    
    while ($row = $result->fetch_assoc()) {
        $subtotal = $row['price'] * $row['quantity'];
        $totalPrice += $subtotal;

        echo '<tr class="cart-row">';
        echo '<td class="cart-item">' . htmlspecialchars($row['product_name']) . '</td>';
        echo '<td class="cart-item">' . htmlspecialchars($row['price']) . ' VND</td>';
        echo '<td class="cart-quantity">
                <form method="post" action="update_cart.php">
                    <input type="hidden" name="product_id" value="' . $row['product_id'] . '">
                    <button type="submit" name="action" value="decrease" class="quantity-btn">-</button>
                    ' . htmlspecialchars($row['quantity']) . '
                    <button type="submit" name="action" value="increase" class="quantity-btn">+</button>
                </form>
              </td>';
        echo '<td class="cart-size">' . htmlspecialchars($row['size']) . '</td>';
        echo '<td class="cart-subtotal">' . $subtotal . ' VND</td>';
        echo '<td class="cart-action">
                <form method="post" action="update_cart.php">
                    <input type="hidden" name="product_id" value="' . $row['product_id'] . '">
                    <button type="submit" name="action" value="delete" class="delete-btn">Xóa</button>
                </form>
              </td>';
        echo '</tr>';
    }
    echo '<tr class="cart-total"><td colspan="5" class="total-text">Tổng cộng:</td><td class="total-amount">' . $totalPrice . ' VND</td></tr>';
    echo '</table>';
} else {
    echo '<p class="empty-cart-message">Giỏ hàng của bạn đang trống!</p>';
}

if ($result->num_rows > 0) {
    // Add a checkout button
    echo '<form action="checkout.php" method="post">';
    echo '<input type="hidden" name="total_price" value="' . $totalPrice . '">';
    echo '<button type="submit" class="checkout-btn">Thanh toán</button>';
    echo '</form>';
} else {
    echo '<p class="empty-cart-message">Giỏ hàng của bạn đang trống!</p>';
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



