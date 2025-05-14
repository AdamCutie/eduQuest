-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2025 at 12:12 AM
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
-- Table structure for table `tbl_acts`
--

CREATE TABLE `tbl_acts` (
  `act_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` longtext NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `posted_at` datetime NOT NULL DEFAULT current_timestamp(),
  `score` int(3) NOT NULL,
  `deadline` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_announcements`
--

CREATE TABLE `tbl_announcements` (
  `ann_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` longtext NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `posted_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_announcements`
--

INSERT INTO `tbl_announcements` (`ann_id`, `class_id`, `title`, `description`, `file_name`, `file_path`, `file_type`, `file_size`, `posted_at`) VALUES
(1, 0, 'Tite', 'iwanandie', '', '', '', 0, '2025-04-30 02:43:14'),
(2, 0, 'Tite', 'iwanandie', '', '', '', 0, '2025-04-30 02:45:46'),
(3, 0, 'asdas', 'asdasdasd', 'mr_reservations.sql', 'uploads/681128ae1c927_mr_reservations.sql', 'text/plain', 1968, '2025-04-30 03:29:50'),
(4, 3, 'sad', 'asd', 'buongDB.sql.sql', 'uploads/6811468069638_buongDB.sql.sql', 'text/plain', 13696, '2025-04-30 05:37:04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_assignments`
--

CREATE TABLE `tbl_assignments` (
  `ass_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` longtext NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `posted_at` datetime NOT NULL DEFAULT current_timestamp(),
  `score` int(3) NOT NULL,
  `deadline` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_assignments`
--

INSERT INTO `tbl_assignments` (`ass_id`, `class_id`, `title`, `description`, `file_name`, `file_path`, `file_type`, `file_size`, `posted_at`, `score`, `deadline`) VALUES
(1, 0, 'asdasd', 'asdasdas', '', '', '', 0, '2025-04-30 03:59:36', 100, '2025-04-30 03:59:00'),
(2, 0, 'bsdfhgbsdf', 'jkashdkjashdkjashd', 'library (2).sql', 'uploads/68112fce4e40c_library (2).sql', 'text/plain', 4887, '2025-04-30 04:00:14', 0, '2025-04-30 04:00:00'),
(3, 3, 'jkfjknsdffsdjkn', 'sdfklnklnsdfklnsdf', 'request_logs.sql', 'uploads/6811477ad0864_request_logs.sql', 'text/plain', 2830, '2025-04-30 05:41:14', 234, '2025-04-30 05:41:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_classes`
--

CREATE TABLE `tbl_classes` (
  `class_id` int(11) NOT NULL,
  `class_name` varchar(20) NOT NULL,
  `section` varchar(50) NOT NULL,
  `description` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_classes`
--

INSERT INTO `tbl_classes` (`class_id`, `class_name`, `section`, `description`) VALUES
(1, 'Class', 'Sir Ronnie', 'Devils Advocate\r\nMatalino Matalinaw'),
(2, 'Matasatitg', 'Ron', 'Magusap tayo\r\nhinde ikaw ang gusto ko'),
(3, '. . .m,m.,,m', 'sir sir', 'dfgdfgfgd'),
(4, 'Meth', 'Heisenberg', 'We need to cook we r so cooked');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `user_type` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `Name`, `Email`, `Password`, `user_type`) VALUES
(1, 'sad', 'asd@gmail.com', '$2y$10$P.oQLPEAXgaGHUoTzeSSXei/yDj406LQHZG2pLEzqnsp/OBQwS4ju', 'student'),
(2, 'andrew', 'asdd@gmail.com', '$2y$10$2hfmLBNxDNzt3Mp/96oxYOpPdJL3I2rM6gMmARXRfCrcQkFbLtA7S', 'teacher'),
(6, 'admin', 'admin@gmail.com', '$2y$10$yf3Y61VdNCkg87o7mXP4OOZXqVMWqva9VdxOnl/ilj/tzZGUeGTjC', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_acts`
--
ALTER TABLE `tbl_acts`
  ADD PRIMARY KEY (`act_id`);

--
-- Indexes for table `tbl_announcements`
--
ALTER TABLE `tbl_announcements`
  ADD PRIMARY KEY (`ann_id`);

--
-- Indexes for table `tbl_assignments`
--
ALTER TABLE `tbl_assignments`
  ADD PRIMARY KEY (`ass_id`);

--
-- Indexes for table `tbl_classes`
--
ALTER TABLE `tbl_classes`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_acts`
--
ALTER TABLE `tbl_acts`
  MODIFY `act_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_announcements`
--
ALTER TABLE `tbl_announcements`
  MODIFY `ann_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_assignments`
--
ALTER TABLE `tbl_assignments`
  MODIFY `ass_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_classes`
--
ALTER TABLE `tbl_classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
