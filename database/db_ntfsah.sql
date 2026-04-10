-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2024 at 09:34 PM
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
-- Database: `db_ntfsah`
--

-- --------------------------------------------------------

--
-- Table structure for table `adviser_list`
--

CREATE TABLE `adviser_list` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `adviser_id` varchar(100) NOT NULL,
  `firstname` text NOT NULL,
  `middlename` text NOT NULL,
  `lastname` text NOT NULL,
  `department_id` int(30) NOT NULL DEFAULT 0,
  `college_id` int(30) NOT NULL DEFAULT 0,
  `curriculum_id` int(30) NOT NULL DEFAULT 0,
  `position_id` int(30) NOT NULL DEFAULT 0,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `gender` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `avatar` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archive_list`
--

CREATE TABLE `archive_list` (
  `id` int(30) NOT NULL,
  `archive_code` varchar(100) NOT NULL,
  `curriculum_id` int(30) NOT NULL,
  `department_id` int(30) NOT NULL DEFAULT 0,
  `year` year(4) NOT NULL,
  `title` text NOT NULL,
  `abstract` text NOT NULL,
  `keywords` text NOT NULL,
  `members` text NOT NULL,
  `document_path` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `student_id` int(30) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `archive_list`
--

INSERT INTO `archive_list` (`id`, `archive_code`, `curriculum_id`, `year`, `title`, `abstract`, `keywords`, `members`, `document_path`, `status`, `student_id`, `date_created`, `date_updated`) VALUES
(6, '2024120003', 13, '2024', 'Sample', '&lt;p&gt;sample&lt;/p&gt;', '<p>sample</p>', '&lt;ol&gt;&lt;li&gt;John Smith&lt;/li&gt;&lt;/ol&gt;', 'uploads/pdf/archive-6.pdf?v=1733106141', 0, 5, '2024-12-01 18:22:21', '2024-12-01 18:22:21'),
(7, '2024120004', 13, '2024', 'Sample Title', '&lt;p&gt;sample title&lt;/p&gt;', '<p>sample</p>', '&lt;ol&gt;&lt;li&gt;John Smith&lt;/li&gt;&lt;/ol&gt;', 'uploads/pdf/archive-7.pdf?v=1733106155', 0, 5, '2024-12-01 18:22:35', '2024-12-01 18:22:35'),
(9, '2024120001', 13, '2024', 'Sample Title: Advanced Education', '&lt;p&gt;sample&lt;/p&gt;', '<p>sample</p>', '&lt;ol&gt;&lt;li&gt;John Smith&lt;/li&gt;&lt;/ol&gt;', 'uploads/pdf/archive-9.pdf?v=1733106626', 0, 5, '2024-12-01 18:30:26', '2024-12-01 18:30:26');

-- --------------------------------------------------------

--
-- Table structure for table `college_list`
--

CREATE TABLE `college_list` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `college_list`
--

INSERT INTO `college_list` (`id`, `name`, `description`, `status`, `date_created`, `date_updated`) VALUES
(1, 'CAS', 'College of Arts and Sciences', 1, '2024-11-30 11:23:09', NULL),
(2, 'CBM', 'College of Business and Management', 1, '2024-11-30 11:24:21', NULL),
(3, 'CET', 'College of Engineering Technology', 1, '2024-11-30 11:24:40', NULL),
(4, 'CITE', 'College of Information Technology Education', 1, '2024-11-30 11:25:00', NULL),
(5, 'CTE', 'College of Technology Education', 1, '2024-11-30 11:25:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `curriculum_list`
--

CREATE TABLE `curriculum_list` (
  `id` int(30) NOT NULL,
  `college_id` int(30) NOT NULL,
  `department_id` int(30) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `curriculum_list`
--

INSERT INTO `curriculum_list` (`id`, `college_id`, `department_id`, `name`, `description`, `status`, `date_created`, `date_updated`) VALUES
(10, 2, 8, 'BSBA-FM', 'Bachelor of Science major in Financial Management', 1, '2024-11-30 11:32:58', '2024-11-30 13:14:28'),
(11, 4, 10, 'BSCS', 'Bachelor of Science in Computer Science', 1, '2024-11-30 11:33:19', '2024-11-30 13:14:44'),
(12, 3, 9, 'BSCE', 'Bachelor of Science in Civil Engineering', 1, '2024-11-30 11:33:40', '2024-11-30 13:15:00'),
(13, 1, 7, 'BA-EL', 'Bachelor of Arts major in English Language', 1, '2024-11-30 11:46:34', '2024-11-30 13:15:10'),
(14, 2, 8, 'BSBA-MM', 'Bachelor of Science in Business Administration major in Marketing Management', 1, '2024-11-30 13:10:52', NULL),
(15, 1, 13, 'BS-Math', 'Bachelor of Science in Mathematics', 1, '2024-12-01 18:02:58', NULL),
(16, 2, 14, 'BS-HM', 'Bachelor of Science in Hospitality Management', 1, '2024-12-01 18:03:44', NULL),
(17, 5, 11, 'BSED-Science', 'Bachelor of Science in Education major in Science', 1, '2024-12-01 18:04:51', NULL),
(18, 1, 7, 'BA-Fil', 'Batsilyer ng Sining sa Filipino', 1, '2024-12-01 18:05:34', NULL),
(19, 1, 12, 'BA-Econ', 'Bachelor of Arts major in Economics', 1, '2024-12-01 18:06:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `discipline_list`
--

CREATE TABLE `discipline_list` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `department_list`
--

CREATE TABLE `department_list` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `college_id` int(30) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department_list`
--

