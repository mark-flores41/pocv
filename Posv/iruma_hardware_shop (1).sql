-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2024 at 04:43 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `iruma_hardware_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ok`
--

CREATE TABLE `ok` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `user_email` varchar(255) NOT NULL,
  `emailofdeliveryrider` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image_path` blob DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `evidence_path` varchar(255) DEFAULT NULL,
  `evidence_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `product_id`, `quantity`, `price`, `total`, `user_email`, `emailofdeliveryrider`, `created_at`, `image_path`, `status`, `evidence_path`, `evidence_image`) VALUES
(88, 2, 1, 12000.00, 12000.00, 'franxhenry55@gmail.com', '', '2024-11-17 04:51:12', 0x6275646f792f7069632f7777772e6a7067, NULL, NULL, NULL),
(89, 3, 1, 1500.00, 1500.00, 'franxhenry55@gmail.com', '', '2024-11-17 04:51:12', 0x6275646f792f7069632f7765772e6a7067, NULL, NULL, NULL),
(90, 4, 1, 1600.00, 1600.00, 'franxhenry55@gmail.com', '', '2024-11-17 04:51:12', 0x6275646f792f7069632f777777772e6a7067, NULL, NULL, NULL),
(91, 1, 3, 1000.00, 3000.00, 'franxhenry@gmail.com', '', '2024-11-18 06:42:50', 0x6275646f792f7069632f312e6a7067, NULL, NULL, NULL),
(92, 1, 2, 1000.00, 2000.00, 'franxhenry432@gmail.com', '', '2024-11-19 02:59:53', 0x6275646f792f7069632f312e6a7067, NULL, NULL, NULL),
(93, 2, 1, 12000.00, 12000.00, 'franxhenry432@gmail.com', '', '2024-11-19 02:59:53', 0x6275646f792f7069632f7777772e6a7067, NULL, NULL, NULL),
(94, 3, 1, 1500.00, 1500.00, 'franxhenry432@gmail.com', '', '2024-11-19 02:59:53', 0x6275646f792f7069632f7765772e6a7067, NULL, NULL, NULL),
(95, 2, 1, 12000.00, 12000.00, 'franxhenry98@gmail.com', '', '2024-11-19 03:57:10', 0x6275646f792f7069632f7777772e6a7067, '', NULL, NULL),
(96, 1, 1, 1000.00, 1000.00, 'franxhenry98@gmail.com', '', '2024-11-19 03:57:10', 0x6275646f792f7069632f312e6a7067, '', NULL, NULL),
(97, 1, 3, 1000.00, 3000.00, 'franxhenry98@gmail.com', '', '2024-11-19 04:01:28', 0x6275646f792f7069632f312e6a7067, '', NULL, NULL),
(98, 1, 3, 1000.00, 3000.00, 'franxhenry98@gmail.com', '', '2024-11-19 04:02:13', 0x6275646f792f7069632f312e6a7067, 'confirmed', NULL, NULL),
(99, 1, 2, 1000.00, 2000.00, 'franxhenry98@gmail.com', '', '2024-11-19 04:03:08', 0x6275646f792f7069632f312e6a7067, 'delivered', NULL, NULL),
(100, 1, 1, 1000.00, 1000.00, 'franxhenry3124@gmail.com', 'franxhenry8787@gmail.com', '2024-11-19 04:37:54', 0x6275646f792f7069632f312e6a7067, 'delivered', NULL, 'uploads/evidence/100-1732076654-Brown Beige Vintage Scrapbook Cover History Project A4 Document.png'),
(101, 4, 1, 1600.00, 1600.00, 'franxhenry3124@gmail.com', '', '2024-11-19 04:37:54', 0x6275646f792f7069632f777777772e6a7067, 'pending', NULL, NULL),
(102, 1, 1, 1000.00, 1000.00, 'franxhenry98@gmail.com', '', '2024-11-19 22:20:58', 0x6275646f792f7069632f312e6a7067, 'pending', NULL, NULL),
(103, 2, 2, 12000.00, 24000.00, 'franxhenry98@gmail.com', 'franxhenry8787@gmail.com', '2024-11-19 22:20:58', 0x6275646f792f7069632f7777772e6a7067, 'delivered', NULL, NULL),
(104, 3, 1, 1500.00, 1500.00, 'franxhenry98@gmail.com', 'franxhenry8787@gmail.com', '2024-11-19 22:20:58', 0x6275646f792f7069632f7765772e6a7067, 'delivered', NULL, NULL),
(105, 4, 1, 1600.00, 1600.00, 'franxhenry98@gmail.com', '', '2024-11-19 22:20:58', 0x6275646f792f7069632f777777772e6a7067, 'pending', NULL, NULL),
(106, 5, 1, 500.00, 500.00, 'franxhenry98@gmail.com', 'franxhenry45@gmail.com', '2024-11-19 22:20:58', 0x6275646f792f7069632f6368616765722e6a7067, 'delivering', NULL, 'uploads/evidence/106-1732159433-5ddb0db77e209cdda3f0db18ec820839 (2).jpg'),
(107, 3, 1, 1500.00, 1500.00, 'franxhenry3124@gmail.com', '', '2024-11-20 05:05:08', 0x6275646f792f7069632f7765772e6a7067, 'pending', NULL, NULL),
(108, 5, 1, 500.00, 500.00, 'franxhenry98@gmail.com', '', '2024-11-20 20:28:51', 0x6275646f792f7069632f6368616765722e6a7067, 'pending', NULL, NULL),
(109, 4, 1, 1600.00, 1600.00, 'franxhenry98@gmail.com', '', '2024-11-20 20:28:51', 0x6275646f792f7069632f777777772e6a7067, 'pending', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `price`, `image_path`) VALUES
(1, 'Keyboard', 1000.00, 'budoy/pic/1.jpg'),
(2, 'Monitor', 12000.00, 'budoy/pic/www.jpg'),
(3, 'Mouse', 1500.00, 'budoy/pic/wew.jpg'),
(4, 'System Unit', 1600.00, 'budoy/pic/wwww.jpg'),
(5, 'charger', 500.00, 'budoy/pic/chager.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `municipality` varchar(50) NOT NULL,
  `barangay` varchar(50) NOT NULL,
  `sitioorzone` varchar(50) NOT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `role` enum('Admin','Guest','Delivery Rider') DEFAULT 'Guest',
  `reset_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `created_at`, `municipality`, `barangay`, `sitioorzone`, `contact`, `role`, `reset_token`) VALUES
(11, 'franxhenry432@gmail.com', '$2y$10$jlh9Uwwmu58knwl4oFzDLOa3Uw5O8G3saQpgzIBE/LcZjH89ff.Ou', '2024-11-19 09:41:13', 'palo', 'cangumbang', 'zone 3', '09070940350', 'Guest', NULL),
(12, 'franxhenry98@gmail.com', '$2y$10$vVTv5AF2sWK8Pf/GOVniCezO4Ln9eW9tZMsaxzQbDFcIqY1HkrbgS', '2024-11-19 09:46:28', 'palo', 'cangumbang', 'zone 3', '09070940350', 'Guest', '33a0a6e0eab64397'),
(13, 'franxhenry3124@gmail.com', '$2y$10$/AkD4PlQiH4JZ4uqxVBoKOKYLY2Kv9zhWKmr8YAQO22aw6h8w8Vsy', '2024-11-19 09:52:31', 'palo', 'cangumbang', 'zone 3', '09070940350', 'Guest', 'b0ed4991405df416'),
(14, 'franxhenry0988@gmail.com', '$2y$10$eFIUfy0i/sSevpaatyy7b.0LYcl7KrDgYXlL88cvtCoPP1LlaVL/S', '2024-11-19 10:05:02', 'palo', 'cangumbang', 'zone 3', '09070940350', '', '525b1d3d66865baf'),
(15, 'franxhenry545@gmail.com', '$2y$10$op.Wol1mL1/76.tnnPAdN.pZp9PA1ShizmejLShkY9jl4k9tWMWmq', '2024-11-19 10:24:29', 'palo', 'cangumbang', 'zone 3', '09070940350', '', 'ce1a82f046e87ffc'),
(16, 'franxhenry45@gmail.com', '$2y$10$875z1qvUAfOCfQh.lDDnsOLa3wYFl0PlyjPlp47tCSteuQb0o2Rmm', '2024-11-19 10:26:27', 'Tanuan', 'Idunno', 'zone 4', '09518830919', 'Delivery Rider', '2e4979797c33d8d7'),
(17, 'franxhenry4dmin@gmail.com', '$2y$10$n4T3F5TzOWrmKMJ0Yr9hZeN5K2k.h9F43ZzfmeNUxNWLxYZZMIoGu', '2024-11-20 04:37:33', 'palo', 'cangumbang', 'zone 3', '09070940350', 'Admin', '45d4568f6dab7f50'),
(18, 'JhonVincent12@gmail.com', '$2y$10$UyhDBYB/NtZZs7jUA6KGRu0IlaVMrX1q.vamyejDNtTFo.JfQzsve', '2024-11-21 03:11:00', 'palo', 'San miguel', 'zone 4', '09070940350', 'Admin', '6a8cdd7249abcb67');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `ok`
--
ALTER TABLE `ok`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `ok`
--
ALTER TABLE `ok`
  ADD CONSTRAINT `ok_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `ok` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
