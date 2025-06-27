<?php
// Kết nối với cơ sở dữ liệu từ file config.php
include 'config.php';

// Kiểm tra nếu có từ khóa tìm kiếm từ form
$search_keyword = isset($_GET['search']) ? $_GET['search'] : '';

// Kiểm tra nếu có bộ lọc giá từ form
$price_filter = isset($_GET['price_filter']) ? $_GET['price_filter'] : '';

// Tạo truy vấn SQL dựa trên từ khóa tìm kiếm và bộ lọc giá
$sql = "SELECT * FROM products WHERE 1=1"; // Khởi tạo truy vấn với điều kiện đúng

if (!empty($search_keyword)) {
    // Tìm kiếm sản phẩm theo tên
    $sql .= " AND product_name LIKE '%" . $conn->real_escape_string($search_keyword) . "%'";
}

if (!empty($price_filter)) {
    // Lọc theo khoảng giá
    switch ($price_filter) {
        case '100-150':
            $sql .= " AND price BETWEEN 100 AND 150";
            break;
        case '150-300':
            $sql .= " AND price BETWEEN 150 AND 300";
            break;
        case '300-500':
            $sql .= " AND price BETWEEN 300 AND 500";
            break;
    }
}

// Thực hiện truy vấn với sắp xếp theo product_code
$sql .= " ORDER BY product_code";

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

   
    
   

    <section class="product">
        <h2 class="product-category">Products</h2>
    </section>
    <div class="seachting">
    <form method="GET" action="products.php">
        <!-- Bộ lọc giá -->
        <label for="price_filter">Lọc theo giá:</label><br>
        <input type="radio" name="price_filter" value="100-150" <?php if(isset($_GET['price_filter']) && $_GET['price_filter'] == '100-150') echo 'checked'; ?>> 100 - 150<br>
        <input type="radio" name="price_filter" value="150-300" <?php if(isset($_GET['price_filter']) && $_GET['price_filter'] == '150-300') echo 'checked'; ?>> 150 - 300<br>
        <input type="radio" name="price_filter" value="300-500" <?php if(isset($_GET['price_filter']) && $_GET['price_filter'] == '300-500') echo 'checked'; ?>> 300 - 500<br>

        <input type="submit" value="Tìm kiếm">
    </form>
</div>

    </header>
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
Đến với webbangiay.</p>
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