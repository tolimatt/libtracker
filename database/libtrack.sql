-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 17, 2025 at 05:06 PM
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `verification_code` int(6) DEFAULT NULL,
  `verification_code_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `password`, `email`, `created_at`, `verification_code`, `verification_code_expiry`) VALUES
(2, 'admin', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'esperanzagabrieljose@gmail.com', '2025-02-01 12:49:31', 630831, '2025-03-05 11:18:08');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `entry_time` varchar(50) NOT NULL,
  `day` varchar(10) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `entry_time`, `day`, `department`) VALUES
(227, '03-2324-012345', 'March 8, 2025, 9:52 pm', 'Saturday', 'CAHS');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL COMMENT 'Unique ID for each book.',
  `title` varchar(255) NOT NULL COMMENT 'Title of the book.',
  `author` varchar(255) NOT NULL COMMENT 'Author of the book.',
  `description` varchar(255) NOT NULL,
  `book_code` varchar(99) NOT NULL COMMENT 'ISBN number of the book',
  `copies_available` int(11) NOT NULL COMMENT 'Number of available copies.',
  `total_copies` int(11) NOT NULL COMMENT 'Total number of copies.',
  `department` varchar(255) NOT NULL COMMENT 'Department where it belong',
  `category` varchar(255) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `pdf_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Date book was added.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `author`, `description`, `book_code`, `copies_available`, `total_copies`, `department`, `category`, `image_url`, `pdf_url`, `created_at`) VALUES
(68, 'Abnormal Psychology', 'Susan Nolen-hoeksema', '', '0123', 70, 99, 'College of Allied Health and Sciences (CAHS)', 'Psychology', 'http://192.168.1.248/LibTrack/libtracker/book_images/485084511_1698884137381870_6855990703119091364_n.jpg', 'http://192.168.1.248/LibTrack/libtracker/book_pdf/tarp 2x3ft (36 x 24 in).pdf', '2025-03-05 09:45:45'),
(77, 'Case  Analyses For Abnormal Psychology', 'Randall E. Osborne, Joan Esterline Lafuze And David V. Perkins', '', '124', 77, 90, 'College of Allied Health and Sciences (CAHS)', 'Technology', 'http://192.168.1.248/LibTrack/libtracker/book_images/485084249_1287540275650612_276555213351401423_n.jpg', 'http://192.168.1.248/LibTrack/libtracker/book_pdf/tarp 2x3ft (36 x 24 in).pdf', '2025-03-07 14:30:09'),
(79, 'okp', 'po', '', '457', 1, 12, 'College of Allied Health and Sciences (CAHS)', 'Technology', 'http://192.168.1.248/LibTrack/libtracker/book_images/484985735_1299979247725978_6353110145376817480_n.jpg', 'http://192.168.1.248/LibTrack/libtracker/book_pdf/tarp 2x3ft (36 x 24 in).pdf', '2025-03-14 10:57:34'),
(80, 'guiok', 'asda', '', '65', 11, 23, 'College of Allied Health and Sciences (CAHS)', 'Technology', 'http://192.168.1.248/LibTrack/libtracker/book_images/485008406_839984534955441_727090214655102047_n.jpg', 'http://192.168.1.248/LibTrack/libtracker/book_pdf/tarp 2x3ft (36 x 24 in).pdf', '2025-03-14 10:57:34');

-- --------------------------------------------------------

--
-- Table structure for table `borrow`
--

