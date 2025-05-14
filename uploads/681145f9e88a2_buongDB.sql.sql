-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2025 at 06:41 PM
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
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `campus` varchar(25) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `campus`, `title`, `author`, `year`, `quantity`, `category`) VALUES
(1, 'San Bartolme', 'Star Wars: A new Hope', 'George Clooney', 2002, 5, 'Fiction'),
(2, 'San Bartolme', 'Harry Potter: The Legend of Encantadia', 'Pepito Manaloto', 1995, 14, 'Fiction'),
(3, 'San Bartolme', 'IT: Chapter 69', 'Robert J. Oppenheimer', 1945, 17, 'Mystery'),
(4, 'San Bartolme', 'Dune', 'Frank Herbert', 1965, 4, 'Sci-Fi'),
(5, 'San Bartolme', 'Ender\'s Game', 'Orson Scott Card', 1985, 4, 'Sci-Fi'),
(6, 'San Bartolme', 'Foundation', 'Isaac Asimov', 1951, 3, 'Sci-Fi'),
(7, 'San Bartolme', 'Pride and Prejudice', 'Jane Austen', 1813, 4, 'Romance'),
(8, 'San Francisco', 'Crimino and Educ', 'Filodor Dospordostoevsky', 2025, 1, 'Pyschololomo'),
(9, 'San Francisco', 'title', 'autor', 2001, 4, 'Fiction');

-- --------------------------------------------------------

--
-- Table structure for table `borrowings`
--

CREATE TABLE `borrowings` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `borrow_ref` varchar(50) DEFAULT NULL,
  `borrow_date` date DEFAULT NULL,
  `student_number` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `return_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowings`
--

