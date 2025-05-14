-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2025 at 06:24 PM
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
-- Database: `eduquest`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_classes`
--

CREATE TABLE `tbl_classes` (
  `class_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `class_name` varchar(20) NOT NULL,
  `section` varchar(50) NOT NULL,
  `description` longtext NOT NULL,
  `class_code` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_classes`
--

INSERT INTO `tbl_classes` (`class_id`, `user_id`, `class_name`, `section`, `description`, `class_code`) VALUES
(1, 2, 'Class', 'Sir Ronnie', 'Devils Advocate\r\nMatalino Matalinaw', NULL),
(2, 2, 'Matasatitg', 'Ron', 'Magusap tayo\r\nhinde ikaw ang gusto ko', NULL),
(3, 2, '. . .m,m.,,m', 'sir sir', 'dfgdfgfgd', NULL),
(4, 2, 'Meth', 'Heisenberg', 'We need to cook we r so cooked', NULL),
(5, 2, 'Meth', 'SBIT-TITE', 'asdjasl;djasd', NULL),
(6, 2, 'asd', 'asd', 'asd', NULL),
(7, 2, 'fasf', 'asfasf', 'asfasf', NULL),
(8, 2, 'asfasf', 'asfasf', 'asfasf', NULL),
(9, 2, 'asf', 'asf', 'asf', NULL),
(11, 12, 'IM101', 'SBIT-2L', 'Always be Matalino, Matalinaw MATA SA TITEG', NULL),
(13, 13, 'qwe', 'qwe', 'wq', NULL),
(14, 13, 'qw', 'qwe', 'qw', NULL),
(15, 13, 'qw', 'qw', 'qw', NULL),
(16, 13, 'qw', 'qw', 'qw', NULL),
(17, 13, 'q', 're', 'w', '8PU3P7'),
(18, 13, 'asf', 'as', 'as', 'F48NHJ'),
(19, 13, 'w', 'w', 'w', '4JTGXJ');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_classes`
--
ALTER TABLE `tbl_classes`
  ADD PRIMARY KEY (`class_id`),
  ADD UNIQUE KEY `class_code` (`class_code`),
  ADD KEY `user_classes` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_classes`
--
ALTER TABLE `tbl_classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_classes`
--
ALTER TABLE `tbl_classes`
  ADD CONSTRAINT `user_classes` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
