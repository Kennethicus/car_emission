-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 23, 2024 at 03:34 AM
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
-- Database: `greenfrog_test`
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
  `date_tested` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car_emission`
--

INSERT INTO `car_emission` (`id`, `event_id`, `user_id`, `plate_number`, `customer_email`, `customer_first_name`, `customer_middle_name`, `customer_last_name`, `address`, `status`, `app_date`, `vehicle_cr_no`, `vehicle_or_no`, `first_reg_date`, `year_model`, `fuel_type`, `purpose`, `mv_type`, `region`, `mv_file_no`, `classification`, `payment_date`, `petc_or`, `amount`, `organization`, `engine`, `chassis`, `make`, `series`, `color`, `gross_weight`, `net_capacity`, `cec_number`, `mvect_operator`, `car_picture`, `paymentMethod`, `paymentStatus`, `ticketing_id`, `reference_number`, `date_tested`) VALUES
(1, 0, 3, 'NBAA 1232', 'tecson.k.bsinfotech@gmail.com', 'Kenneth Gabriel', 'Guimong', 'Tecson', 'Mckinley BGC', 'canceled', '2023-12-27 20:00:00', '12', '12', '2023-12-06', '2023', 'Gasoline', 'Meeting', 'Type1', 'Region1', '13', 'Compact', '0000-00-00 00:00:00', '0', '500.00', 'KORAK', '12', '1543', 'Toyota', 'Sedan', 'Red', '12.00', '12.00', '0', 'pending', 'uploads/icon.png', 'cash', 'unpaid', '2293686', '76012', '0000-00-00 00:00:00'),
(2, 0, 3, 'NBAA 1332', 'tecson.k.bsinfotech@gmail.com', 'Kenneth Gabriel', 'Guimong', 'Tecson', 'Mckinley BGC', 'booked', '2023-12-27 22:16:00', '12', '12', '2023-11-29', '2019', 'Diesel - None Turbo', 'For Registration', 'Tricycle', 'Region I', '13', 'Diplomatic-Consular Corps', '2024-01-15 07:13:14', '19761', '400.00', 'KORAK', '12', '1543', 'Toyota', 'Sedan', 'Red', '12.00', '12.00', '202400000019761', 'Operator 1', 'uploads/1011917966.jpg', 'cash', 'paid', '5052674', '35401', '2024-01-15 06:11:57'),
(3, 0, 3, 'PBAA 1234', 'tecson.k.bsinfotech@gmail.com', 'Kenneth Gabriel', 'Guimong', 'Tecson', 'Mckinley BGC', 'doned', '2023-12-28 03:48:00', '12', '12', '2023-11-27', '2023', 'LPG', 'For Registration', 'Car', 'Region I', '13', 'Diplomatic-Consular Corps', '2024-01-12 04:38:34', '81476', '400.00', 'KORAK', '12', '1543', 'Toyota', 'Sedan', 'Red', '12.00', '12.00', '0', 'Operator 2', 'uploads/R.jpg', 'cash', 'paid', '5480037', '72550', '2024-01-11 06:35:09'),
(4, 0, 3, 'PBAA 1221', 'tecson.k.bsinfotech@gmail.com', 'Kenneth Gabriel', 'Guimong', 'Tecson', 'Mckinley BGC', 'booked', '2024-01-15 17:08:00', '12', '12', '2024-01-11', '2024', 'Gasoline', 'For Registration', 'Car', 'Region I', '12', 'Diplomatic-Consular Corps', '0000-00-00 00:00:00', '0', '500.00', '12', '12', '12', 'Toyota', 'Sedan', 'Red', '12.00', '12.00', '0', 'Operator 1', 'uploads/star.png', 'cash', 'unpaid', '3414908', '50328', '2024-01-15 09:32:22');

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
(5, 'teson@gmail.com', '$2y$10$ODaQXl04OOGtSghE8knT..7n4feNS92oEKAGw1bHTBBEAfcaWINze', 'Kenenth', 'Tecson', 'Guimong', '0888861528', 'BGC Mckinley', 0, 1),
(7, 'kyrispy2@gmail.com', '$2y$10$UZjsKFGTkDd3F1braTxnouROfZ9dQqj2tzL/WDw1SXPhznl38FbMG', 'Kenenth', 'Tecson', 'Guimong', '0888861528', 'BGC Mckinley', 1, 1);

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
(1, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-01 08:00:00', '2024-01-01 08:30:00', 'available'),
(2, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-01 08:30:00', '2024-01-01 09:00:00', 'available'),
(3, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-01 09:00:00', '2024-01-01 09:30:00', 'available'),
(4, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-01 09:30:00', '2024-01-01 10:00:00', 'available'),
(5, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-01 10:00:00', '2024-01-01 10:30:00', 'available'),
(6, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-01 10:30:00', '2024-01-01 11:00:00', 'available'),
(7, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-01 11:00:00', '2024-01-01 11:30:00', 'available'),
(8, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-01 11:30:00', '2024-01-01 12:00:00', 'available'),
(9, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-01 13:00:00', '2024-01-01 13:30:00', 'available'),
(10, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-01 13:30:00', '2024-01-01 14:00:00', 'available'),
(11, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-01 14:00:00', '2024-01-01 14:30:00', 'available'),
(12, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-01 14:30:00', '2024-01-01 15:00:00', 'available'),
(13, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-01 15:00:00', '2024-01-01 15:30:00', 'available'),
(14, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-01 15:30:00', '2024-01-01 16:00:00', 'available'),
(15, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-01 16:00:00', '2024-01-01 16:30:00', 'available'),
(16, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-01 16:30:00', '2024-01-01 17:00:00', 'available'),
(17, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-02 08:00:00', '2024-01-02 08:30:00', 'available'),
(18, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-02 08:30:00', '2024-01-02 09:00:00', 'available'),
(19, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-02 09:00:00', '2024-01-02 09:30:00', 'available'),
(20, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-02 09:30:00', '2024-01-02 10:00:00', 'available'),
(21, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-02 10:00:00', '2024-01-02 10:30:00', 'available'),
(22, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-02 10:30:00', '2024-01-02 11:00:00', 'available'),
(23, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-02 11:00:00', '2024-01-02 11:30:00', 'available'),
(24, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-02 11:30:00', '2024-01-02 12:00:00', 'available'),
(25, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-02 13:00:00', '2024-01-02 13:30:00', 'available'),
(26, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-02 13:30:00', '2024-01-02 14:00:00', 'available'),
(27, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-02 14:00:00', '2024-01-02 14:30:00', 'available'),
(28, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-02 14:30:00', '2024-01-02 15:00:00', 'available'),
(29, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-02 15:00:00', '2024-01-02 15:30:00', 'available'),
(30, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-02 15:30:00', '2024-01-02 16:00:00', 'available'),
(31, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-02 16:00:00', '2024-01-02 16:30:00', 'available'),
(32, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-02 16:30:00', '2024-01-02 17:00:00', 'available'),
(33, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-03 08:00:00', '2024-01-03 08:30:00', 'available'),
(34, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-03 08:30:00', '2024-01-03 09:00:00', 'available'),
(35, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-03 09:00:00', '2024-01-03 09:30:00', 'available'),
(36, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-03 09:30:00', '2024-01-03 10:00:00', 'available'),
(37, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-03 10:00:00', '2024-01-03 10:30:00', 'available'),
(38, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-03 10:30:00', '2024-01-03 11:00:00', 'available'),
(39, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-03 11:00:00', '2024-01-03 11:30:00', 'available'),
(40, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-03 11:30:00', '2024-01-03 12:00:00', 'available'),
(41, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-03 13:00:00', '2024-01-03 13:30:00', 'available'),
(42, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-03 13:30:00', '2024-01-03 14:00:00', 'available'),
(43, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-03 14:00:00', '2024-01-03 14:30:00', 'available'),
(44, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-03 14:30:00', '2024-01-03 15:00:00', 'available'),
(45, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-03 15:00:00', '2024-01-03 15:30:00', 'available'),
(46, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-03 15:30:00', '2024-01-03 16:00:00', 'available'),
(47, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-03 16:00:00', '2024-01-03 16:30:00', 'available'),
(48, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-03 16:30:00', '2024-01-03 17:00:00', 'available'),
(49, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-04 08:00:00', '2024-01-04 08:30:00', 'available'),
(50, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-04 08:30:00', '2024-01-04 09:00:00', 'available'),
(51, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-04 09:00:00', '2024-01-04 09:30:00', 'available'),
(52, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-04 09:30:00', '2024-01-04 10:00:00', 'available'),
(53, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-04 10:00:00', '2024-01-04 10:30:00', 'available'),
(54, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-04 10:30:00', '2024-01-04 11:00:00', 'available'),
(55, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-04 11:00:00', '2024-01-04 11:30:00', 'available'),
(56, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-04 11:30:00', '2024-01-04 12:00:00', 'available'),
(57, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-04 13:00:00', '2024-01-04 13:30:00', 'available'),
(58, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-04 13:30:00', '2024-01-04 14:00:00', 'available'),
(59, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-04 14:00:00', '2024-01-04 14:30:00', 'available'),
(60, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-04 14:30:00', '2024-01-04 15:00:00', 'available'),
(61, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-04 15:00:00', '2024-01-04 15:30:00', 'available'),
(62, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-04 15:30:00', '2024-01-04 16:00:00', 'available'),
(63, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-04 16:00:00', '2024-01-04 16:30:00', 'available'),
(64, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-04 16:30:00', '2024-01-04 17:00:00', 'available'),
(65, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-05 08:00:00', '2024-01-05 08:30:00', 'available'),
(66, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-05 08:30:00', '2024-01-05 09:00:00', 'available'),
(67, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-05 09:00:00', '2024-01-05 09:30:00', 'available'),
(68, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-05 09:30:00', '2024-01-05 10:00:00', 'available'),
(69, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-05 10:00:00', '2024-01-05 10:30:00', 'available'),
(70, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-05 10:30:00', '2024-01-05 11:00:00', 'available'),
(71, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-05 11:00:00', '2024-01-05 11:30:00', 'available'),
(72, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-05 11:30:00', '2024-01-05 12:00:00', 'available'),
(73, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-05 13:00:00', '2024-01-05 13:30:00', 'available'),
(74, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-05 13:30:00', '2024-01-05 14:00:00', 'available'),
(75, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-05 14:00:00', '2024-01-05 14:30:00', 'available'),
(76, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-05 14:30:00', '2024-01-05 15:00:00', 'available'),
(77, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-05 15:00:00', '2024-01-05 15:30:00', 'available'),
(78, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-05 15:30:00', '2024-01-05 16:00:00', 'available'),
(79, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-05 16:00:00', '2024-01-05 16:30:00', 'available'),
(80, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-05 16:30:00', '2024-01-05 17:00:00', 'available'),
(81, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-08 08:00:00', '2024-01-08 08:30:00', 'available'),
(82, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-08 08:30:00', '2024-01-08 09:00:00', 'available'),
(83, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-08 09:00:00', '2024-01-08 09:30:00', 'available'),
(84, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-08 09:30:00', '2024-01-08 10:00:00', 'available'),
(85, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-08 10:00:00', '2024-01-08 10:30:00', 'available'),
(86, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-08 10:30:00', '2024-01-08 11:00:00', 'available'),
(87, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-08 11:00:00', '2024-01-08 11:30:00', 'available'),
(88, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-08 11:30:00', '2024-01-08 12:00:00', 'available'),
(89, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-08 13:00:00', '2024-01-08 13:30:00', 'available'),
(90, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-08 13:30:00', '2024-01-08 14:00:00', 'available'),
(91, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-08 14:00:00', '2024-01-08 14:30:00', 'available'),
(92, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-08 14:30:00', '2024-01-08 15:00:00', 'available'),
(93, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-08 15:00:00', '2024-01-08 15:30:00', 'available'),
(94, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-08 15:30:00', '2024-01-08 16:00:00', 'available'),
(95, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-08 16:00:00', '2024-01-08 16:30:00', 'available'),
(96, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-08 16:30:00', '2024-01-08 17:00:00', 'available'),
(97, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-09 08:00:00', '2024-01-09 08:30:00', 'available'),
(98, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-09 08:30:00', '2024-01-09 09:00:00', 'available'),
(99, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-09 09:00:00', '2024-01-09 09:30:00', 'available'),
(100, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-09 09:30:00', '2024-01-09 10:00:00', 'available'),
(101, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-09 10:00:00', '2024-01-09 10:30:00', 'available'),
(102, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-09 10:30:00', '2024-01-09 11:00:00', 'available'),
(103, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-09 11:00:00', '2024-01-09 11:30:00', 'available'),
(104, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-09 11:30:00', '2024-01-09 12:00:00', 'available'),
(105, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-09 13:00:00', '2024-01-09 13:30:00', 'available'),
(106, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-09 13:30:00', '2024-01-09 14:00:00', 'available'),
(107, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-09 14:00:00', '2024-01-09 14:30:00', 'available'),
(108, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-09 14:30:00', '2024-01-09 15:00:00', 'available'),
(109, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-09 15:00:00', '2024-01-09 15:30:00', 'available'),
(110, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-09 15:30:00', '2024-01-09 16:00:00', 'available'),
(111, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-09 16:00:00', '2024-01-09 16:30:00', 'available'),
(112, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-09 16:30:00', '2024-01-09 17:00:00', 'available'),
(129, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-11 08:00:00', '2024-01-11 08:30:00', 'available'),
(130, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-11 08:30:00', '2024-01-11 09:00:00', 'available'),
(131, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-11 09:00:00', '2024-01-11 09:30:00', 'available'),
(132, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-11 09:30:00', '2024-01-11 10:00:00', 'available'),
(133, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-11 10:00:00', '2024-01-11 10:30:00', 'available'),
(134, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-11 10:30:00', '2024-01-11 11:00:00', 'available'),
(135, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-11 11:00:00', '2024-01-11 11:30:00', 'available'),
(136, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-11 11:30:00', '2024-01-11 12:00:00', 'available'),
(137, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-11 13:00:00', '2024-01-11 13:30:00', 'available'),
(138, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-11 13:30:00', '2024-01-11 14:00:00', 'available'),
(139, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-11 14:00:00', '2024-01-11 14:30:00', 'available'),
(140, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-11 14:30:00', '2024-01-11 15:00:00', 'available'),
(141, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-11 15:00:00', '2024-01-11 15:30:00', 'available'),
(142, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-11 15:30:00', '2024-01-11 16:00:00', 'available'),
(143, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-11 16:00:00', '2024-01-11 16:30:00', 'available'),
(144, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-11 16:30:00', '2024-01-11 17:00:00', 'available'),
(145, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-12 08:00:00', '2024-01-12 08:30:00', 'available'),
(146, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-12 08:30:00', '2024-01-12 09:00:00', 'available'),
(147, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-12 09:00:00', '2024-01-12 09:30:00', 'available'),
(148, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-12 09:30:00', '2024-01-12 10:00:00', 'available'),
(149, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-12 10:00:00', '2024-01-12 10:30:00', 'available'),
(150, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-12 10:30:00', '2024-01-12 11:00:00', 'available'),
(151, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-12 11:00:00', '2024-01-12 11:30:00', 'available'),
(152, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-12 11:30:00', '2024-01-12 12:00:00', 'available'),
(153, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-12 13:00:00', '2024-01-12 13:30:00', 'available'),
(154, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-12 13:30:00', '2024-01-12 14:00:00', 'available'),
(155, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-12 14:00:00', '2024-01-12 14:30:00', 'available'),
(156, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-12 14:30:00', '2024-01-12 15:00:00', 'available'),
(157, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-12 15:00:00', '2024-01-12 15:30:00', 'available'),
(158, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-12 15:30:00', '2024-01-12 16:00:00', 'available'),
(159, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-12 16:00:00', '2024-01-12 16:30:00', 'available'),
(160, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-12 16:30:00', '2024-01-12 17:00:00', 'available'),
(161, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-15 08:00:00', '2024-01-15 08:30:00', 'available'),
(162, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-15 08:30:00', '2024-01-15 09:00:00', 'available'),
(163, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-15 09:00:00', '2024-01-15 09:30:00', 'available'),
(164, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-15 09:30:00', '2024-01-15 10:00:00', 'available'),
(165, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-15 10:00:00', '2024-01-15 10:30:00', 'available'),
(166, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-15 10:30:00', '2024-01-15 11:00:00', 'available'),
(167, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-15 11:00:00', '2024-01-15 11:30:00', 'available'),
(168, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-15 11:30:00', '2024-01-15 12:00:00', 'available'),
(169, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-15 13:00:00', '2024-01-15 13:30:00', 'available'),
(170, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-15 13:30:00', '2024-01-15 14:00:00', 'available'),
(171, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-15 14:00:00', '2024-01-15 14:30:00', 'available'),
(172, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-15 14:30:00', '2024-01-15 15:00:00', 'available'),
(173, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-15 15:00:00', '2024-01-15 15:30:00', 'available'),
(174, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-15 15:30:00', '2024-01-15 16:00:00', 'available'),
(175, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-15 16:00:00', '2024-01-15 16:30:00', 'available'),
(176, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-15 16:30:00', '2024-01-15 17:00:00', 'available'),
(177, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-16 08:00:00', '2024-01-16 08:30:00', 'available'),
(178, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-16 08:30:00', '2024-01-16 09:00:00', 'available'),
(179, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-16 09:00:00', '2024-01-16 09:30:00', 'available'),
(180, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-16 09:30:00', '2024-01-16 10:00:00', 'available'),
(181, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-16 10:00:00', '2024-01-16 10:30:00', 'available'),
(182, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-16 10:30:00', '2024-01-16 11:00:00', 'available'),
(183, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-16 11:00:00', '2024-01-16 11:30:00', 'available'),
(184, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-16 11:30:00', '2024-01-16 12:00:00', 'available'),
(185, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-16 13:00:00', '2024-01-16 13:30:00', 'available'),
(186, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-16 13:30:00', '2024-01-16 14:00:00', 'available'),
(187, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-16 14:00:00', '2024-01-16 14:30:00', 'available'),
(188, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-16 14:30:00', '2024-01-16 15:00:00', 'available'),
(189, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-16 15:00:00', '2024-01-16 15:30:00', 'available'),
(190, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-16 15:30:00', '2024-01-16 16:00:00', 'available'),
(191, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-16 16:00:00', '2024-01-16 16:30:00', 'available'),
(192, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-16 16:30:00', '2024-01-16 17:00:00', 'available'),
(193, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-17 08:00:00', '2024-01-17 08:30:00', 'available'),
(194, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-17 08:30:00', '2024-01-17 09:00:00', 'available'),
(195, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-17 09:00:00', '2024-01-17 09:30:00', 'available'),
(196, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-17 09:30:00', '2024-01-17 10:00:00', 'available'),
(197, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-17 10:00:00', '2024-01-17 10:30:00', 'available'),
(198, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-17 10:30:00', '2024-01-17 11:00:00', 'available'),
(199, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-17 11:00:00', '2024-01-17 11:30:00', 'available'),
(200, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-17 11:30:00', '2024-01-17 12:00:00', 'available'),
(201, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-17 13:00:00', '2024-01-17 13:30:00', 'available'),
(202, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-17 13:30:00', '2024-01-17 14:00:00', 'available'),
(203, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-17 14:00:00', '2024-01-17 14:30:00', 'available'),
(204, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-17 14:30:00', '2024-01-17 15:00:00', 'available'),
(205, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-17 15:00:00', '2024-01-17 15:30:00', 'available'),
(206, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-17 15:30:00', '2024-01-17 16:00:00', 'available'),
(207, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-17 16:00:00', '2024-01-17 16:30:00', 'available'),
(208, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-17 16:30:00', '2024-01-17 17:00:00', 'available'),
(209, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-18 08:00:00', '2024-01-18 08:30:00', 'available'),
(210, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-18 08:30:00', '2024-01-18 09:00:00', 'available'),
(211, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-18 09:00:00', '2024-01-18 09:30:00', 'available'),
(212, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-18 09:30:00', '2024-01-18 10:00:00', 'available'),
(213, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-18 10:00:00', '2024-01-18 10:30:00', 'available'),
(214, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-18 10:30:00', '2024-01-18 11:00:00', 'available'),
(215, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-18 11:00:00', '2024-01-18 11:30:00', 'available'),
(216, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-18 11:30:00', '2024-01-18 12:00:00', 'available'),
(217, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-18 13:00:00', '2024-01-18 13:30:00', 'available'),
(218, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-18 13:30:00', '2024-01-18 14:00:00', 'available'),
(219, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-18 14:00:00', '2024-01-18 14:30:00', 'available'),
(220, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-18 14:30:00', '2024-01-18 15:00:00', 'available'),
(221, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-18 15:00:00', '2024-01-18 15:30:00', 'available'),
(222, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-18 15:30:00', '2024-01-18 16:00:00', 'available'),
(223, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-18 16:00:00', '2024-01-18 16:30:00', 'available'),
(224, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-18 16:30:00', '2024-01-18 17:00:00', 'available'),
(225, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-19 08:00:00', '2024-01-19 08:30:00', 'available'),
(226, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-19 08:30:00', '2024-01-19 09:00:00', 'available'),
(227, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-19 09:00:00', '2024-01-19 09:30:00', 'available'),
(228, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-19 09:30:00', '2024-01-19 10:00:00', 'available'),
(229, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-19 10:00:00', '2024-01-19 10:30:00', 'available'),
(230, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-19 10:30:00', '2024-01-19 11:00:00', 'available'),
(231, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-19 11:00:00', '2024-01-19 11:30:00', 'available'),
(232, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-19 11:30:00', '2024-01-19 12:00:00', 'available'),
(233, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-19 13:00:00', '2024-01-19 13:30:00', 'available'),
(234, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-19 13:30:00', '2024-01-19 14:00:00', 'available'),
(235, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-19 14:00:00', '2024-01-19 14:30:00', 'available'),
(236, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-19 14:30:00', '2024-01-19 15:00:00', 'available'),
(237, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-19 15:00:00', '2024-01-19 15:30:00', 'available'),
(238, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-19 15:30:00', '2024-01-19 16:00:00', 'available'),
(239, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-19 16:00:00', '2024-01-19 16:30:00', 'available'),
(240, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-19 16:30:00', '2024-01-19 17:00:00', 'available'),
(241, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-22 08:00:00', '2024-01-22 08:30:00', 'available'),
(242, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-22 08:30:00', '2024-01-22 09:00:00', 'available'),
(243, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-22 09:00:00', '2024-01-22 09:30:00', 'available'),
(244, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-22 09:30:00', '2024-01-22 10:00:00', 'available'),
(245, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-22 10:00:00', '2024-01-22 10:30:00', 'available'),
(246, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-22 10:30:00', '2024-01-22 11:00:00', 'available'),
(247, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-22 11:00:00', '2024-01-22 11:30:00', 'available'),
(248, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-22 11:30:00', '2024-01-22 12:00:00', 'available'),
(249, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-22 13:00:00', '2024-01-22 13:30:00', 'available'),
(250, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-22 13:30:00', '2024-01-22 14:00:00', 'available'),
(251, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-22 14:00:00', '2024-01-22 14:30:00', 'available'),
(252, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-22 14:30:00', '2024-01-22 15:00:00', 'available'),
(253, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-22 15:00:00', '2024-01-22 15:30:00', 'available'),
(254, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-22 15:30:00', '2024-01-22 16:00:00', 'available'),
(255, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-22 16:00:00', '2024-01-22 16:30:00', 'available'),
(256, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-22 16:30:00', '2024-01-22 17:00:00', 'available'),
(257, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-23 08:00:00', '2024-01-23 08:30:00', 'available'),
(258, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-23 08:30:00', '2024-01-23 09:00:00', 'available'),
(259, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-23 09:00:00', '2024-01-23 09:30:00', 'available'),
(260, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-23 09:30:00', '2024-01-23 10:00:00', 'available'),
(261, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-23 10:00:00', '2024-01-23 10:30:00', 'available'),
(262, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-23 10:30:00', '2024-01-23 11:00:00', 'available'),
(263, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-23 11:00:00', '2024-01-23 11:30:00', 'available'),
(264, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-23 11:30:00', '2024-01-23 12:00:00', 'available'),
(265, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-23 13:00:00', '2024-01-23 13:30:00', 'available'),
(266, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-23 13:30:00', '2024-01-23 14:00:00', 'available'),
(267, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-23 14:00:00', '2024-01-23 14:30:00', 'available'),
(268, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-23 14:30:00', '2024-01-23 15:00:00', 'available'),
(269, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-23 15:00:00', '2024-01-23 15:30:00', 'available'),
(270, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-23 15:30:00', '2024-01-23 16:00:00', 'available'),
(271, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-23 16:00:00', '2024-01-23 16:30:00', 'available'),
(272, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-23 16:30:00', '2024-01-23 17:00:00', 'available'),
(273, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-24 08:00:00', '2024-01-24 08:30:00', 'available'),
(274, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-24 08:30:00', '2024-01-24 09:00:00', 'available'),
(275, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-24 09:00:00', '2024-01-24 09:30:00', 'available'),
(276, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-24 09:30:00', '2024-01-24 10:00:00', 'available'),
(277, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-24 10:00:00', '2024-01-24 10:30:00', 'available'),
(278, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-24 10:30:00', '2024-01-24 11:00:00', 'available'),
(279, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-24 11:00:00', '2024-01-24 11:30:00', 'available'),
(280, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-24 11:30:00', '2024-01-24 12:00:00', 'available'),
(281, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-24 13:00:00', '2024-01-24 13:30:00', 'available'),
(282, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-24 13:30:00', '2024-01-24 14:00:00', 'available'),
(283, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-24 14:00:00', '2024-01-24 14:30:00', 'available'),
(284, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-24 14:30:00', '2024-01-24 15:00:00', 'available'),
(285, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-24 15:00:00', '2024-01-24 15:30:00', 'available'),
(286, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-24 15:30:00', '2024-01-24 16:00:00', 'available'),
(287, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-24 16:00:00', '2024-01-24 16:30:00', 'available'),
(288, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-24 16:30:00', '2024-01-24 17:00:00', 'available'),
(289, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-25 08:00:00', '2024-01-25 08:30:00', 'available'),
(290, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-25 08:30:00', '2024-01-25 09:00:00', 'available'),
(291, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-25 09:00:00', '2024-01-25 09:30:00', 'available'),
(292, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-25 09:30:00', '2024-01-25 10:00:00', 'available'),
(293, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-25 10:00:00', '2024-01-25 10:30:00', 'available'),
(294, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-25 10:30:00', '2024-01-25 11:00:00', 'available'),
(295, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-25 11:00:00', '2024-01-25 11:30:00', 'available'),
(296, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-25 11:30:00', '2024-01-25 12:00:00', 'available'),
(297, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-25 13:00:00', '2024-01-25 13:30:00', 'available'),
(298, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-25 13:30:00', '2024-01-25 14:00:00', 'available'),
(299, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-25 14:00:00', '2024-01-25 14:30:00', 'available'),
(300, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-25 14:30:00', '2024-01-25 15:00:00', 'available'),
(301, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-25 15:00:00', '2024-01-25 15:30:00', 'available'),
(302, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-25 15:30:00', '2024-01-25 16:00:00', 'available'),
(303, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-25 16:00:00', '2024-01-25 16:30:00', 'available'),
(304, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-25 16:30:00', '2024-01-25 17:00:00', 'available'),
(305, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-26 08:00:00', '2024-01-26 08:30:00', 'available'),
(306, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-26 08:30:00', '2024-01-26 09:00:00', 'available'),
(307, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-26 09:00:00', '2024-01-26 09:30:00', 'available'),
(308, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-26 09:30:00', '2024-01-26 10:00:00', 'available'),
(309, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-26 10:00:00', '2024-01-26 10:30:00', 'available'),
(310, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-26 10:30:00', '2024-01-26 11:00:00', 'available'),
(311, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-26 11:00:00', '2024-01-26 11:30:00', 'available'),
(312, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-26 11:30:00', '2024-01-26 12:00:00', 'available'),
(313, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-26 13:00:00', '2024-01-26 13:30:00', 'available'),
(314, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-26 13:30:00', '2024-01-26 14:00:00', 'available'),
(315, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-26 14:00:00', '2024-01-26 14:30:00', 'available'),
(316, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-26 14:30:00', '2024-01-26 15:00:00', 'available'),
(317, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-26 15:00:00', '2024-01-26 15:30:00', 'available'),
(318, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-26 15:30:00', '2024-01-26 16:00:00', 'available'),
(319, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-26 16:00:00', '2024-01-26 16:30:00', 'available'),
(320, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-26 16:30:00', '2024-01-26 17:00:00', 'available'),
(321, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-29 08:00:00', '2024-01-29 08:30:00', 'available'),
(322, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-29 08:30:00', '2024-01-29 09:00:00', 'available'),
(323, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-29 09:00:00', '2024-01-29 09:30:00', 'available'),
(324, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-29 09:30:00', '2024-01-29 10:00:00', 'available'),
(325, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-29 10:00:00', '2024-01-29 10:30:00', 'available'),
(326, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-29 10:30:00', '2024-01-29 11:00:00', 'available'),
(327, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-29 11:00:00', '2024-01-29 11:30:00', 'available'),
(328, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-29 11:30:00', '2024-01-29 12:00:00', 'available'),
(329, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-29 13:00:00', '2024-01-29 13:30:00', 'available'),
(330, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-29 13:30:00', '2024-01-29 14:00:00', 'available'),
(331, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-29 14:00:00', '2024-01-29 14:30:00', 'available'),
(332, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-29 14:30:00', '2024-01-29 15:00:00', 'available'),
(333, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-29 15:00:00', '2024-01-29 15:30:00', 'available'),
(334, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-29 15:30:00', '2024-01-29 16:00:00', 'available'),
(335, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-29 16:00:00', '2024-01-29 16:30:00', 'available'),
(336, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-29 16:30:00', '2024-01-29 17:00:00', 'available'),
(337, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-30 08:00:00', '2024-01-30 08:30:00', 'available'),
(338, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-30 08:30:00', '2024-01-30 09:00:00', 'available'),
(339, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-30 09:00:00', '2024-01-30 09:30:00', 'available'),
(340, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-30 09:30:00', '2024-01-30 10:00:00', 'available'),
(341, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-30 10:00:00', '2024-01-30 10:30:00', 'available'),
(342, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-30 10:30:00', '2024-01-30 11:00:00', 'available'),
(343, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-30 11:00:00', '2024-01-30 11:30:00', 'available'),
(344, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-30 11:30:00', '2024-01-30 12:00:00', 'available'),
(345, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-30 13:00:00', '2024-01-30 13:30:00', 'available'),
(346, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-30 13:30:00', '2024-01-30 14:00:00', 'available'),
(347, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-30 14:00:00', '2024-01-30 14:30:00', 'available'),
(348, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-30 14:30:00', '2024-01-30 15:00:00', 'available'),
(349, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-30 15:00:00', '2024-01-30 15:30:00', 'available'),
(350, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-30 15:30:00', '2024-01-30 16:00:00', 'available'),
(351, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-30 16:00:00', '2024-01-30 16:30:00', 'available'),
(352, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-30 16:30:00', '2024-01-30 17:00:00', 'available'),
(353, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-31 08:00:00', '2024-01-31 08:30:00', 'available'),
(354, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-31 08:30:00', '2024-01-31 09:00:00', 'available'),
(355, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-31 09:00:00', '2024-01-31 09:30:00', 'available'),
(356, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-31 09:30:00', '2024-01-31 10:00:00', 'available'),
(357, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-31 10:00:00', '2024-01-31 10:30:00', 'available'),
(358, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-31 10:30:00', '2024-01-31 11:00:00', 'available'),
(359, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-31 11:00:00', '2024-01-31 11:30:00', 'available'),
(360, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-31 11:30:00', '2024-01-31 12:00:00', 'available'),
(361, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-31 13:00:00', '2024-01-31 13:30:00', 'available'),
(362, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-31 13:30:00', '2024-01-31 14:00:00', 'available'),
(363, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-31 14:00:00', '2024-01-31 14:30:00', 'available'),
(364, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-31 14:30:00', '2024-01-31 15:00:00', 'available'),
(365, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-31 15:00:00', '2024-01-31 15:30:00', 'available'),
(366, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-31 15:30:00', '2024-01-31 16:00:00', 'available'),
(367, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-31 16:00:00', '2024-01-31 16:30:00', 'available'),
(368, 'Vehicle Emission', 'emission', 5, 0, '600.00', '500.00', '300.00', '2024-01-31 16:30:00', '2024-01-31 17:00:00', 'available');

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
  `W_Catalytic` tinyint(1) DEFAULT NULL,
  `Report` tinyint(1) DEFAULT NULL,
  `auth_code` int(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_result`
--

INSERT INTO `test_result` (`id`, `booking_id`, `HC`, `CO`, `CO2`, `O2`, `N`, `RPM`, `K_AVE`, `testing_status`, `record_status`, `vehicle_img`, `Tested`, `Uploaded_Image`, `Retest`, `Uploaded`, `Motorcycle`, `Rebuilt`, `Valid`, `W_Catalytic`, `Report`, `auth_code`) VALUES
(2, 3, '0.00', '0.00', '14.00', '2.00', '0.00', '0.00', '0.00', 1, 1, 'assets/img/test_img/1011917966.jpg', 1, 1, NULL, 1, NULL, NULL, 0, NULL, NULL, 8378673),
(4, 2, '0.00', '0.00', '14.00', '3.00', '0.00', '0.00', '0.00', 1, 1, 'assets/img/test_img/311114698_1504206127008775_364900707846708373_n.jpg', 1, 1, NULL, 1, NULL, NULL, 1, NULL, NULL, 1758339);

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
  ADD KEY `fk_car_emission_user_id` (`user_id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `schedule_list`
--
ALTER TABLE `schedule_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=369;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
