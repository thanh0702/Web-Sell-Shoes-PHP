  // JavaScript để ẩn/hiện mật khẩu
  document.querySelector('.toggle-password').addEventListener('click', function () {
    const passwordField = document.querySelector('#password-field');
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);

    // Đổi icon giữa mắt mở và mắt đóng
    this.classList.toggle('fa-eye');
    this.classList.toggle('fa-eye-slash');
});