CREATE TABLE `borrow` (
  `transaction_id` int(11) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `book_code` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `borrowed_date` varchar(255) NOT NULL,
  `due_date` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `server_time` varchar(255) NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrow`
--

INSERT INTO `borrow` (`transaction_id`, `student_id`, `book_code`, `title`, `borrowed_date`, `due_date`, `status`, `server_time`) VALUES
(57, '03-2324-032803', '124', 'Case  Analyses For Abnormal Psychology', '17 Mar 2025, 18:56:21', '24 Mar 2025, 18:56:21', 'Returned', '2025-03-17 18:56:42'),
(58, '03-2324-032803', '65', 'guiok', '17 Mar 2025, 19:06:33', '24 Mar 2025, 19:06:33', 'Returned', '2025-03-17 19:06:55'),
(59, '03-2324-032803', '0123', 'Abnormal Psychology', '17 Mar 2025, 19:20:14', '24 Mar 2025, 19:20:14', 'Returned', '2025-03-17 19:20:36');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `staff_idNum` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `staff_idNum`, `first_name`, `last_name`, `position`, `email`, `department`, `status`) VALUES
(1, 0, 'John', 'Doe', 'Professor', 'john.doe@example.com', 'CITE', 1),
(2, 0, 'Jane', 'Smith', 'Assistant Professor', 'jane.smith@example.com', 'CMA', 1),
(3, 0, 'Alice', 'Johnson', 'Lecturer', 'alice.johnson@example.com', 'CEA', 1),
(4, 0, 'Bob', 'Brown', 'Instructor', 'bob.brown@example.com', 'CAS', 1);

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
  `program` varchar(255) NOT NULL,
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

INSERT INTO `user` (`user_id`, `student_id`, `first_name`, `last_name`, `password`, `program`, `phinmaed_email`, `year_level`, `department`, `created_at`, `contact_number`, `status`) VALUES
(7, '03-2324-032903', 'Kirsteen', 'Orduna', 'Gabriel12345', '', 'kisa.orduna.up@phinmaed.com', 'Sophomore (2nd Year)', 'CAHS', '2025-03-05 07:14:56', 9959824437, 1),
(8, '03-2324-012345', 'Joshua', 'Velasco', 'Joshua123456', '', 'joshuavelasco@gmail.com', 'Sophomore (2nd Year)', 'CAHS', '2025-03-05 07:14:51', 9123456789, 1),
(9, '2425-049858', 'Elijah', 'Vinluan', 'Vinluan12345', '', 'elca.vinluan.up@phinmaed.com', 'Sophomore (2nd Year)', 'CITE', '2025-03-12 14:09:17', 9123456789, 1),
(11, '03-2324-123456', 'Juan', 'Dela Cruz', '000000000000', '', 'juan.delacruz.up@phinmaed.com', 'Senior (4th Year)', 'CITE', '2025-03-12 14:09:41', 9951234567, 1),
(12, '03-2122-123456', 'Dayarana', 'Vinluan', 'user123456789', '', 'sample@gmail.com', 'Junior (3rd Year)', 'CCJE', '2025-03-12 14:09:35', 9123556487, 1),
(13, '03-2324-036622', 'Nicka', 'Ok', 'password12345', '', 'ok@gmail.com', 'Sophomore (2nd Year)', 'CCJE', '2025-03-13 13:40:56', 9619968015, 1),
(14, '03-2425-123', 'Joshua', 'Dacasin', '@Dmin1234567890', '', 'joshua.dacasin@gmail.com', 'Freshmen (1st Year)', 'CITE', '2025-03-13 13:40:53', 9123453789, 1),
(16, '03-2324-032803', 'Gabriel Jose ', 'Esperanza ', 'Kirsteen12345', '', 'gajo.esperanza.up@phinmaed.com', 'Sophomore (2nd Year)', 'College of Information Technology Education (CITE)', '2025-03-13 14:55:50', 9705095844, 1),
(17, '64694355---595', 'Jdjsbcu', 'Hisbsuds', 'pppppppp', 'BS Accounting Information System', '.up@phinmaed.com', 'Sophomore (2nd Year)', 'College of Management and Accountancy (CMA)', '2025-03-14 01:51:19', 643798, 1),
(18, '359538--6434', 'Jdbd', 'Gdud', '00000000', 'BA Political Science', '.up@phinmaed.com', 'Sophomore (2nd Year)', 'College of Education and Liberal Arts (CELA)', '2025-03-14 11:04:12', 986595, 1),
(19, '03-2324-03', 'Joshua', 'Ud', '00000000', 'BA Political Science', '.up@phinmaed.com', 'Freshmen (1st Year)', 'CITE', '2025-03-17 15:34:18', 9, 1);

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
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `book_code` (`book_code`);

--
-- Indexes for table `borrow`
--
ALTER TABLE `borrow`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `book_code` (`book_code`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=228;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for each book.', AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `borrow`
--
ALTER TABLE `borrow`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for each user.', AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `user` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `borrow`
--
ALTER TABLE `borrow`
  ADD CONSTRAINT `books` FOREIGN KEY (`book_code`) REFERENCES `books` (`book_code`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user` FOREIGN KEY (`student_id`) REFERENCES `user` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
