<?php
// Kết nối với cơ sở dữ liệu từ file config.php
include 'config.php';

// Lấy product_code từ URL hoặc đặt mặc định là 1
$product_code = isset($_GET['product_code']) ? $_GET['product_code'] : 1;

// Truy vấn lấy thông tin sản phẩm từ bảng products
$sql = "SELECT * FROM products WHERE product_code = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $product_code); // Nếu product_code là kiểu string
$stmt->execute();
$result = $stmt->get_result();

// Kiểm tra nếu có sản phẩm
if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
    // Lấy kích thước từ cột size và chuyển thành một mảng (nếu lưu dưới dạng chuỗi)
    $availableSizes = explode(',', $product['size']); 
} else {
    echo "Sản phẩm không tồn tại!";
    exit;
}
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
    <title>Thông tin sản phẩm</title>
    <link rel="stylesheet" type="text/css" href="css/product_detail.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
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
                // Nếu đã đăng nhập, hiển thị thông tin tài khoản và nút đăng xuất
                echo '<a href="profile.php">Thông tin tài khoản</a>';
                echo '<a href="cart.php">Giỏ hàng</a>';
                echo '<a href="logout.php">Đăng xuất</a>';
                echo '<div class="user-info">';
                echo '</div>';
            } else {
                // Nếu chưa đăng nhập, chỉ hiển thị nút đăng nhập
                echo '<a href="login.php">Đăng nhập</a>';
            }
            ?>
        </div>
    </div>
</div>


                    <!-- PHP kiểm tra session và hiển thị thông tin người dùng -->
                    <?php

if (isset($_SESSION['username'])) {
    echo '<div class="user-info">';
    echo '<p>Xin chào, ' . htmlspecialchars($_SESSION['username']) . '</p>';
    echo '</div>';
}
?>

                </div>
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
    <div class="product-container">
        <?php if (isset($product)): ?>
            <div class="product-image">
                <img src="<?php echo $product['image_url0']; ?>" alt="<?php echo $product['product_name']; ?>">
                <div class="image-thumbnails">
                    <img src="<?php echo $product['image_url0']; ?>" alt="Thumbnail 1">
                    <img src="<?php echo $product['image_url1']; ?>" alt="Thumbnail 2">
                    <img src="<?php echo $product['image_url2']; ?>" alt="Thumbnail 3">
                    <img src="<?php echo $product['image_url3']; ?>" alt="Thumbnail 4">
                </div>
            </div>
            <div class="product-details">
    <h1><?php echo $product['product_name']; ?></h1>
    <p>Lorem Ipsum Dolor Sit, Amet Consectetur Adipisicing Elit.</p>
    <p>Số lượng: <?php echo $product['quantity']; ?></p>
    <div class="price">
        <span class="current-price"><?php echo '$' . $product['price']; ?></span>
        <span class="original-price">$300</span>
        <span class="discount">(50% off)</span>
    </div>

    <form action="add_to_cart.php" method="POST">
        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
        <input type="hidden" name="product_name" value="<?php echo $product['product_name']; ?>">
        <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
        
        <div class="size-selection">
    <p>Chọn Kích Thước</p>
    <div class="sizes">
        <?php foreach ($availableSizes as $size): ?>
            <input type="radio" id="size_<?php echo $size; ?>" name="size" value="<?php echo $size; ?>" required class="size-radio">
            <label for="size_<?php echo $size; ?>" class="size-button"><?php echo $size; ?></label>
        <?php endforeach; ?>
    </div>
</div>
<div id="size-message"></div>
<div class="actions">
    <button class="add-to-cart">Add To Cart</button>
    <button class="add-to-wishlist">Add To Wishlist</button>
</div>

    </form>
</div>


                


            </div>
        <?php else: ?>
            <p>Không tìm thấy thông tin sản phẩm.</p>
        <?php endif; ?>
    </div>

    <section class="detail-des">
        <h2 class="heading">Description</h2>
        <p class="des">Thông tin chi tiết về sản phẩm.</p>
    </section>

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
