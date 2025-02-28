-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 28, 2025 at 01:02 AM
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
-- Database: `libtrack`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `password`, `email`, `created_at`) VALUES
(2, 'admin', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', '', '2025-02-01 12:49:31');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `entry_time` datetime NOT NULL,
  `exit_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `entry_time`, `exit_time`) VALUES
(135, '03-2324-032903', '2025-02-22 09:02:26', NULL),
(136, '03-2324-032903', '2025-02-22 09:02:33', NULL),
(137, '03-2324-032903', '2025-02-22 16:03:18', NULL),
(138, '03-2324-032903', '2025-02-22 16:03:21', NULL),
(139, '03-2324-032903', '2025-02-22 16:03:31', NULL),
(142, '03-2324-032903', '2025-02-22 16:03:36', NULL),
(143, '03-2324-032803', '2025-02-25 14:48:32', NULL),
(144, '03-2324-032803', '2025-02-25 14:48:37', NULL),
(146, '03-2324-012345', '2025-02-26 14:51:09', NULL),
(148, '03-2324-012345', '2025-02-26 14:51:09', NULL),
(149, '03-2324-012345', '2025-02-26 14:51:16', NULL),
(151, '03-2324-012345', '2025-02-26 14:51:21', NULL),
(152, '03-2324-012345', '2025-02-26 14:51:38', NULL),
(153, '03-2324-012345', '2025-02-26 14:51:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL COMMENT 'Unique ID for each book.',
  `title` varchar(255) NOT NULL COMMENT 'Title of the book.',
  `author` varchar(255) NOT NULL COMMENT 'Author of the book.',
  `isbn` varchar(99) NOT NULL COMMENT 'ISBN number of the book',
  `copies_available` int(11) NOT NULL COMMENT 'Number of available copies.',
  `total_copies` int(11) NOT NULL COMMENT 'Total number of copies.',
  `department` varchar(255) NOT NULL COMMENT 'Department where it belong',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Date book was added.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `author`, `isbn`, `copies_available`, `total_copies`, `department`, `created_at`) VALUES
(37, 'dayabase the book', 'Dayanara Lopez', '1234', 1, 1, 'CITE', '2025-02-20 01:21:32'),
(47, 'Zoology, 12th Edition ', 'N/a', '0123', 99, 99, 'CAHS', '2025-02-25 07:17:09'),
(48, 'Zoology, 12th Edition ', 'N/a', '0123', 99, 99, 'CAHS', '2025-02-25 10:34:03');

-- --------------------------------------------------------

--
-- Table structure for table `borrowed_books`
--

CREATE TABLE `borrowed_books` (
  `transaction_id` int(11) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `borrowed_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `return_status` enum('Returned','Overdue','Pending') DEFAULT 'Pending',
  `renewal_status` enum('Yes','No') DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowed_books`
--

INSERT INTO `borrowed_books` (`transaction_id`, `book_id`, `user_id`, `borrowed_date`, `due_date`, `return_date`, `return_status`, `renewal_status`) VALUES
(3, 37, 6, '2025-02-19', '2025-02-28', '2025-02-22', 'Returned', '');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL COMMENT 'Unique ID for each user.',
  `student_id` varchar(255) NOT NULL COMMENT 'Student ID "03-2324-xxxxxx"',
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phinmaed_email` varchar(255) NOT NULL COMMENT 'PHINMA EMAIL',
  `year_level` varchar(255) NOT NULL,
  `department` varchar(100) NOT NULL COMMENT 'Course',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Registration date',
  `contact_number` bigint(15) NOT NULL COMMENT 'contact number',
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `student_id`, `first_name`, `last_name`, `password`, `phinmaed_email`, `year_level`, `department`, `created_at`, `contact_number`, `status`) VALUES
(6, '03-2324-032803', 'Gabriel Jose', 'Esperanza', 'Kirsteen12345', 'gajo.esperanza.up@phinmaed.com', 'Sophomore (2nd Year)', 'BS Information Technology', '2025-02-22 07:46:21', 9705095844, 1),
(7, '03-2324-032903', 'Kirsteen', 'Orduna', 'Gabriel12345', 'kisa.orduna.up@phinmaed.com', 'Sophomore (2nd Year)', 'BS Nursing', '2025-02-22 07:32:51', 9959824437, 1),
(8, '03-2324-012345', 'Joshua', 'Velasco', 'Joshua123456', 'joshuavelasco@gmail.com', 'Sophomore (2nd Year)', 'BS Nursing', '2025-02-26 06:49:23', 9123456789, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for each book.', AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for each user.', AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `user` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD CONSTRAINT `borrowed_books_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrowed_books_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