INSERT INTO `borrowings` (`id`, `book_id`, `borrow_ref`, `borrow_date`, `student_number`, `status`, `return_date`) VALUES
(50, 6, '9E3AE35B', '2025-04-20', 'student123', 'Returned', NULL),
(51, 1, '623F0A22', '2025-04-20', 'student123', 'Returned', NULL),
(52, 2, '645B8218', '2025-04-20', 'student123', 'Returned', NULL),
(53, 2, '0A611910', '2025-04-20', 'student456', 'Returned', NULL),
(54, 1, '620212D3', '2025-04-20', 'student456', 'Returned', NULL),
(55, 2, '77A83F3D', '2025-04-20', 'student456', 'Returned', NULL),
(56, 1, '0EBD5AE3', '2025-04-20', 'student456', 'Returned', NULL),
(57, 1, '5036ECA5', '2025-04-20', 'student456', 'Returned', NULL),
(58, 6, '031058C9', '2025-04-20', 'student456', 'Returned', NULL),
(59, 5, 'B77BB8FC', '2025-04-20', 'student456', 'Returned', NULL),
(60, 6, '9352DAF5', '2025-04-20', 'student123', 'Returned', NULL),
(61, 5, 'DE6F72F7', '2025-04-20', 'student123', 'Returned', NULL),
(62, 5, '767557ED', '2025-04-20', 'student123', 'Returned', NULL),
(63, 1, '76E6A646', '2025-04-20', 'student123', 'Returned', NULL),
(64, 1, '91EEEA61', '2025-04-20', 'student123', 'Returned', NULL),
(65, 1, 'F0CD4487', '2025-04-20', 'student123', 'Returned', NULL),
(66, 2, 'EE60CCD8', '2025-04-20', 'student123', 'Returned', NULL),
(67, 2, '51C3EA1B', '2025-04-20', 'student123', 'Returned', NULL),
(68, 1, '0C438161', '2025-04-20', 'student123', 'Returned', NULL),
(69, 1, 'E3E1CDD1', '2025-04-20', 'student123', 'Returned', NULL),
(70, 2, '2BFC53B5', '2025-04-20', 'student123', 'Pending', NULL),
(71, 2, '3962BC80', '2025-04-20', 'student123', 'Pending', NULL),
(72, 1, '62F02149', '2025-04-22', 'student123', 'Returned', NULL),
(73, 1, '2B83B8CD', '2025-04-22', 'student123', 'Pending', NULL),
(74, 3, 'CA2E7B38', '2025-04-23', 'student123', 'Returned', NULL),
(75, 4, '50B94DA3', '2025-04-23', 'student123', 'Pending', NULL),
(76, 9, 'BF09BA62', '2025-04-23', 'student123', 'Returned', NULL),
(77, 9, '5DDB5762', '2025-04-23', 'student123', 'Returned', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `borrowings_temp`
--

CREATE TABLE `borrowings_temp` (
  `id` int(11) NOT NULL DEFAULT 0,
  `book_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `student_number` varchar(20) DEFAULT NULL,
  `borrow_ref` varchar(100) NOT NULL,
  `borrow_date` datetime NOT NULL,
  `status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowings_temp`
--

INSERT INTO `borrowings_temp` (`id`, `book_id`, `student_id`, `student_number`, `borrow_ref`, `borrow_date`, `status`) VALUES
(42, 1, 2, 'student123', '4D58F9C7', '2025-04-20 05:20:15', 'Returned'),
(43, 2, 2, 'student123', 'ACD84D5E', '2025-04-20 05:22:44', 'Returned'),
(44, 2, 3, 'student456', '794BDF24', '2025-04-20 05:23:29', 'Returned'),
(45, 1, 2, 'student123', '86E1391B', '2025-04-20 05:26:25', 'Returned'),
(46, 2, 2, 'student123', 'DEEC21E5', '2025-04-20 05:26:27', 'Returned'),
(47, 2, 3, 'student456', 'CFECCD45', '2025-04-20 05:26:39', 'Returned'),
(48, 1, 2, 'student123', '4FE3B103', '2025-04-20 05:31:10', 'Returned'),
(49, 2, 2, 'student123', 'A92B298B', '2025-04-20 05:31:13', 'Returned'),
(50, 2, 3, 'student456', '98FCA98C', '2025-04-20 05:31:24', 'Returned'),
(51, 1, 2, 'student123', '019CA03E', '2025-04-20 05:41:11', 'Returned'),
(52, 2, 2, 'student123', '01DA5836', '2025-04-20 05:41:14', 'Returned'),
(53, 2, 3, 'student456', '24309EC8', '2025-04-20 05:41:22', 'Returned'),
(54, 1, 3, 'student456', 'A16168D6', '2025-04-20 05:42:03', 'Returned');

-- --------------------------------------------------------

--
-- Table structure for table `meeting_rooms`
--

CREATE TABLE `meeting_rooms` (
  `room_id` int(11) NOT NULL,
  `Status` enum('Available','Unavailable','In Session','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meeting_rooms`
--

INSERT INTO `meeting_rooms` (`room_id`, `Status`) VALUES
(1, 'In Session'),
(2, 'In Session'),
(3, 'In Session'),
(4, 'Available');

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
(1, 1, 'student123', '2025-04-22 00:00:00', 'I want segsy time', 'Approved'),
(2, 2, 'student123', '2025-04-22 20:14:56', 'matotolog po', 'Rejected'),
(3, 1, 'student123', '2025-04-22 20:16:30', 'matotolog po ko', 'Approved'),
(4, 1, 'student123', '2025-04-22 20:26:42', 'tolog po gosto ko matolog', 'Rejected'),
(5, 1, 'student123', '2025-04-22 21:00:11', 'mstotolog po', 'Approved'),
(6, 1, 'student123', '2025-04-23 14:01:09', 'magbabahay bahayan\r\n', 'Approved'),
(7, 2, 'student123', '2025-04-23 14:17:26', 'asdasd', 'Approved'),
(8, 3, 'student123', '2025-04-23 15:03:36', 'ghjygjghj', 'Approved'),
(9, 4, 'student123', '2025-04-23 15:38:07', 'asdfasd', 'Rejected'),
(10, 4, 'student123', '2025-04-23 15:38:21', 'asdasdasd', 'Rejected');

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
(1, 1, 'student123', '2025-04-30', '13:00:00', '14:00:00', 'SEGGGGGGGGGGGGSSSSSSSSSSSSSS', '2025-04-23 17:18:21', 'Pending'),
(2, 1, 'student123', '2025-04-30', '14:30:00', '15:30:00', 'SEGGS', '2025-04-23 17:28:10', 'Pending');

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
(18, 1, 'Approved', 1, '2025-04-23 10:59:33'),
(19, 2, 'Rejected', 1, '2025-04-23 11:28:35'),
(20, 1, 'Approved', 1, '2025-04-23 11:29:02'),
(21, 4, 'Rejected', 1, '2025-04-23 11:29:03'),
(22, 5, 'Approved', 1, '2025-04-23 11:29:04'),
(23, 3, 'Approved', 1, '2025-04-23 11:29:05'),
(24, 6, 'Approved', 1, '2025-04-23 12:03:02'),
(25, 7, 'Approved', 1, '2025-04-23 12:17:39'),
(26, 8, 'Approved', 1, '2025-04-23 13:03:45'),
(27, 9, 'Rejected', 1, '2025-04-23 13:38:12'),
(28, 10, 'Rejected', 1, '2025-04-23 13:38:33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `student_number` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `student_number`, `password`, `role`) VALUES
(1, 'admin123', '$2y$10$THpAVFDpPVXY5GdrMprCkuh2844G/LTEHO82eLTuxE3TCY068azq2', 'admin'),
(2, 'student123', '$2y$10$bLFarHxLFUpYlpbFNKpobuxf/3oUY3bMzh2G6OPZORLQfdVH7wQO6', 'student'),
(3, 'student456', '$2y$10$DVdFY6zt1AnMgKHqN76G.Od6qf3Rb2paitJbRLIrw5KdjKqEP1Zce', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrowings`
--
ALTER TABLE `borrowings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meeting_rooms`
--
ALTER TABLE `meeting_rooms`
  ADD PRIMARY KEY (`room_id`);

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_number` (`student_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `borrowings`
--
ALTER TABLE `borrowings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `meeting_rooms`
--
ALTER TABLE `meeting_rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `mr_requests`
--
ALTER TABLE `mr_requests`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `mr_reservations`
--
ALTER TABLE `mr_reservations`
  MODIFY `res_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `request_logs`
--
ALTER TABLE `request_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
