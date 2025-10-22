-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 22, 2025 lúc 12:33 PM
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
  `quantity` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(100) NOT NULL,
  `soluong` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`, `soluong`) VALUES
(1, '48 Laws of Power', 10, '48 Laws of Power.jpg', 0),
(2, 'All Your Perfects', 9, 'All Your Perfects.jpg', 0),
(3, 'Apples Never Fall', 11, 'Apples Never Fall.jpg', 0),
(4, 'bash_and_lucy-2', 8, 'bash_and_lucy-2.jpg', 0),
(5, 'be_well_bee', 12, 'be_well_bee.jpg', 0),
(6, 'Beyond Order 12 More Rules for Life', 15, 'Beyond Order 12 More Rules for Life.jpg', 0),
(7, 'boring_girls_a_novel', 10, 'boring_girls_a_novel.jpg', 0),
(8, 'clever_lands', 8, 'clever_lands.jpg', 0),
(9, 'darknet', 5, 'darknet.jpg', 0),
(10, 'economic', 7, 'economic.jpg', 0),
(11, 'freefall', 7, 'freefall.jpg', 0),
(12, 'Greenlights', 8, 'Greenlights.jpg', 0),
(13, 'history_of_modern_architectur', 12, 'history_of_modern_architecture.jpg', 0),
(14, 'holy_ghosts', 10, 'holy_ghosts.jpg', 0),
(15, 'lloyd', 13, 'lloyd.jpg', 0),
(16, 'Maps of Meaning', 14, 'Maps of Meaning.jpg', 0),
(17, 'Maybe Someday', 12, 'Maybe Someday.jpg', 0),
(18, 'nightshade', 8, 'nightshade.jpg', 0),
(19, 'Project Hail Mary', 21, 'Project Hail Mary.jpg', 0),
(20, 'radical_gardening', 24, 'radical_gardening.jpg', 0),
(21, 'red_queen', 81, 'red_queen.jpg', 0),
(22, 'Reminders of Him', 17, 'Reminders of Him.jpg', 0),
(23, 'shattered', 27, 'shattered.jpg', 0),
(24, 'The Four Agreements', 87, 'The Four Agreements.jpg', 0),
(25, 'The Last Thing He Told Me', 75, 'The Last Thing He Told Me.jpg', 0),
(26, 'The Laws of Human Nature', 16, 'The Laws of Human Nature.jpg', 0),
(27, 'The Maid_A Novel', 78, 'The Maid_A Novel.jpg', 0),
(28, 'The Paris Apartment', 57, 'The Paris Apartment.jpg', 0),
(29, 'the_girl_of_ink_and_stars', 47, 'the_girl_of_ink_and_stars.jpg', 0),
(30, 'the_happy_lemon', 48, 'the_happy_lemon.jpg', 0),
(31, 'the_world', 59, 'the_world.jpg', 0),
(32, 'Think and Grow Rich', 35, 'Think and Grow Rich.jpg', 0),
(33, 'Thinking, Fast and Slow', 34, 'Thinking, Fast and Slow.jpg', 0),
(34, 'Ugly Love', 97, 'Ugly Love .jpg', 0),
(35, 'Verity', 78, 'Verity.jpg', 0),
(36, 'Will', 37, 'Will.jpg', 0);

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
(2, 'admin', 'admin@gmail.com', '21232f297a57a5a743894a0e4a801fc3', 'admin');

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
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT cho bảng `message`
--
ALTER TABLE `message`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
