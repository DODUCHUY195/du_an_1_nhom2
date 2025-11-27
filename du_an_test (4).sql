-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 27, 2025 lúc 09:36 AM
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
-- Cơ sở dữ liệu: `du_an_test`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `audit_log`
--

CREATE TABLE `audit_log` (
  `log_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `action` varchar(100) DEFAULT NULL,
  `log_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `audit_log`
--

INSERT INTO `audit_log` (`log_id`, `user_id`, `action`, `log_time`, `details`) VALUES
(1, 1, 'create_booking', '2025-11-26 10:00:00', 'Admin tạo booking demo cho Customer Demo.');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `schedule_id` int(10) UNSIGNED NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) DEFAULT NULL,
  `deposit` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `booking`
--

INSERT INTO `booking` (`booking_id`, `user_id`, `schedule_id`, `booking_date`, `total_amount`, `deposit`, `payment_method`, `status`) VALUES
(1, 2, 1, '2025-11-26 10:00:00', 5000000.00, 2000000.00, 'cash', 'pending');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `category`
--

CREATE TABLE `category` (
  `category_id` int(10) UNSIGNED NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `description`) VALUES
(1, 'Trong nước', 'Tour du lịch trong nước'),
(2, 'Nước ngoài', 'Tour du lịch nước ngoài');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `daily_log`
--

