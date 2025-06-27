<?php
// Kết nối từ config.php
require 'config.php'; // Gọi file config.php để sử dụng kết nối

// PHP xử lý đăng nhập
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Truy vấn để kiểm tra thông tin người dùng
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Kiểm tra mật khẩu
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role']; // Lưu vai trò người dùng vào session

            // Kiểm tra nếu "Remember Me" được chọn
            if (isset($_POST['remember_me'])) {
                // Lưu tài khoản và mật khẩu vào cookie trong 7 ngày
                setcookie('username', $username, time() + (7 * 24 * 60 * 60)); // Cookie tồn tại trong 7 ngày
                setcookie('password', $password, time() + (7 * 24 * 60 * 60)); // Lưu mật khẩu dạng plain text (không bảo mật)
            } else {
                // Xóa cookie nếu "Remember Me" không được chọn
                setcookie('username', '', time() - 3600);
                setcookie('password', '', time() - 3600);
            }

            // Kiểm tra vai trò
            if ($user['role'] === 'Admin') {
                header("Location: quanly.php"); // Điều hướng đến trang quản lý nếu là admin
            } else {
                header("Location: index.php"); // Điều hướng đến trang index nếu là member
            }
            exit();
        } else {
            $error = "Sai mật khẩu!";
        }
    } else {
        $error = "Tên đăng nhập không tồn tại!";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Login Page</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/login.css">
  </head>
  <body>

    <section class="ftco-section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-6 text-center mb-5"></div>
        </div>
        <div class="row justify-content-center">
          <div class="col-md-7 col-lg-5">
            <div class="wrap">
              <div class="img" style="background-image: url(images/bg-1.jpg);"></div>
              <div class="login-wrap p-4 p-md-5">
                <div class="d-flex">
                  <div class="w-100">
                    <h3 class="mb-4">Sign In</h3>
                  </div>
                  <div class="w-100">
                    <p class="social-media d-flex justify-content-end">
                      <a href="#" class="social-icon d-flex align-items-center justify-content-center"><span class="fa fa-facebook"></span></a>
                      <a href="#" class="social-icon d-flex align-items-center justify-content-center"><span class="fa fa-twitter"></span></a>
                    </p>
                  </div>
                </div>
                <!-- Hiển thị thông báo lỗi nếu có -->
                <?php if (isset($error)): ?>
                  <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form action="login.php" method="POST" class="signin-form">
                  <div class="form-group mt-3">
                    <input type="text" name="username" class="form-control" required value="<?php echo isset($_COOKIE['username']) ? $_COOKIE['username'] : ''; ?>">
                    <label class="form-control-placeholder" for="username">Username</label>
                  </div>
                  <div class="form-group">
                    <input id="password-field" type="password" name="password" class="form-control" required value="<?php echo isset($_COOKIE['password']) ? $_COOKIE['password'] : ''; ?>">
                    <label class="form-control-placeholder" for="password">Password</label>
                    <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="form-control btn btn-primary rounded submit px-3">Sign In</button>
                  </div>
                  <div class="form-group d-md-flex">
                    <div class="w-50 text-left">
                      <label class="checkbox-wrap checkbox-primary mb-0">Remember Me
                        <input type="checkbox" name="remember_me" <?php echo isset($_COOKIE['username']) ? 'checked' : ''; ?>>
                        <span class="checkmark"></span>
                      </label>
                    </div>
                    <div class="w-50 text-md-right">
                      <a href="#">Forgot Password</a>
                    </div>
                  </div>
                </form>
                <p class="text-center">Not a member? <a href="signup.php">Sign Up</a></p>
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
