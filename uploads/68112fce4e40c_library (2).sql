-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2025 at 03:38 PM
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
-- Database: `library`
--

-- --------------------------------------------------------

--
-- Table structure for table `mr_requests`
--

CREATE TABLE `mr_requests` (
  `req_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `student_number` varchar(20) NOT NULL,
  `date_requested` datetime NOT NULL,
  `reason` longtext NOT NULL,
  `status` enum('Pending','Approved','Rejected','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mr_requests`
--

INSERT INTO `mr_requests` (`req_id`, `room_id`, `student_number`, `date_requested`, `reason`, `status`) VALUES
(1, 1, 'student123', '2025-04-22 00:00:00', 'I want segsy time', 'Pending'),
(2, 2, 'student123', '2025-04-22 20:14:56', 'matotolog po', 'Pending'),
(3, 1, 'student123', '2025-04-22 20:16:30', 'matotolog po ko', 'Pending'),
(4, 1, 'student123', '2025-04-22 20:26:42', 'tolog po gosto ko matolog', 'Pending'),
(5, 1, 'student123', '2025-04-22 21:00:11', 'mstotolog po', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `mr_reservations`
--

CREATE TABLE `mr_reservations` (
  `res_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `student_number` varchar(20) NOT NULL,
  `date_reserved` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `reason` longtext NOT NULL,
  `date_of_reservation` datetime NOT NULL,
  `status` enum('Pending','Approved','Rejected','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mr_reservations`
--

INSERT INTO `mr_reservations` (`res_id`, `room_id`, `student_number`, `date_reserved`, `start_time`, `end_time`, `reason`, `date_of_reservation`, `status`) VALUES
(1, 1, 'student123', '2025-04-30', '13:00:00', '14:00:00', 'SEGGGGGGGGGGGGSSSSSSSSSSSSSS', '2025-04-23 17:18:21', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `request_logs`
--

CREATE TABLE `request_logs` (
  `log_id` int(11) NOT NULL,
  `req_id` int(11) NOT NULL,
  `action` enum('Approved','Rejected') NOT NULL,
  `admin_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request_logs`
--

INSERT INTO `request_logs` (`log_id`, `req_id`, `action`, `admin_id`, `timestamp`) VALUES
(1, 1, 'Approved', 1, '2025-04-23 10:52:28'),
(2, 1, 'Approved', 1, '2025-04-23 10:52:30'),
(3, 1, 'Rejected', 1, '2025-04-23 10:52:32'),
(4, 1, 'Approved', 1, '2025-04-23 10:52:33'),
(5, 1, 'Rejected', 1, '2025-04-23 10:52:33'),
(6, 1, 'Approved', 1, '2025-04-23 10:52:33'),
(7, 1, 'Rejected', 1, '2025-04-23 10:52:34'),
(8, 1, 'Approved', 1, '2025-04-23 10:52:34'),
(9, 1, 'Rejected', 1, '2025-04-23 10:52:35'),
(10, 1, 'Approved', 1, '2025-04-23 10:52:35'),
(11, 1, 'Rejected', 1, '2025-04-23 10:52:36'),
(12, 1, 'Approved', 1, '2025-04-23 10:52:36'),
(13, 1, 'Rejected', 1, '2025-04-23 10:52:36'),
(14, 1, 'Rejected', 1, '2025-04-23 10:52:50'),
(15, 1, 'Approved', 1, '2025-04-23 10:52:51'),
(16, 1, 'Approved', 1, '2025-04-23 10:55:05'),
(17, 2, 'Rejected', 1, '2025-04-23 10:55:06'),
(18, 1, 'Approved', 1, '2025-04-23 10:59:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mr_requests`
--
ALTER TABLE `mr_requests`
  ADD PRIMARY KEY (`req_id`);

--
-- Indexes for table `mr_reservations`
--
ALTER TABLE `mr_reservations`
  ADD PRIMARY KEY (`res_id`);

--
-- Indexes for table `request_logs`
--
ALTER TABLE `request_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `req_id` (`req_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mr_requests`
--
ALTER TABLE `mr_requests`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `mr_reservations`
--
ALTER TABLE `mr_reservations`
  MODIFY `res_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `request_logs`
--
ALTER TABLE `request_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
