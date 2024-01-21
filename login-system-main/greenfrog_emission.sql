-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 21, 2024 at 05:47 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `greenfrog_emission`
--

-- --------------------------------------------------------

--
-- Table structure for table `cancellation_reasons`
--

CREATE TABLE `cancellation_reasons` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `canceled_by_user` int(11) DEFAULT NULL,
  `cancel_by_smurf_admin` int(11) DEFAULT NULL,
  `cancel_by_super_admin` int(11) DEFAULT NULL,
  `cancellation_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cancellation_reasons`
--

INSERT INTO `cancellation_reasons` (`id`, `booking_id`, `reason`, `canceled_by_user`, `cancel_by_smurf_admin`, `cancel_by_super_admin`, `cancellation_timestamp`) VALUES
(2, 1, 'ayoko na', 3, NULL, NULL, '2023-12-27 13:05:23');

-- --------------------------------------------------------

--
-- Table structure for table `car_emission`
--

CREATE TABLE `car_emission` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `plate_number` varchar(20) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_first_name` varchar(255) NOT NULL,
  `customer_middle_name` varchar(255) DEFAULT NULL,
  `customer_last_name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `app_date` datetime DEFAULT NULL,
  `vehicle_cr_no` varchar(50) DEFAULT NULL,
  `vehicle_or_no` varchar(50) DEFAULT NULL,
  `first_reg_date` date DEFAULT NULL,
  `year_model` varchar(10) DEFAULT NULL,
  `fuel_type` varchar(50) DEFAULT NULL,
  `purpose` varchar(50) DEFAULT NULL,
  `mv_type` varchar(50) DEFAULT NULL,
  `region` varchar(50) DEFAULT NULL,
  `mv_file_no` varchar(50) DEFAULT NULL,
  `classification` varchar(50) DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `petc_or` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `organization` varchar(100) DEFAULT NULL,
  `engine` varchar(50) DEFAULT NULL,
  `chassis` varchar(50) DEFAULT NULL,
  `make` varchar(50) DEFAULT NULL,
  `series` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `gross_weight` decimal(10,2) DEFAULT NULL,
  `net_capacity` decimal(10,2) DEFAULT NULL,
  `cec_number` varchar(50) DEFAULT NULL,
  `mvect_operator` varchar(50) DEFAULT NULL,
  `car_picture` varchar(255) NOT NULL,
  `paymentMethod` varchar(50) NOT NULL,
  `paymentStatus` varchar(50) NOT NULL,
  `ticketing_id` varchar(20) NOT NULL,
  `reference_number` varchar(50) DEFAULT NULL,
  `date_tested` datetime DEFAULT NULL,
  `smurf_admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car_emission`
--

