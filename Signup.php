<!doctype html>
<html lang="en">
  <head>
    <title>Sign Up</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/login.css">
  </head>
  <body>
    <?php
    // Kết nối từ config.php
    require 'config.php'; // Thay vì kết nối trực tiếp, file config.php sẽ được gọi
    
    // Xử lý form đăng ký
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Lấy dữ liệu từ form
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $address = $_POST['address']; // Thêm địa chỉ
        $phone_number = $_POST['phone_number']; // Thêm số điện thoại
        $role = 'Member'; // Gán mặc định vai trò là Member

       // Kiểm tra username hoặc email đã tồn tại
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $error = "Tên người dùng hoặc email đã tồn tại!";
} else {
            // Mã hóa mật khẩu và thêm tài khoản vào cơ sở dữ liệu
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password, email, address, phone_number, role) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $username, $hashed_password, $email, $address, $phone_number, $role);

            if ($stmt->execute()) {
                $success = "Đăng ký thành công! Bạn có thể đăng nhập ngay bây giờ.";
            } else {
                $error = "Đã xảy ra lỗi khi đăng ký!";
            }
        }

        $stmt->close();
        $conn->close();
    }
    ?>

    <section class="ftco-section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-7 col-lg-5">
            <div class="wrap">
              <div class="img" style="background-image: url(images/bg-1.jpg);"></div>
              <div class="login-wrap p-4 p-md-5">
                <h3 class="mb-4 text-center">Sign Up</h3>

                <!-- Hiển thị thông báo lỗi hoặc thành công -->
                <?php if (isset($error)): ?>
                  <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php elseif (isset($success)): ?>
                  <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <form action="signup.php" method="POST" class="signin-form">
                  <div class="form-group mt-3">
                    <input type="text" name="username" class="form-control" required>
                    <label class="form-control-placeholder" for="username">Username</label>
                  </div>
                  <div class="form-group mt-3">
                    <input type="email" name="email" class="form-control" required>
                    <label class="form-control-placeholder" for="email">Email</label>
                  </div>
                  <div class="form-group mt-3">
                    <input type="text" name="address" class="form-control" required>
                    <label class="form-control-placeholder" for="address">Address</label>
                  </div>
                  <div class="form-group mt-3"> <!-- Thêm trường nhập số điện thoại -->
                    <input type="text" name="phone_number" class="form-control" required>
                    <label class="form-control-placeholder" for="phone_number">Phone Number</label>
                  </div>
                  <div class="form-group">
                    <input id="password-field" type="password" name="password" class="form-control" required>
                    <label class="form-control-placeholder" for="password">Password</label>
                    <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="form-control btn btn-primary rounded submit px-3">Sign Up</button>
                  </div>
                </form>
                <p class="text-center">Already have an account? <a href="login.php">Sign In</a></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/script.js"></script>
  </body>
</html>
