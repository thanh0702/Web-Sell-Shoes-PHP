<?php
// Kết nối với cơ sở dữ liệu từ file config.php
include 'config.php';

// Kiểm tra nếu có từ khóa tìm kiếm từ form
$search_keyword = isset($_GET['search']) ? $_GET['search'] : '';

// Tạo truy vấn SQL tìm kiếm sản phẩm theo tên, sử dụng '%'

$sql = "SELECT * FROM products 
        WHERE product_name LIKE '%" . $conn->real_escape_string($search_keyword) . "%' 
        ORDER BY product_code";

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
    <title>Bán giày fake</title>
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/indexx.css">
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
                        <input type="text" name="search" class="search-box" placeholder="Tìm kiếm thương hiệu, sản phẩm" value="<?php echo htmlspecialchars($search_keyword); ?>">
                        
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
                                echo '<div class="user-info"></div>';
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



    <!-- hero section -->
    <header class="hero-section">
        <div class="content">
            <img src="images/logogiay2.jpg" class="logo" alt="">
            <p class="sub-heading">best fashion collection of all time</p>
        </div>
    </header>

    <section class="product">
        <h2 class="product-category">best selling</h2>
    </section>

    <div class="product-container-wrapper">
        <button class="scroll-btn left-btn">❮</button>
        <div class="product-container">
            <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-image">
                    <span class="discount-tag">50% off</span>
                    <img src="<?php echo htmlspecialchars($product['image_url0']); ?>" class="product-thumb" alt="Product Image">
                    <a href="product_detail.php?product_code=<?php echo $product['product_code']; ?>"><button class="card-btn">Xem thông tin</button></a>
                </div>
                <div class="product-info">
                    <h2 class="product-brand"><?php echo htmlspecialchars($product['product_name']); ?></h2>
                    <p class="product-short-des"><?php echo htmlspecialchars($product['description']); ?></p>
                    <span class="price"><?php echo '$' . htmlspecialchars($product['price']); ?></span>
                    <span class="actual-price">$40</span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <button class="scroll-btn right-btn">❯</button>
    </div>

    <!-- collections -->
    <section class="collection-container">
        <a href="#" class="collection">
            <img src="images/giaynu.jpg" alt="">
            <p class="collection-title">women <br> apparels</p>
        </a>
        <a href="#" class="collection">
            <img src="images/giaynam.jpg" alt="">
            <p class="collection-title">men <br> apparels</p>
        </a>
        <a href="#" class="collection">
            <img src="images/jordan-2.webp" alt="">
            <p class="collection-title">accessories</p>
        </a>
    </section>

    <section class="product">
        <h2 class="product-category">Product</h2>
    </section>
    
    <div class="product-container">
    <?php foreach ($products as $product): ?>
        <?php if ($product['product_code'] >= 1 && $product['product_code'] <= 4): ?>
        <div class="product-card">
            <div class="product-image">
                <span class="discount-tag">50% off</span>
                <img src="<?php echo htmlspecialchars($product['image_url0']); ?>" class="product-thumb" alt="Product Image">
                <a href="product_detail.php?product_code=<?php echo $product['product_code']; ?>"><button class="card-btn">Xem thông tin</button></a>
            </div>
            <div class="product-info">
                <h2 class="product-brand"><?php echo htmlspecialchars($product['product_name']); ?></h2>
                <p class="product-short-des"><?php echo htmlspecialchars($product['description']); ?></p>
                <span class="price"><?php echo '$' . htmlspecialchars($product['price']); ?></span>
                <span class="actual-price">$40</span>
            </div>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>



<div class="product-container">
    <?php foreach ($products as $product): ?>
        <?php if ($product['product_code'] >= 5 && $product['product_code'] <= 8): ?>
        <div class="product-card">
            <div class="product-image">
                <span class="discount-tag">50% off</span>
                <img src="<?php echo htmlspecialchars($product['image_url0']); ?>" class="product-thumb" alt="Product Image">
                <a href="product_detail.php?product_code=<?php echo $product['product_code']; ?>"><button class="card-btn">Xem thông tin</button></a>
            </div>
            <div class="product-info">
                <h2 class="product-brand"><?php echo htmlspecialchars($product['product_name']); ?></h2>
                <p class="product-short-des"><?php echo htmlspecialchars($product['description']); ?></p>
                <span class="price"><?php echo '$' . htmlspecialchars($product['price']); ?></span>
                <span class="actual-price">$40</span>
            </div>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

      
      
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
    <p class="info">Nếu bạn đang tìm kiếm một trang web để mua và bán hàng trực tuyến thì webbangiay là một sự lựa chọn tuyệt vời dành cho bạn. webbangiay là trang thương mại điện tử cho phép người mua và người bán tương tác và trao đổi dễ dàng thông tin về sản phẩm và chương trình khuyến mãi của shop. Do đó, việc mua bán trên webbangiay trở nên nhanh chóng và đơn giản hơn. Bạn có thể trò chuyện trực tiếp với nhà bán hàng để hỏi trực tiếp về mặt hàng cần mua. Còn nếu bạn muốn tìm mua những dòng sản phẩm chính hãng, uy tín. Để bạn có thể dễ dàng khi tìm hiểu và sử dụng sản phẩm, webbangiay Blog - trang blog thông tin chính thức của webbangiay - sẽ giúp bạn có thể tìm được cho mình các kiến thức về xu hướng thời trang, mẹo làm đẹp, tin tức tiêu dùng và deal giá tốt bất ngờ.
Đến với webbangiay. Chỉ với vài thao tác trên ứng dụng, bạn đã có thể đăng bán ngay những sản phẩm của mình. Không những thế, các nhà bán hàng có thể tự tạo chương trình khuyến mãi trên webbangiay để thu hút người mua với những sản phẩm có mức giá hấp dẫn. Khi đăng nhập tại Shopee Kênh người bán, bạn có thể dễ dàng phân loại sản phẩm, theo dõi đơn hàng, chăm sóc khách hàng và cập nhập ngay các hoạt động của shop.
Bên cạnh đó, webbangiay hợp tác với nhiều đơn vị vận chuyển uy tín trên thị trường như SPX,... nhằm cung cấp dịch vu giao nhận và vận chuyển tiện lợi cho cả khách hàng và người bán. Cùng với nhiều ưu đãi với chi phí giao hàng hợp lý, Shopee đảm bảo cho khách hàng trải nghiệm mua sắm thuận tiện nhất.</p>
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
  <script src="js/scp.js"></script>
</body>
</html>