INSERT INTO `car_emission` (`id`, `event_id`, `user_id`, `plate_number`, `customer_email`, `customer_first_name`, `customer_middle_name`, `customer_last_name`, `address`, `status`, `app_date`, `vehicle_cr_no`, `vehicle_or_no`, `first_reg_date`, `year_model`, `fuel_type`, `purpose`, `mv_type`, `region`, `mv_file_no`, `classification`, `payment_date`, `petc_or`, `amount`, `organization`, `engine`, `chassis`, `make`, `series`, `color`, `gross_weight`, `net_capacity`, `cec_number`, `mvect_operator`, `car_picture`, `paymentMethod`, `paymentStatus`, `ticketing_id`, `reference_number`, `date_tested`, `smurf_admin_id`) VALUES
(1, 1, 3, 'NBAA 1232', 'tecson.k.bsinfotech@gmail.com', 'Kenneth Gabriel', 'Guimong', 'Tecson', 'Mckinley BGC', 'canceled', '2023-12-27 20:00:00', '12', '12', '2023-12-06', '2023', 'Gasoline', 'Meeting', 'Type1', 'Region1', '13', 'Compact', '0000-00-00 00:00:00', '0', '500.00', 'KORAK', '12', '1543', 'Toyota', 'Sedan', 'Red', '12.00', '12.00', '0', 'pending', 'uploads/icon.png', 'cash', 'unpaid', '2293686', '76012', '0000-00-00 00:00:00', NULL),
(2, 2, 3, 'NBAA 1332', 'tecson.k.bsinfotech@gmail.com', 'Kenneth Gabriel', 'Guimong', 'Tecson', 'Mckinley BGC', 'doned', '2023-12-27 22:16:00', '12', '12', '2023-11-29', '2019', 'Diesel - None Turbo', 'For Registration', 'Tricycle', 'Region I', '13', 'Diplomatic-Consular Corps', '2024-01-21 09:57:01', '71952', '400.00', 'KORAK', '12', '1543', 'Toyota', 'Sedan', 'Red', '12.00', '12.00', '202400000071952', 'Operator 1', 'uploads/1011917966.jpg', 'cash', 'paid', '5052674', '35401', '2024-01-21 08:28:32', NULL),
(3, 2, 3, 'PBAA 1234', 'tecson.k.bsinfotech@gmail.com', 'Kenneth Gabriel', 'Guimong', 'Tecson', 'Mckinley BGC', 'doned', '2023-12-28 03:48:00', '12', '12', '2023-11-27', '2023', 'LPG', 'For Registration', 'Car', 'Region I', '13', 'Diplomatic-Consular Corps', '2024-01-12 04:38:34', '81476', '400.00', 'KORAK', '12', '1543', 'Toyota', 'Sedan', 'Red', '12.00', '12.00', '0', 'Operator 2', 'uploads/R.jpg', 'cash', 'paid', '5480037', '72550', '2024-01-21 07:43:22', NULL),
(4, 3, 3, 'PBAA 1221', 'tecson.k.bsinfotech@gmail.com', 'Kenneth Gabriel', 'Guimong', 'Tecson', 'Mckinley BGC', 'booked', '2024-01-15 17:08:00', '12', '12', '2024-01-11', '2024', 'Gasoline', 'For Registration', 'Car', 'Region I', '12', 'Diplomatic-Consular Corps', '0000-00-00 00:00:00', '0', '500.00', '12', '12', '12', 'Toyota', 'Sedan', 'Red', '12.00', '12.00', '0', 'Operator 1', 'uploads/star.png', 'cash', 'unpaid', '3414908', '50328', '2024-01-15 09:32:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `classifications`
--

CREATE TABLE `classifications` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classifications`
--

INSERT INTO `classifications` (`id`, `name`) VALUES
(1, 'Diplomatic-Consular Corps'),
(2, 'Diplomatic-Chief of Mission'),
(3, 'Diplomatic-Diplomatic Corps'),
(4, 'Exempt-Government'),
(5, 'Diplomatic Exempt-Economics Z'),
(6, 'Government'),
(7, 'For hire'),
(8, 'Diplomatic-OEV'),
(9, 'Private'),
(10, 'Exempt-For-Hire'),
(11, 'Exempt-Private');

-- --------------------------------------------------------

--
-- Table structure for table `color`
--

CREATE TABLE `color` (
  `color_id` int(11) NOT NULL,
  `color_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `color`
--

INSERT INTO `color` (`color_id`, `color_name`) VALUES
(1, 'Red'),
(2, 'Maroon'),
(3, 'Dark Red'),
(4, 'Brown'),
(5, 'Orange'),
(6, 'Coral'),
(7, 'Dark Orange'),
(8, 'Orange Red'),
(9, 'Yellow'),
(10, 'Gold'),
(11, 'Khaki'),
(12, 'Lemon Chiffon'),
(13, 'Lime'),
(14, 'Green Yellow'),
(15, 'Chartreuse'),
(16, 'Lime Green'),
(17, 'Green'),
(18, 'Forest Green'),
(19, 'Olive Drab'),
(20, 'Olive'),
(21, 'Teal'),
(22, 'Dark Cyan'),
(23, 'Medium Turquoise'),
(24, 'Turquoise'),
(25, 'Cyan'),
(26, 'Aqua'),
(27, 'Light Cyan'),
(28, 'Pale Turquoise'),
(29, 'Blue'),
(30, 'Dodger Blue'),
(31, 'Steel Blue'),
(32, 'Royal Blue'),
(33, 'Medium Blue'),
(34, 'Dark Blue'),
(35, 'Navy'),
(36, 'Midnight Blue'),
(37, 'Purple'),
(38, 'Indigo'),
(39, 'Slate Blue'),
(40, 'Medium Slate Blue'),
(41, 'Dark Slate Blue'),
(42, 'Medium Purple'),
(43, 'Dark Orchid'),
(44, 'Magenta'),
(45, 'Medium Orchid'),
(46, 'Medium Violet Red'),
(47, 'Dark Violet'),
(48, 'Purple'),
(49, 'Pink'),
(50, 'Light Pink'),
(51, 'Hot Pink'),
(52, 'Deep Pink'),
(53, 'White'),
(54, 'Snow'),
(55, 'Honeydew'),
(56, 'Mint Cream'),
(57, 'Azure'),
(58, 'Alice Blue'),
(59, 'Ghost White'),
(60, 'White Smoke'),
(61, 'Seashell'),
(62, 'Beige'),
(63, 'Old Lace'),
(64, 'Floral White'),
(65, 'Ivory'),
(66, 'Antique White'),
(67, 'Linen'),
(68, 'Lavender Blush'),
(69, 'Misty Rose'),
(70, 'Gainsboro'),
(71, 'Light Gray'),
(72, 'Silver'),
(73, 'Dark Gray'),
(74, 'Gray'),
(75, 'Dim Gray'),
(76, 'Light Slate Gray'),
(77, 'Slate Gray'),
(78, 'Dark Slate Gray'),
(79, 'Black');

-- --------------------------------------------------------

--
-- Table structure for table `fuel_types`
--

CREATE TABLE `fuel_types` (
  `id` int(11) NOT NULL,
  `fuel_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fuel_types`
--

INSERT INTO `fuel_types` (`id`, `fuel_name`) VALUES
(1, 'Gasoline'),
(2, 'LPG'),
(3, 'Diesel - None Turbo'),
(4, 'Turbo Diesel');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `contact_no` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `accept_terms` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`user_id`, `email`, `password`, `first_name`, `last_name`, `middle_name`, `contact_no`, `address`, `status`, `accept_terms`) VALUES
(2, 'kirvyheinrich@gmail.com', '$2y$10$JsDeKaEotLey6VJHz92ZKe7f3X4jaMt0BJg/NG/PQYOiC/Xw2ia2.', 'Kirvy', 'Heinrich', 'fuhrer', '0888861528', 'eqwrqwrqwrqwrewq', 1, 1),
(3, 'tecson.k.bsinfotech@gmail.com', '$2y$10$ddzangNM8PaV3MnT4aEXZurnx91HjvlXR49uxF2txpT1u5raHSZXC', 'Kenneth Gabriel', 'Tecson', 'Guimong', '09951260721', 'Mckinley BGC', 1, 1),
(5, 'teson@gmail.com', '$2y$10$ODaQXl04OOGtSghE8knT..7n4feNS92oEKAGw1bHTBBEAfcaWINze', 'Kennenth', 'Tecson', 'Guimong', '0888861528', 'BGC Mckinley', 0, 1),
(7, 'kyrispy2@gmail.com', '$2y$10$UZjsKFGTkDd3F1braTxnouROfZ9dQqj2tzL/WDw1SXPhznl38FbMG', 'Kenenth', 'Tecson', 'Guimong', '0888861528', 'BGC Mckinley', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `make`
--

CREATE TABLE `make` (
  `make_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `make`
--

INSERT INTO `make` (`make_name`) VALUES
('Acura'),
('Alfa Romeo'),
('Aston Martin'),
('Audi'),
('Bentley'),
('BMW'),
('Buick'),
('Cadillac'),
('Chevrolet'),
('Chrysler'),
('Dodge'),
('Ferrari'),
('Fiat'),
('Ford'),
('Genesis'),
('GMC'),
('Honda'),
('Hyundai'),
('Infiniti'),
('Jaguar'),
('Jeep'),
('Kia'),
('Lamborghini'),
('Land Rover'),
('Lexus'),
('Lincoln'),
('Lotus'),
('Maserati'),
('Mazda'),
('McLaren'),
('Mercedes-Benz'),
('MINI'),
('Mitsubishi'),
('Nissan'),
('Porsche'),
('Ram'),
('Rolls-Royce'),
('Rusi'),
('Subaru'),
('Suzuki'),
('Tesla'),
('Toyota'),
('Volkswagen'),
('Volvo');

-- --------------------------------------------------------

--
-- Table structure for table `mvtypes`
--

CREATE TABLE `mvtypes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mvtypes`
--

INSERT INTO `mvtypes` (`id`, `name`) VALUES
(1, 'Car'),
(2, 'Mopeds (0-49 cc)'),
(3, 'Motorcycle w/ side car'),
(4, 'Motorcycle w/o side car'),
(5, 'Non-conventional MC (Car)'),
(6, 'Shuttle Bus'),
(7, 'Sports Utility Vehicle'),
(8, 'Tourist Bus'),
(9, 'Tricycle'),
(10, 'Truck Bus'),
(11, 'Trucks'),
(12, 'Utility Vehicle'),
(13, 'School bus'),
(14, 'Rebuilt'),
(15, 'Mobil Clinic'),
(16, 'Trailer');

-- --------------------------------------------------------

--
-- Table structure for table `purposes`
--

CREATE TABLE `purposes` (
  `id` int(11) NOT NULL,
  `purpose_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purposes`
--

INSERT INTO `purposes` (`id`, `purpose_name`) VALUES
(1, 'For Registration'),
(2, 'For Compliance'),
(3, 'Plate Redemption');

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `id` int(11) NOT NULL,
  `region_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`id`, `region_name`) VALUES
(1, 'Region I'),
(2, 'Region II'),
(3, 'Region III'),
(4, 'Region IV‑A'),
(5, 'MIMAROPA'),
(6, 'Region V'),
(7, 'Region VI'),
(8, 'Region VII'),
(9, 'Region VIII'),
(10, 'Region IX'),
(11, 'Region X'),
(12, 'Region XI'),
(13, 'Region XII'),
(14, 'Region XIII'),
(15, 'NCR'),
(16, 'CAR'),
(17, 'BARMM');

-- --------------------------------------------------------

--
-- Table structure for table `schedule_list`
--

CREATE TABLE `schedule_list` (
  `id` int(30) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `qty_of_person` int(23) NOT NULL,
  `reserve_count` int(23) NOT NULL DEFAULT 0,
  `price_3` decimal(10,2) NOT NULL DEFAULT 0.00,
  `price_2` decimal(10,2) NOT NULL,
  `price_1` decimal(10,2) NOT NULL DEFAULT 0.00,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime DEFAULT NULL,
  `availability` varchar(10) NOT NULL DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule_list`
--

INSERT INTO `schedule_list` (`id`, `title`, `description`, `qty_of_person`, `reserve_count`, `price_3`, `price_2`, `price_1`, `start_datetime`, `end_datetime`, `availability`) VALUES
(1, 'Vehicle Emission', 'Vehicle Emission', 2, -1, '0.00', '500.00', '300.00', '2023-12-29 13:00:00', '2023-12-29 13:30:00', 'available'),
(2, 'Vehicle Emission', 'Vehicle', 2, 2, '700.00', '400.00', '500.00', '2023-12-30 20:00:00', '2023-12-30 13:00:00', 'available'),
(3, 'Vehicle Emission', 'Emission', 2, 1, '800.00', '500.00', '300.00', '2024-02-10 13:00:00', '2024-02-10 13:30:00', 'available'),
(4, 'Vehicle Emission', 'Vehicle', 5, 0, '700.00', '500.00', '300.00', '2024-02-12 07:00:00', '2024-01-12 07:30:00', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `smurf_admin`
--

CREATE TABLE `smurf_admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `smurf_admin`
--

INSERT INTO `smurf_admin` (`id`, `username`, `password`) VALUES
(1, 'tk', '0503');

-- --------------------------------------------------------

--
-- Table structure for table `super_admin`
--

CREATE TABLE `super_admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `super_admin`
--

INSERT INTO `super_admin` (`id`, `username`, `password`) VALUES
(1, 'kenneth', '123\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `test_result`
--

CREATE TABLE `test_result` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `HC` decimal(8,2) DEFAULT NULL,
  `CO` decimal(8,2) DEFAULT NULL,
  `CO2` decimal(8,2) DEFAULT NULL,
  `O2` decimal(8,2) DEFAULT NULL,
  `N` decimal(8,2) DEFAULT NULL,
  `RPM` decimal(8,2) DEFAULT NULL,
  `K_AVE` decimal(8,2) DEFAULT NULL,
  `testing_status` int(11) DEFAULT 0,
  `record_status` int(11) DEFAULT 0,
  `vehicle_img` varchar(255) DEFAULT NULL,
  `Tested` tinyint(1) DEFAULT NULL,
  `Uploaded_Image` tinyint(1) DEFAULT NULL,
  `Retest` tinyint(1) DEFAULT NULL,
  `Uploaded` tinyint(1) DEFAULT NULL,
  `Motorcycle` tinyint(1) DEFAULT NULL,
  `Rebuilt` tinyint(1) DEFAULT NULL,
  `Valid` tinyint(1) DEFAULT 0,
  `auth_code` int(7) DEFAULT NULL,
  `finalize` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_result`
--

INSERT INTO `test_result` (`id`, `booking_id`, `HC`, `CO`, `CO2`, `O2`, `N`, `RPM`, `K_AVE`, `testing_status`, `record_status`, `vehicle_img`, `Tested`, `Uploaded_Image`, `Retest`, `Uploaded`, `Motorcycle`, `Rebuilt`, `Valid`, `auth_code`, `finalize`) VALUES
(5, 2, '0.00', '0.00', '14.00', '2.00', '0.00', '0.00', '0.00', 1, 1, 'assets/img/test_img/1011917966.jpg', 1, 1, NULL, 1, NULL, NULL, 1, 6396447, 1),
(6, 4, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 2, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0),
(7, 3, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 2, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_eries`
--

CREATE TABLE `vehicle_eries` (
  `series_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_series`
--

CREATE TABLE `vehicle_series` (
  `series_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cancellation_reasons`
--
ALTER TABLE `cancellation_reasons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cancellation_reasons_booking_id` (`booking_id`),
  ADD KEY `fk_cancellation_reasons_smurf_admin_id` (`cancel_by_smurf_admin`),
  ADD KEY `fk_cancellation_reasons_super_admin_id` (`cancel_by_super_admin`),
  ADD KEY `fk_cancellation_reasons_canceled_by_user` (`canceled_by_user`);

--
-- Indexes for table `car_emission`
--
ALTER TABLE `car_emission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `fk_car_emission_user_id` (`user_id`),
  ADD KEY `fk_car_emission_smurf_admin` (`smurf_admin_id`);

--
-- Indexes for table `classifications`
--
ALTER TABLE `classifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `color`
--
ALTER TABLE `color`
  ADD PRIMARY KEY (`color_id`);

--
-- Indexes for table `fuel_types`
--
ALTER TABLE `fuel_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `make`
--
ALTER TABLE `make`
  ADD PRIMARY KEY (`make_name`);

--
-- Indexes for table `mvtypes`
--
ALTER TABLE `mvtypes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purposes`
--
ALTER TABLE `purposes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule_list`
--
ALTER TABLE `schedule_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `smurf_admin`
--
ALTER TABLE `smurf_admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `super_admin`
--
ALTER TABLE `super_admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `test_result`
--
ALTER TABLE `test_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `vehicle_eries`
--
ALTER TABLE `vehicle_eries`
  ADD PRIMARY KEY (`series_name`);

--
-- Indexes for table `vehicle_series`
--
ALTER TABLE `vehicle_series`
  ADD PRIMARY KEY (`series_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cancellation_reasons`
--
ALTER TABLE `cancellation_reasons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `car_emission`
--
ALTER TABLE `car_emission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `classifications`
--
ALTER TABLE `classifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `color`
--
ALTER TABLE `color`
  MODIFY `color_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `fuel_types`
--
ALTER TABLE `fuel_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `mvtypes`
--
ALTER TABLE `mvtypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `purposes`
--
ALTER TABLE `purposes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `schedule_list`
--
ALTER TABLE `schedule_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `smurf_admin`
--
ALTER TABLE `smurf_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `super_admin`
--
ALTER TABLE `super_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `test_result`
--
ALTER TABLE `test_result`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cancellation_reasons`
--
ALTER TABLE `cancellation_reasons`
  ADD CONSTRAINT `fk_cancellation_reasons_booking_id` FOREIGN KEY (`booking_id`) REFERENCES `car_emission` (`id`),
  ADD CONSTRAINT `fk_cancellation_reasons_canceled_by_user` FOREIGN KEY (`canceled_by_user`) REFERENCES `login` (`user_id`),
  ADD CONSTRAINT `fk_cancellation_reasons_smurf_admin_id` FOREIGN KEY (`cancel_by_smurf_admin`) REFERENCES `smurf_admin` (`id`),
  ADD CONSTRAINT `fk_cancellation_reasons_super_admin_id` FOREIGN KEY (`cancel_by_super_admin`) REFERENCES `super_admin` (`id`);

--
-- Constraints for table `car_emission`
--
ALTER TABLE `car_emission`
  ADD CONSTRAINT `car_emission_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `schedule_list` (`id`),
  ADD CONSTRAINT `fk_car_emission_smurf_admin` FOREIGN KEY (`smurf_admin_id`) REFERENCES `smurf_admin` (`id`),
  ADD CONSTRAINT `fk_car_emission_user_id` FOREIGN KEY (`user_id`) REFERENCES `login` (`user_id`);

--
-- Constraints for table `test_result`
--
ALTER TABLE `test_result`
  ADD CONSTRAINT `test_result_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `car_emission` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
