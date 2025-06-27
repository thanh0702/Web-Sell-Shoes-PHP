// Lấy các phần tử cần thiết
const mainImage = document.querySelector('.product-image > img'); // Ảnh chính
const thumbnails = document.querySelectorAll('.image-thumbnails img'); // Ảnh thumbnail

// Thêm sự kiện click vào mỗi ảnh thumbnail
thumbnails.forEach(thumbnail => {
  thumbnail.addEventListener('click', () => {
      // Thay đổi ảnh chính bằng src của thumbnail được nhấn
      mainImage.src = thumbnail.src;
  });
});
   // JavaScript để xử lý sự kiện click
   const sizeButtons = document.querySelectorAll('.sizes button');

   sizeButtons.forEach(button => {
       button.addEventListener('click', () => {
           sizeButtons.forEach(btn => btn.classList.remove('active'));
           button.classList.add('active');
       });
   });


   