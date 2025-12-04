-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 05, 2025 lúc 12:10 AM
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
-- Cơ sở dữ liệu: `bookshop-website`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `name`, `price`, `quantity`, `image`) VALUES
(76, 4, 'All Your Perfects', 9, 1, 'All Your Perfects.jpg'),
(83, 3, 'All Your Perfects', 9, 1, 'All Your Perfects.jpg'),
(88, 1, 'Apples Never Fall', 11, 4, 'Apples Never Fall.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `message`
--

CREATE TABLE `message` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `message`
--

INSERT INTO `message` (`id`, `user_id`, `name`, `email`, `number`, `message`) VALUES
(12, 3, 'qu anh', 'toilagatau234@gmail.com', '122331414', 'RQRTFJGJG'),
(13, 3, 'qu anh', 'user@gmail.com', '122331414', 'TDJFKHGJKH'),
(14, 3, 'qu anh', 'user1@gmail.com', '122331414', '23QW45EU6RI7T86'),
(18, 1, 'tên gì cũng được', 'toilagatau24@gmail.com', '0123456789', 'agervaergfaerfsgvag');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `email` varchar(100) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(100) NOT NULL,
  `placed_on` varchar(50) NOT NULL,
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`) VALUES
(11, 3, 'qu anh', '122331414', 'user1@gmail.com', 'momo', 'flat no. 13, 3 dường 25a, ho chi minh, Việt Nam - 90000', ', 48 Laws of Power (1) , clever_lands (2) ', 26, '23-Oct-2025', 'completed'),
(12, 1, 'qu anh', '122331414', 'toilagatau234@gmail.com', 'paypal', 'flat no. 54, 3 dường 25a, ho chi minh, Việt Nam - 90000', ', the_happy_lemon (1) , Apples Never Fall (1) ', 59, '24-Oct-2025', 'pending'),
(13, 4, 'qu anh', '122331414', 'user2@gmail.com', 'momo', 'flat no. 11, 3 dường 25a, ho chi minh, Việt Nam - 90000', ', All Your Perfects (1) , 48 Laws of Power (1) ', 19, '21-Nov-2025', 'completed'),
(16, 3, 'qu anh', '122331414', 'toilagatau234@gmail.com', 'visa debit', 'flat no. 3, 3 dường 25a, ho chi minh, Việt Nam - 90000', ', shattered (1) ', 27, '21-Nov-2025', 'pending');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `details` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`, `quantity`, `details`) VALUES
(1, '48 Laws of Power', 10, '48 Laws of Power.jpg', 14, 'Chưa có mô tả cho sản phẩm này.'),
(3, 'Apples Never Fall', 11, 'Apples Never Fall.jpg', 5, 'adsdddawgewyhgerdyhthythy'),
(4, 'bash and lucy 2', 8, 'bash_and_lucy-2.jpg', 10, 'Chưa có mô tả cho sản phẩm này.'),
(5, 'be well bee', 12, 'be_well_bee.jpg', 41, 'Chưa có mô tả cho sản phẩm này.'),
(6, 'Beyond Order 12 More Rules for Life', 15, 'Beyond Order 12 More Rules for Life.jpg', 25, 'Chưa có mô tả cho sản phẩm này.'),
(7, 'boring girls a novel', 10, 'boring_girls_a_novel.jpg', 26, 'Chưa có mô tả cho sản phẩm này.'),
(8, 'clever lands', 8, 'clever_lands.jpg', 62, 'Chưa có mô tả cho sản phẩm này.'),
(9, 'darknet', 5, 'darknet.jpg', 52, 'Chưa có mô tả cho sản phẩm này.'),
(10, 'economic', 7, 'economic.jpg', 525, 'Chưa có mô tả cho sản phẩm này.'),
(11, 'freefall', 7, 'freefall.jpg', 13, 'Chưa có mô tả cho sản phẩm này.'),
(12, 'Greenlights', 8, 'Greenlights.jpg', 15, 'Chưa có mô tả cho sản phẩm này.'),
(13, 'history of modern architectur', 12, 'history_of_modern_architecture.jpg', 34, 'Chưa có mô tả cho sản phẩm này.'),
(14, 'holy ghosts', 10, 'holy_ghosts.jpg', 135, 'Chưa có mô tả cho sản phẩm này.'),
(15, 'lloyd', 13, 'lloyd.jpg', 53, 'Chưa có mô tả cho sản phẩm này.'),
(16, 'Maps of Meaning', 14, 'Maps of Meaning.jpg', 57, 'Chưa có mô tả cho sản phẩm này.'),
(17, 'Maybe Someday', 12, 'Maybe Someday.jpg', 36, 'Chưa có mô tả cho sản phẩm này.'),
(18, 'nightshade', 8, 'nightshade.jpg', 37, 'Chưa có mô tả cho sản phẩm này.'),
(19, 'Project Hail Mary', 21, 'Project Hail Mary.jpg', 28, 'Chưa có mô tả cho sản phẩm này.'),
(20, 'radical gardening', 24, 'radical_gardening.jpg', 29, 'Chưa có mô tả cho sản phẩm này.'),
(21, 'red queen', 81, 'red_queen.jpg', 86, 'Chưa có mô tả cho sản phẩm này.'),
(22, 'Reminders of Him', 17, 'Reminders of Him.jpg', 277, 'Chưa có mô tả cho sản phẩm này.'),
(23, 'shattered', 27, 'shattered.jpg', 238, 'Chưa có mô tả cho sản phẩm này.'),
(24, 'The Four Agreements', 87, 'The Four Agreements.jpg', 75, 'Chưa có mô tả cho sản phẩm này.'),
(25, 'The Last Thing He Told Me', 75, 'The Last Thing He Told Me.jpg', 75, 'Chưa có mô tả cho sản phẩm này.'),
(26, 'The Laws of Human Nature', 16, 'The Laws of Human Nature.jpg', 768, 'Chưa có mô tả cho sản phẩm này.'),
(27, 'The Maid A Novel', 78, 'The Maid_A Novel.jpg', 73, 'Chưa có mô tả cho sản phẩm này.'),
(28, 'The Paris Apartment', 57, 'The Paris Apartment.jpg', 76, 'Chưa có mô tả cho sản phẩm này.'),
(29, 'the girl of ink and stars', 47, 'the_girl_of_ink_and_stars.jpg', 7, 'Chưa có mô tả cho sản phẩm này.'),
(30, 'the happy lemon', 48, 'the_happy_lemon.jpg', 456, 'Chưa có mô tả cho sản phẩm này.'),
(31, 'the world', 59, 'the_world.jpg', 76, 'Chưa có mô tả cho sản phẩm này.'),
(32, 'Think and Grow Rich', 35, 'Think and Grow Rich.jpg', 767, 'Chưa có mô tả cho sản phẩm này.'),
(33, 'Thinking, Fast and Slow', 34, 'Thinking, Fast and Slow.jpg', 0, 'Chưa có mô tả cho sản phẩm này.'),
(34, 'Ugly Love', 97, 'Ugly Love .jpg', 0, 'Chưa có mô tả cho sản phẩm này.'),
(35, 'Verity', 78, 'Verity.jpg', 0, 'Chưa có mô tả cho sản phẩm này.'),
(36, 'Will', 37, 'Will.jpg', 0, 'Chưa có mô tả cho sản phẩm này.');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`) VALUES
(1, 'quanh', 'toilagatau234@gmail.com', '202cb962ac59075b964b07152d234b70', 'user'),
(2, 'admin', 'admin@gmail.com', '21232f297a57a5a743894a0e4a801fc3', 'admin'),
(3, 'user1', 'user1@gmail.com', '202cb962ac59075b964b07152d234b70', 'user');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT cho bảng `message`
--
ALTER TABLE `message`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