INSERT INTO `department_list` (`id`, `name`, `description`, `status`, `college_id`, `date_created`, `date_updated`) VALUES
(7, 'DOL', 'Department of Languages', 1, 1, '2024-11-30 11:28:48', NULL),
(8, 'DBM', 'Department of Business and Management', 1, 2, '2024-11-30 11:29:54', NULL),
(9, 'DET', 'Department of Engineering and Technology', 1, 3, '2024-11-30 11:30:28', NULL),
(10, 'DCS', 'Department of Computer Science', 1, 4, '2024-11-30 11:30:45', NULL),
(11, 'DGTT', 'Department of General Teacher Training', 1, 5, '2024-11-30 11:31:23', NULL),
(12, 'DSS', 'Department of Social Sciences', 1, 1, '2024-11-30 11:31:43', NULL),
(13, 'DMNS', 'Department of Mathematics and Natural Sciences', 1, 1, '2024-11-30 11:32:05', NULL),
(14, 'DHM', 'Department of Hospitality Management', 1, 2, '2024-12-01 18:00:41', NULL),
(15, 'DPA', 'Department of Public Administration', 1, 2, '2024-12-01 18:01:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_list`
--

CREATE TABLE `student_list` (
  `id` int(30) NOT NULL,
  `firstname` text NOT NULL,
  `middlename` text NOT NULL,
  `lastname` text NOT NULL,
  `college_id` int(30) NOT NULL,
  `department_id` int(30) NOT NULL,
  `curriculum_id` int(30) NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `gender` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `avatar` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_list`
--

INSERT INTO `student_list` (`id`, `firstname`, `middlename`, `lastname`, `college_id`, `department_id`, `curriculum_id`, `email`, `password`, `gender`, `status`, `avatar`, `date_created`, `date_updated`) VALUES
(4, 'John', 'De Jesus', 'Dwight', 2, 8, 10, 'jalexander@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Male', 1, '', '2024-11-30 11:50:42', '2024-11-30 11:52:29'),
(5, 'Jake', 'Lewis', 'Smith', 1, 7, 13, 'jsmith@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Male', 1, '', '2024-11-30 13:28:56', '2024-11-30 13:29:22'),
(6, 'Robin', '', 'Revilla', 3, 9, 12, 'rrevilla@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Male', 0, '', '2024-11-30 18:59:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `system_info`
--

CREATE TABLE `system_info` (
  `id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_info`
--

INSERT INTO `system_info` (`id`, `meta_field`, `meta_value`) VALUES
(1, 'name', 'NEMSU Thesis and Feasibility Study Archiving Hub'),
(6, 'short_name', 'NEMSU - TFSAH'),
(11, 'logo', 'uploads/NEMSU-LOGO.png'),
(13, 'user_avatar', 'uploads/user_avatar.jpg'),
(14, 'cover', 'uploads/nemsu-bldg.jpg'),
(15, 'content', 'Array'),
(16, 'email', 'bscs4@nemsu.edu.ph'),
(17, 'contact', '09854698789'),
(18, 'from_time', '11:00'),
(19, 'to_time', '21:30'),
(20, 'address', 'Brgy. Rosario, Tandag City, Surigao del Sur');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(50) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `middlename` text DEFAULT NULL,
  `lastname` varchar(250) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `college_id` int(30) NOT NULL DEFAULT 0,
  `department_id` int(30) NOT NULL DEFAULT 0,
  `status` int(1) NOT NULL DEFAULT 1 COMMENT '0=not verified, 1 = verified',
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `middlename`, `lastname`, `username`, `password`, `avatar`, `last_login`, `type`, `status`, `date_added`, `date_updated`) VALUES
(1, 'Adminstrator', NULL, 'Admin', 'admin', '0192023a7bbd73250516f069df18b500', 'uploads/student-1.png?v=1639202560', NULL, 1, 1, '2021-01-20 14:02:37', '2021-12-11 14:02:40'),
(3, 'Jackelyn', NULL, 'Ponciano', 'jponciano', '81dc9bdb52d04dc20036dbd8313ed055', NULL, NULL, 2, 1, '2024-11-30 18:57:50', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `archive_list`
--
ALTER TABLE `archive_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `curriculum_id` (`curriculum_id`,`student_id`);

--
-- Indexes for table `college_list`
--
ALTER TABLE `college_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `curriculum_list`
--
ALTER TABLE `curriculum_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `college_id` (`college_id`);

--
-- Indexes for table `department_list`
--
ALTER TABLE `department_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `college_id` (`college_id`);

--
-- Indexes for table `student_list`
--
ALTER TABLE `student_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`) USING HASH,
  ADD KEY `department_id` (`department_id`),
  ADD KEY `curriculum_id` (`curriculum_id`),
  ADD KEY `college_id` (`college_id`);

--
-- Indexes for table `system_info`
--
ALTER TABLE `system_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `archive_list`
--
ALTER TABLE `archive_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `college_list`
--
ALTER TABLE `college_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `curriculum_list`
--
ALTER TABLE `curriculum_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `department_list`
--
ALTER TABLE `department_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `student_list`
--
ALTER TABLE `student_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `system_info`
--
ALTER TABLE `system_info`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `curriculum_list`
--
ALTER TABLE `curriculum_list`
  ADD CONSTRAINT `curriculum_list_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_list`
--
ALTER TABLE `student_list`
  ADD CONSTRAINT `student_list_ibfk_1` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculum_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_list_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `department_list` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
