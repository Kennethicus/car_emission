-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 28, 2024 at 01:58 PM
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
  `smurf_admin_id` int(11) DEFAULT NULL,
  `payAmount1` varchar(250) DEFAULT NULL,
  `receipt1` varchar(255) DEFAULT NULL,
  `reference1` varchar(50) DEFAULT NULL,
  `paymentlock1` int(11) NOT NULL DEFAULT 0,
  `payment_date1` datetime DEFAULT NULL,
  `return_reason1` varchar(255) DEFAULT NULL,
  `return_switch_1` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car_emission`
--

INSERT INTO `car_emission` (`id`, `event_id`, `user_id`, `plate_number`, `customer_email`, `customer_first_name`, `customer_middle_name`, `customer_last_name`, `address`, `status`, `app_date`, `vehicle_cr_no`, `vehicle_or_no`, `first_reg_date`, `year_model`, `fuel_type`, `purpose`, `mv_type`, `region`, `mv_file_no`, `classification`, `payment_date`, `petc_or`, `amount`, `organization`, `engine`, `chassis`, `make`, `series`, `color`, `gross_weight`, `net_capacity`, `cec_number`, `mvect_operator`, `car_picture`, `paymentMethod`, `paymentStatus`, `ticketing_id`, `reference_number`, `date_tested`, `smurf_admin_id`, `payAmount1`, `receipt1`, `reference1`, `paymentlock1`, `payment_date1`, `return_reason1`, `return_switch_1`) VALUES
(1, 2, 3, 'NBAA 7888', '', 'Kenneth Gabriel', 'Guimong', 'Tecson', '12', 'booked', '2024-01-28 20:10:02', '12', '12', '2024-01-15', '2024', 'Gasoline', 'For Registration', 'Shuttle Bus', 'Region I', '12', 'Diplomatic-Consular Corps', '0000-00-00 00:00:00', '0', '600.00', '1', '12', '12', 'Toyota', 'Sedan', 'Red', '12.00', '12.00', '0', 'pending', 'uploads/car_picture/65b6441a95021_image.png', 'pending', 'unpaid', '9123783', '96088', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0, NULL, 'Unmatch payment receipt, Unmatch reference number, Invalid payment receipt, Invalid reference number', 3);

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
-- Table structure for table `mv_types`
--

CREATE TABLE `mv_types` (
  `mv1` varchar(255) DEFAULT NULL,
  `mv2` varchar(255) DEFAULT NULL,
  `mv3` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mv_types`
--

INSERT INTO `mv_types` (`mv1`, `mv2`, `mv3`) VALUES
('Mopeds (0-49 cc)', NULL, NULL),
('Motorcycle w/ side car', NULL, NULL),
('Motorcycle w/o side car', NULL, NULL),
('Non-conventional MC (Car)', NULL, NULL),
('Tricycle', NULL, NULL),
(NULL, 'Car', NULL),
(NULL, 'Sports utility Vehicle', NULL),
(NULL, 'Utility Vehicle', NULL),
(NULL, 'Rebuilt', NULL),
(NULL, 'Mobil Clinic', NULL),
(NULL, NULL, 'School bus'),
(NULL, NULL, 'Shuttle Bus'),
(NULL, NULL, 'Tourist Bus'),
(NULL, NULL, 'Truck Bus'),
(NULL, NULL, 'Trucks'),
(NULL, NULL, 'Trailer');

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int(11) NOT NULL,
  `gcash_acc_name` varchar(255) NOT NULL,
  `gcash_acc_no` varchar(20) NOT NULL,
  `paymaya_acc_name` varchar(255) NOT NULL,
  `paymaya_acc_no` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `gcash_acc_name`, `gcash_acc_no`, `paymaya_acc_name`, `paymaya_acc_no`) VALUES
(1, 'Gilene Mardo', '09951260721', 'Richard Alaurin', '09927664189');

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
(1, 'Vehicle Emission', 'emission', 5, 5, '600.00', '500.00', '300.00', '2024-01-28 08:00:00', '2024-01-28 08:30:00', 'available'),
(2, 'Vehicle Emission', 'emission', 5, 1, '600.00', '500.00', '300.00', '2024-01-28 08:30:00', '2024-01-28 09:00:00', 'available'),
(3, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-28 09:00:00', '2024-01-28 09:30:00', 'available'),
(4, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-28 09:30:00', '2024-01-28 10:00:00', 'available'),
(5, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-28 10:00:00', '2024-01-28 10:30:00', 'available'),
(6, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-28 10:30:00', '2024-01-28 11:00:00', 'available'),
(7, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-28 11:00:00', '2024-01-28 11:30:00', 'available'),
(8, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-28 11:30:00', '2024-01-28 12:00:00', 'available'),
(9, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-28 13:00:00', '2024-01-28 13:30:00', 'available'),
(10, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-28 13:30:00', '2024-01-28 14:00:00', 'available'),
(11, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-28 14:00:00', '2024-01-28 14:30:00', 'available'),
(12, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-28 14:30:00', '2024-01-28 15:00:00', 'available'),
(13, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-28 15:00:00', '2024-01-28 15:30:00', 'available'),
(14, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-28 15:30:00', '2024-01-28 16:00:00', 'available'),
(15, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-28 16:00:00', '2024-01-28 16:30:00', 'available'),
(16, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-28 16:30:00', '2024-01-28 17:00:00', 'available');

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
(1, 2, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0),
(2, 3, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0),
(3, 1, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0);

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
  ADD KEY `fk_cancellation_reasons_smurf_admin_id` (`cancel_by_smurf_admin`),
  ADD KEY `fk_cancellation_reasons_super_admin_id` (`cancel_by_super_admin`),
  ADD KEY `fk_cancellation_reasons_canceled_by_user` (`canceled_by_user`),
  ADD KEY `fk_cancellation_reasons_booking_id` (`booking_id`);

--
-- Indexes for table `car_emission`
--
ALTER TABLE `car_emission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_car_emission_user_id` (`user_id`),
  ADD KEY `fk_car_emission_smurf_admin` (`smurf_admin_id`),
  ADD KEY `car_emission_ibfk_1` (`event_id`);

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
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `car_emission`
--
ALTER TABLE `car_emission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cancellation_reasons`
--
ALTER TABLE `cancellation_reasons`
  ADD CONSTRAINT `fk_cancellation_reasons_booking_id` FOREIGN KEY (`booking_id`) REFERENCES `car_emission` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cancellation_reasons_canceled_by_user` FOREIGN KEY (`canceled_by_user`) REFERENCES `login` (`user_id`),
  ADD CONSTRAINT `fk_cancellation_reasons_smurf_admin_id` FOREIGN KEY (`cancel_by_smurf_admin`) REFERENCES `smurf_admin` (`id`),
  ADD CONSTRAINT `fk_cancellation_reasons_super_admin_id` FOREIGN KEY (`cancel_by_super_admin`) REFERENCES `super_admin` (`id`);

--
-- Constraints for table `car_emission`
--
ALTER TABLE `car_emission`
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