CREATE TABLE `daily_log` (
  `log_id` int(10) UNSIGNED NOT NULL,
  `guide_id` int(10) UNSIGNED NOT NULL,
  `schedule_id` int(10) UNSIGNED NOT NULL,
  `log_date` date DEFAULT NULL,
  `content` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `daily_log`
--

INSERT INTO `daily_log` (`log_id`, `guide_id`, `schedule_id`, `log_date`, `content`) VALUES
(1, 1, 1, '2025-12-10', 'Ngày 1: Đón khách, check-in khách sạn, tham quan Bà Nà Hills.');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `guide`
--

CREATE TABLE `guide` (
  `guide_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `license_no` varchar(50) DEFAULT NULL,
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `guide`
--

INSERT INTO `guide` (`guide_id`, `user_id`, `license_no`, `note`) VALUES
(1, 3, 'HDV-001', 'Hướng dẫn viên chính cho tour Đà Nẵng');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `guide_assignment`
--

CREATE TABLE `guide_assignment` (
  `assignment_id` int(11) NOT NULL,
  `guide_id` int(10) UNSIGNED NOT NULL,
  `schedule_id` int(10) UNSIGNED NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `guide_assignment`
--

INSERT INTO `guide_assignment` (`assignment_id`, `guide_id`, `schedule_id`, `assigned_at`) VALUES
(1, 1, 1, '2025-11-26 09:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `special_note`
--

CREATE TABLE `special_note` (
  `note_id` int(10) UNSIGNED NOT NULL,
  `passenger_id` int(10) UNSIGNED NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `special_note`
--

INSERT INTO `special_note` (`note_id`, `passenger_id`, `note`, `created_at`) VALUES
(1, 1, 'Khách dị ứng hải sản, lưu ý suất ăn.', '2025-11-26 10:05:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour`
--

CREATE TABLE `tour` (
  `tour_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `tour_code` varchar(50) NOT NULL,
  `tour_name` varchar(200) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration_days` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('draft','pending_approval','active','inactive','suspended','cancelled','completed') DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ;

--
-- Đang đổ dữ liệu cho bảng `tour`
--

INSERT INTO `tour` (`tour_id`, `category_id`, `tour_code`, `tour_name`, `price`, `duration_days`, `description`, `status`, `created_at`, `start_date`, `end_date`, `image`) VALUES
(1, 1, 'PH305', 'Du lịch Đà Nẵng 3N2Đ', 5000000.00, 3, 'Tour Đà Nẵng – Hội An – Bà Nà', '', '2025-11-25 06:51:15', NULL, NULL, NULL),
(2, 2, 'PH3055', 'Du lịch Singapore 4N3Đ', 15000000.00, 4, 'Tour Singapore trọn gói', 'draft', '2025-11-25 08:40:33', NULL, NULL, NULL),
(3, 1, 'TOUR20250003', 'Du lich Cát Bà', 99999999.99, NULL, 'kh có', 'pending_approval', '2025-11-26 12:48:08', NULL, NULL, NULL),
(7, 1, 'TOUR20250004', 'Du lich Viet Nam', 0.00, NULL, 'ok', 'inactive', '2025-11-26 13:02:35', NULL, NULL, 'uploads/tours/6926fa6b927f6_8.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_image`
--

CREATE TABLE `tour_image` (
  `image_id` int(10) UNSIGNED NOT NULL,
  `tour_id` int(10) UNSIGNED NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tour_image`
--

INSERT INTO `tour_image` (`image_id`, `tour_id`, `image_url`, `is_primary`, `created_at`) VALUES
(1, 1, '/uploads/tours/1/main.jpg', 1, '2025-11-26 09:30:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_itinerary`
--

CREATE TABLE `tour_itinerary` (
  `itinerary_id` int(10) UNSIGNED NOT NULL,
  `tour_id` int(10) UNSIGNED NOT NULL,
  `day_number` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tour_itinerary`
--

INSERT INTO `tour_itinerary` (`itinerary_id`, `tour_id`, `day_number`, `title`, `description`) VALUES
(1, 1, 1, 'Ngày 1: Hà Nội – Đà Nẵng', 'Bay vào Đà Nẵng, nhận phòng khách sạn, tham quan biển Mỹ Khê.'),
(2, 1, 2, 'Ngày 2: Bà Nà Hills', 'Tham quan Bà Nà Hills, Cầu Vàng, vui chơi công viên Fantasy.');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_passenger`
--

CREATE TABLE `tour_passenger` (
  `passenger_id` int(10) UNSIGNED NOT NULL,
  `booking_id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `type` varchar(20) DEFAULT 'adult',
  `special_request` text DEFAULT NULL,
  `checked_in` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tour_passenger`
--

INSERT INTO `tour_passenger` (`passenger_id`, `booking_id`, `full_name`, `age`, `type`, `special_request`, `checked_in`) VALUES
(1, 1, 'Customer Demo', 25, 'adult', 'Không ăn hải sản', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_price`
--

CREATE TABLE `tour_price` (
  `price_id` int(10) UNSIGNED NOT NULL,
  `tour_id` int(10) UNSIGNED NOT NULL,
  `season` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_schedule`
--

CREATE TABLE `tour_schedule` (
  `schedule_id` int(10) UNSIGNED NOT NULL,
  `tour_id` int(10) UNSIGNED NOT NULL,
  `depart_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `meeting_point` varchar(200) DEFAULT NULL,
  `seats_total` int(11) NOT NULL DEFAULT 0,
  `seats_booked` int(11) NOT NULL DEFAULT 0,
  `status` varchar(20) DEFAULT 'open',
  `logs_approved` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tour_schedule`
--

INSERT INTO `tour_schedule` (`schedule_id`, `tour_id`, `depart_date`, `meeting_point`, `seats_total`, `seats_booked`, `status`, `logs_approved`) VALUES
(1, 1, '2025-12-10', 'Sân bay Nội Bài', 40, 5, 'open', 0),
(2, 2, '2026-01-05', 'Sân bay Tân Sơn Nhất', 30, 0, 'open', 0),
(4, 7, '2025-11-28', 'Nhà hát lớn', 90, 0, 'pending', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'customer',
  `activated` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `phone`, `password`, `role`, `activated`, `created_at`) VALUES
(1, 'Admin', 'okj@gamil.com', '4545', '$2y$10$Nf4olTyunLsCg5LajDbFT.Grgo1cbNR0PrIeM2EGE30.4R3hfyBXy', 'admin', 1, '2025-11-25 07:30:07'),
(2, 'Customer Demo', 'customer@gamil.com', '0900000002', '$2y$10$6ocdDvzyHkade/H6Q/q0YuEes58X2XB3M/Vsu0SODERNB4JVHxsCe', 'customer', 1, '2025-11-25 09:42:18'),
(3, 'Guide Demo', 'guide@gamil.com', '0900000003', '$2y$10$6ocdDvzyHkade/H6Q/q0YuEes58X2XB3M/Vsu0SODERNB4JVHxsCe', 'guide', 1, '2025-11-25 09:50:00');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `audit_log_ibfk_1` (`user_id`);

--
-- Chỉ mục cho bảng `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `schedule_id` (`schedule_id`),
  ADD KEY `booking_ibfk_1` (`user_id`),
  ADD KEY `idx_booking_status` (`status`);

--
-- Chỉ mục cho bảng `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `unique_category_name` (`category_name`);

--
-- Chỉ mục cho bảng `daily_log`
--
ALTER TABLE `daily_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `guide_id` (`guide_id`),
  ADD KEY `schedule_id` (`schedule_id`);

--
-- Chỉ mục cho bảng `guide`
--
ALTER TABLE `guide`
  ADD PRIMARY KEY (`guide_id`),
  ADD KEY `guide_ibfk_1` (`user_id`);

--
-- Chỉ mục cho bảng `guide_assignment`
--
ALTER TABLE `guide_assignment`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `guide_assignment_ibfk_1` (`guide_id`),
  ADD KEY `schedule_id` (`schedule_id`);

--
-- Chỉ mục cho bảng `special_note`
--
ALTER TABLE `special_note`
  ADD PRIMARY KEY (`note_id`),
  ADD KEY `special_note_ibfk_1` (`passenger_id`);

--
-- Chỉ mục cho bảng `tour`
--
ALTER TABLE `tour`
  ADD PRIMARY KEY (`tour_id`),
  ADD UNIQUE KEY `unique_tour_code` (`tour_code`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `tour_image`
--
ALTER TABLE `tour_image`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Chỉ mục cho bảng `tour_itinerary`
--
ALTER TABLE `tour_itinerary`
  ADD PRIMARY KEY (`itinerary_id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Chỉ mục cho bảng `tour_passenger`
--
ALTER TABLE `tour_passenger`
  ADD PRIMARY KEY (`passenger_id`),
  ADD KEY `tour_passenger_ibfk_1` (`booking_id`);

--
-- Chỉ mục cho bảng `tour_price`
--
ALTER TABLE `tour_price`
  ADD PRIMARY KEY (`price_id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Chỉ mục cho bảng `tour_schedule`
--
ALTER TABLE `tour_schedule`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `unique_users_email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `log_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `daily_log`
--
ALTER TABLE `daily_log`
  MODIFY `log_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `guide`
--
ALTER TABLE `guide`
  MODIFY `guide_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `guide_assignment`
--
ALTER TABLE `guide_assignment`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `special_note`
--
ALTER TABLE `special_note`
  MODIFY `note_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `tour`
--
ALTER TABLE `tour`
  MODIFY `tour_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `tour_image`
--
ALTER TABLE `tour_image`
  MODIFY `image_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `tour_itinerary`
--
ALTER TABLE `tour_itinerary`
  MODIFY `itinerary_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `tour_passenger`
--
ALTER TABLE `tour_passenger`
  MODIFY `passenger_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `tour_price`
--
ALTER TABLE `tour_price`
  MODIFY `price_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `tour_schedule`
--
ALTER TABLE `tour_schedule`
  MODIFY `schedule_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `audit_log`
--
ALTER TABLE `audit_log`
  ADD CONSTRAINT `audit_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`schedule_id`) REFERENCES `tour_schedule` (`schedule_id`);

--
-- Các ràng buộc cho bảng `daily_log`
--
ALTER TABLE `daily_log`
  ADD CONSTRAINT `daily_log_ibfk_1` FOREIGN KEY (`guide_id`) REFERENCES `guide` (`guide_id`),
  ADD CONSTRAINT `daily_log_ibfk_2` FOREIGN KEY (`schedule_id`) REFERENCES `tour_schedule` (`schedule_id`);

--
-- Các ràng buộc cho bảng `guide`
--
ALTER TABLE `guide`
  ADD CONSTRAINT `guide_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `guide_assignment`
--
ALTER TABLE `guide_assignment`
  ADD CONSTRAINT `guide_assignment_ibfk_1` FOREIGN KEY (`guide_id`) REFERENCES `guide` (`guide_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `guide_assignment_ibfk_2` FOREIGN KEY (`schedule_id`) REFERENCES `tour_schedule` (`schedule_id`);

--
-- Các ràng buộc cho bảng `special_note`
--
ALTER TABLE `special_note`
  ADD CONSTRAINT `special_note_ibfk_1` FOREIGN KEY (`passenger_id`) REFERENCES `tour_passenger` (`passenger_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tour`
--
ALTER TABLE `tour`
  ADD CONSTRAINT `tour_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`);

--
-- Các ràng buộc cho bảng `tour_image`
--
ALTER TABLE `tour_image`
  ADD CONSTRAINT `tour_image_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tour_itinerary`
--
ALTER TABLE `tour_itinerary`
  ADD CONSTRAINT `tour_itinerary_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tour_passenger`
--
ALTER TABLE `tour_passenger`
  ADD CONSTRAINT `tour_passenger_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tour_price`
--
ALTER TABLE `tour_price`
  ADD CONSTRAINT `tour_price_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tour_schedule`
--
ALTER TABLE `tour_schedule`
  ADD CONSTRAINT `tour_schedule_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
