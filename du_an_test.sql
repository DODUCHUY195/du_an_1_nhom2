DROP DATABASE IF EXISTS `du_an_test`;
CREATE DATABASE `du_an_test` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `du_an_test`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- Bảng tài khoản đăng nhập
CREATE TABLE `account` (
  `account_id` int(11) UNSIGNED NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('AdminTong','QuanLy','Customer') DEFAULT 'Customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `account` (`account_id`, `full_name`, `email`, `phone`, `password`, `activated`, `created_at`, `role`) VALUES
(1, 'Admin', 'admin@example.com', '0900000001', '123456', 1, NOW(), 'AdminTong'),
(2, 'Quản lý', 'manager@example.com', '0900000002', '123456', 1, NOW(), 'QuanLy'),
(3, 'Customer Demo', 'customer@example.com', '0900000003', '123456', 1, NOW(), 'Customer');

-- Bảng log theo dõi khách (phụ, không bắt buộc với booking nhưng để sẵn)
CREATE TABLE `audit_log` (
  `log_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(11) UNSIGNED NOT NULL,
  `action` varchar(100) DEFAULT NULL,
  `log_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Bảng loại tour
CREATE TABLE `category` (
  `category_id` int(10) UNSIGNED NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(1, 'Chưa phân loại');

-- Bảng tour
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tour` (`tour_id`, `category_id`, `tour_code`, `tour_name`, `price`, `duration_days`, `description`, `status`, `created_at`, `start_date`, `end_date`, `image`) VALUES
(1, 1, 'TOUR20250001', 'Du lịch Singapore 4N3Đ', 10000000.00, 4, 'Tour demo 1', 'active', NOW(), '2025-12-01', '2025-12-04', NULL),
(2, 1, 'TOUR20250002', 'Du lịch Đà Nẵng 3N2Đ', 5000000.00, 3, 'Tour demo 2', 'active', NOW(), '2025-12-10', '2025-12-12', NULL);

-- Bảng lịch khởi hành
CREATE TABLE `tour_schedule` (
  `schedule_id` int(10) UNSIGNED NOT NULL,
  `tour_id` int(10) UNSIGNED NOT NULL,
  `depart_date` date NOT NULL,
  `return_date` date NOT NULL,
  `seats_total` int(11) NOT NULL,
  `seats_booked` int(11) DEFAULT 0,
  `status` enum('open','closed','cancelled','completed') DEFAULT 'open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tour_schedule` (`schedule_id`, `tour_id`, `depart_date`, `return_date`, `seats_total`, `seats_booked`, `status`) VALUES
(1, 1, '2025-12-01', '2025-12-04', 40, 0, 'open'),
(2, 2, '2025-12-10', '2025-12-12', 30, 0, 'open');

-- Bảng xe
CREATE TABLE `vehicles` (
  `vehicle_id` int(11) NOT NULL,
  `schedule_id` int(10) UNSIGNED NOT NULL,
  `vehicle_name` varchar(100) DEFAULT NULL,
  `plate_number` varchar(50) DEFAULT NULL,
  `total_seats` int(11) NOT NULL,
  `booked_seats` int(11) DEFAULT 0,
  `driver_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `vehicles` (`vehicle_id`, `schedule_id`, `vehicle_name`, `plate_number`, `total_seats`, `booked_seats`, `driver_name`) VALUES
(1, 1, 'Xe 45 chỗ 01', '51A-00001', 45, 0, 'Tài xế A'),
(2, 1, 'Xe 29 chỗ 02', '51A-00002', 29, 0, 'Tài xế B'),
(3, 2, 'Xe 16 chỗ 03', '51A-00003', 16, 0, 'Tài xế C');

-- Bảng booking (đơn đặt tour)
CREATE TABLE `booking` (
  `booking_id` int(11) UNSIGNED NOT NULL,
  `customer_id` int(11) UNSIGNED NOT NULL,        -- liên kết account (khách đăng nhập)
  `schedule_id` int(10) UNSIGNED NOT NULL,        -- lịch khởi hành
  `vehicle_id` int(11) DEFAULT NULL,              -- xe được phân bổ
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) DEFAULT NULL,
  `deposit` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `booking_source` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `passenger_count` int(11) NOT NULL,
  `booking_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Bảng khách trong booking (danh sách hành khách)
CREATE TABLE `booking_customer` (
  `customer_id` int(11) NOT NULL,
  `booking_id` int(11) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `gender` enum('Nam','Nữ','Khác') DEFAULT 'Nam',
  `birth_year` year(4) DEFAULT NULL,
  `id_number` varchar(50) DEFAULT NULL,
  `special_request` text DEFAULT NULL,
  `checked_in` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Bảng hướng dẫn viên
CREATE TABLE `guide` (
  `guide_id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status` enum('available','busy','inactive') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Bảng phân công hướng dẫn viên
CREATE TABLE `guide_assignment` (
  `assignment_id` int(10) UNSIGNED NOT NULL,
  `guide_id` int(10) UNSIGNED NOT NULL,
  `schedule_id` int(10) UNSIGNED NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Nhật ký tour (nhật ký hướng dẫn viên)
CREATE TABLE `daily_log` (
  `log_id` int(10) UNSIGNED NOT NULL,
  `guide_id` int(10) UNSIGNED NOT NULL,
  `schedule_id` int(10) UNSIGNED NOT NULL,
  `log_date` date NOT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Thanh toán
CREATE TABLE `payment` (
  `payment_id` int(10) UNSIGNED NOT NULL,
  `booking_id` int(11) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','completed','failed','refunded') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Hành khách (nếu tách riêng khỏi booking_customer, để demo)
CREATE TABLE `tour_passenger` (
  `passenger_id` int(10) UNSIGNED NOT NULL,
  `booking_id` int(11) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `gender` enum('Nam','Nữ','Khác') DEFAULT NULL,
  `birth_year` int(11) DEFAULT NULL,
  `id_number` varchar(50) DEFAULT NULL,
  `special_request` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Ghi chú đặc biệt cho hành khách
CREATE TABLE `special_note` (
  `note_id` int(10) UNSIGNED NOT NULL,
  `passenger_id` int(10) UNSIGNED NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Ảnh tour
CREATE TABLE `tour_image` (
  `image_id` int(10) UNSIGNED NOT NULL,
  `tour_id` int(10) UNSIGNED NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Lịch trình tour
CREATE TABLE `tour_itinerary` (
  `itinerary_id` int(10) UNSIGNED NOT NULL,
  `tour_id` int(10) UNSIGNED NOT NULL,
  `day_number` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Giá tour theo thời gian
CREATE TABLE `tour_price` (
  `price_id` int(10) UNSIGNED NOT NULL,
  `tour_id` int(10) UNSIGNED NOT NULL,
  `effective_date` date NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- INDEX (PRIMARY KEY, UNIQUE, FOREIGN KEY)


-- PRIMARY KEY / INDEX
ALTER TABLE `account`
  ADD PRIMARY KEY (`account_id`),
  ADD UNIQUE KEY `unique_account_email` (`email`);

ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_audit_customer` (`customer_id`);

ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

ALTER TABLE `tour`
  ADD PRIMARY KEY (`tour_id`),
  ADD UNIQUE KEY `unique_tour_code` (`tour_code`),
  ADD KEY `idx_tour_category` (`category_id`);

ALTER TABLE `tour_schedule`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `idx_schedule_tour` (`tour_id`);

ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`vehicle_id`),
  ADD KEY `idx_vehicle_schedule` (`schedule_id`);

ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD UNIQUE KEY `unique_booking_code` (`booking_code`),
  ADD KEY `idx_booking_customer` (`customer_id`),
  ADD KEY `idx_booking_schedule` (`schedule_id`),
  ADD KEY `idx_booking_vehicle` (`vehicle_id`),
  ADD KEY `idx_booking_status` (`status`);

ALTER TABLE `booking_customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `idx_booking_customer_booking` (`booking_id`);

ALTER TABLE `guide`
  ADD PRIMARY KEY (`guide_id`);

ALTER TABLE `guide_assignment`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `idx_guide_assignment_guide` (`guide_id`),
  ADD KEY `idx_guide_assignment_schedule` (`schedule_id`);

ALTER TABLE `daily_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_daily_log_guide` (`guide_id`),
  ADD KEY `idx_daily_log_schedule` (`schedule_id`);

ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `idx_payment_booking` (`booking_id`);

ALTER TABLE `tour_passenger`
  ADD PRIMARY KEY (`passenger_id`),
  ADD KEY `idx_passenger_booking` (`booking_id`);

ALTER TABLE `special_note`
  ADD PRIMARY KEY (`note_id`),
  ADD KEY `idx_note_passenger` (`passenger_id`);

ALTER TABLE `tour_image`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `idx_image_tour` (`tour_id`);

ALTER TABLE `tour_itinerary`
  ADD PRIMARY KEY (`itinerary_id`),
  ADD KEY `idx_itinerary_tour` (`tour_id`);

ALTER TABLE `tour_price`
  ADD PRIMARY KEY (`price_id`),
  ADD KEY `idx_price_tour` (`tour_id`);


-- AUTO_INCREMENT


ALTER TABLE `account`
  MODIFY `account_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `audit_log`
  MODIFY `log_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `category`
  MODIFY `category_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `tour`
  MODIFY `tour_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `tour_schedule`
  MODIFY `schedule_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `vehicles`
  MODIFY `vehicle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `booking`
  MODIFY `booking_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `booking_customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `guide`
  MODIFY `guide_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `guide_assignment`
  MODIFY `assignment_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `daily_log`
  MODIFY `log_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `payment`
  MODIFY `payment_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `tour_passenger`
  MODIFY `passenger_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `special_note`
  MODIFY `note_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `tour_image`
  MODIFY `image_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `tour_itinerary`
  MODIFY `itinerary_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `tour_price`
  MODIFY `price_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;


-- FOREIGN KEY


ALTER TABLE `tour`
  ADD CONSTRAINT `fk_tour_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`);

ALTER TABLE `tour_schedule`
  ADD CONSTRAINT `fk_schedule_tour` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE;

ALTER TABLE `vehicles`
  ADD CONSTRAINT `fk_vehicle_schedule` FOREIGN KEY (`schedule_id`) REFERENCES `tour_schedule` (`schedule_id`) ON DELETE CASCADE;

ALTER TABLE `booking`
  ADD CONSTRAINT `fk_booking_account` FOREIGN KEY (`customer_id`) REFERENCES `account` (`account_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_booking_schedule` FOREIGN KEY (`schedule_id`) REFERENCES `tour_schedule` (`schedule_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_booking_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`);

ALTER TABLE `booking_customer`
  ADD CONSTRAINT `fk_booking_customer_booking` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE;



ALTER TABLE `guide_assignment`
  ADD CONSTRAINT `fk_assignment_guide` FOREIGN KEY (`guide_id`) REFERENCES `guide` (`guide_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_assignment_schedule` FOREIGN KEY (`schedule_id`) REFERENCES `tour_schedule` (`schedule_id`) ON DELETE CASCADE;

ALTER TABLE `daily_log`
  ADD CONSTRAINT `fk_daily_log_guide` FOREIGN KEY (`guide_id`) REFERENCES `guide` (`guide_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_daily_log_schedule` FOREIGN KEY (`schedule_id`) REFERENCES `tour_schedule` (`schedule_id`) ON DELETE CASCADE;

ALTER TABLE `payment`
  ADD CONSTRAINT `fk_payment_booking` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE;

ALTER TABLE `tour_passenger`
  ADD CONSTRAINT `fk_passenger_booking` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE;

ALTER TABLE `special_note`
  ADD CONSTRAINT `fk_note_passenger` FOREIGN KEY (`passenger_id`) REFERENCES `tour_passenger` (`passenger_id`) ON DELETE CASCADE;

ALTER TABLE `tour_image`
  ADD CONSTRAINT `fk_image_tour` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE;

ALTER TABLE `tour_itinerary`
  ADD CONSTRAINT `fk_itinerary_tour` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE;

ALTER TABLE `tour_price`
  ADD CONSTRAINT `fk_price_tour` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE;

COMMIT;
