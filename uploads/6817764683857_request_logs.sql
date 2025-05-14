-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2025 at 01:11 PM
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
-- AUTO_INCREMENT for table `request_logs`
--
ALTER TABLE `request_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `request_logs`
--
ALTER TABLE `request_logs`
  ADD CONSTRAINT `request_logs_ibfk_1` FOREIGN KEY (`req_id`) REFERENCES `mr_requests` (`req_id`),
  ADD CONSTRAINT `request_logs_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
