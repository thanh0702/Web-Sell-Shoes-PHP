-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th3 19, 2025 lúc 03:47 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `webbanhang`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `size` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','processed','shipped','delivered','cancelled') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`order_id`, `username`, `order_date`, `total_price`, `status`) VALUES
(7, 'than', '2025-02-24 14:04:29', 450.00, 'pending'),
(8, 'than', '2025-02-24 15:14:25', 450.00, 'pending'),
(9, 'than', '2025-02-25 06:32:54', 150.00, 'pending'),
(10, 'than', '2025-02-25 07:53:40', 150.00, 'pending'),
(11, 'minh', '2025-02-25 10:19:07', 150.00, 'pending'),
(12, 'than', '2025-02-25 10:24:14', 1200.00, 'pending'),
(13, 'than', '2025-03-18 14:49:45', 320.00, 'delivered'),
(14, 'than', '2025-03-18 15:05:47', 170.00, 'pending');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `order_detail_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`order_detail_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 7, 1, 2, 150.00),
(2, 7, 1, 1, 150.00),
(3, 8, 1, 3, 150.00),
(4, 9, 1, 1, 150.00),
(5, 10, 1, 1, 150.00),
(6, 11, 1, 1, 150.00),
(7, 12, 1, 8, 150.00),
(8, 13, 1, 1, 150.00),
(9, 13, 5, 1, 170.00),
(10, 14, 5, 1, 170.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_code` varchar(50) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `size` varchar(255) DEFAULT NULL,
  `image_url0` varchar(255) DEFAULT NULL,
  `image_url1` varchar(255) DEFAULT NULL,
  `image_url2` varchar(255) DEFAULT NULL,
  `image_url3` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`product_id`, `product_code`, `product_name`, `description`, `price`, `quantity`, `size`, `image_url0`, `image_url1`, `image_url2`, `image_url3`) VALUES
(1, '1', 'Air Jordan Gray', NULL, 150.00, 16, '39,40,41,42,43,44', 'https://i.imgur.com/fzVqNhd.jpeg', 'https://i.imgur.com/Mj5b3I5.jpeg', 'https://i.imgur.com/vs19ug9.jpeg', 'https://i.imgur.com/FFnOfNR.jpeg'),
(4, '2', 'Air Jordan Blue', NULL, 150.00, 15, '39,40,41,42,43', 'https://i.imgur.com/DvjsmOQ.jpeg', 'https://i.imgur.com/PrpVphy.jpeg', 'https://i.imgur.com/rN7IgYa.jpeg', 'https://i.imgur.com/7keNeqd.jpeg'),
(5, '3', 'Air Jordan Panda', NULL, 170.00, 18, '39,40,41,42,43', 'https://i.imgur.com/USsXbMx.jpeg', 'https://i.imgur.com/abJHWob.jpeg', 'https://i.imgur.com/EcT78fw.jpeg', 'https://i.imgur.com/BSRCWfX.jpeg'),
(7, '4', 'Air Jordan Univer', NULL, 200.00, 27, '39,40,41,42,43', 'https://i.imgur.com/NTdwS1b.png', 'https://i.imgur.com/eEdGxPi.png', 'https://i.imgur.com/ON2CiXj.png', 'https://i.imgur.com/j3nwKZz.png'),
(8, '5', 'Air Force Shadow', NULL, 300.00, 23, '39,40,41,42,43', 'https://i.imgur.com/PlLYpCW.png', 'https://i.imgur.com/0iMK0nj.png', 'https://i.imgur.com/tImxSEc.png', 'https://i.imgur.com/CeAXNFe.png'),
(9, '6', 'Air Jordan Love', NULL, 130.00, 24, '39,40,41,42,43', 'https://i.imgur.com/nwNw4KS.png', 'https://i.imgur.com/NmUX1zS.png', 'https://i.imgur.com/icDxTmB.png', 'https://i.imgur.com/Syd6QNO.png'),
(10, '7', 'Air Jordan Brown', NULL, 350.00, 15, '39,40,41,42,43', 'https://i.imgur.com/0cJ28V4.png', 'https://i.imgur.com/FWA9f5K.png', 'https://i.imgur.com/G5xLhx1.png', 'https://i.imgur.com/M86zJ1e.png'),
(11, '8', 'Air Jordan W', NULL, 400.00, 20, '39,40,41,42,43', 'https://i.imgur.com/43tTMbI.png', 'https://i.imgur.com/BcdjBqZ.png', 'https://i.imgur.com/ZxnFVMS.png', 'https://i.imgur.com/u1YLVhj.png');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `role` enum('Admin','Member') DEFAULT 'Member'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `address`, `phone_number`, `role`) VALUES
(1, 'admin', '$2y$10$5..ryIIVP6iDPtfDrFiZD.xjCiajapzLyfKNCE2IRUg1j/heoLxkO', 'thanhkt2k4@gmail.com', 'QB', '0987654321', 'Admin'),
(2, 'thanhh', '$2y$10$nAXhClajOnWlStFsWZR18uEEXW7xgs8h0TNQkFoKtSjyVMO2MLSkC', 'thanhkl2k4@gmail.com', 'VN', '0848548434', 'Member'),
(3, 'thanh', '$2y$10$Q0QJUpX4zW2PM4kKp.nJZuAOjOS6sc.QyNQVZTk2YW9kkOthCQK.a', '22@gmail.com', 'QN', '4343434343', 'Member'),
(4, 'than', '$2y$10$zNmJIDdXSfQdKoUgERPud.C0JT8uoqRoUvoASvm5OEX/7zJ0wIm2W', 'thanh@gmail.com', 'Hà Nội', '091249971565', 'Member'),
(5, 'minh', '$2y$10$dvNF3OoaNdFYId4.duheJuloUDE7zFvL3ahn7uppxo62/923o.u1q', 'minh@gmail', 'sdsdfsdf', '777777777', 'Member');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_detail_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

