-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2025 at 07:27 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dtt`
--

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `crated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `name`, `location_id`, `user_id`, `crated_at`) VALUES
(10, 'Camping', 15, 1, '2025-06-06 14:27:48'),
(11, 'Playing', 15, 1, '2025-06-06 15:42:17'),
(13, 'Hiking', 15, 1, '2025-06-06 21:59:26');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `city` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `country_code` int(11) NOT NULL,
  `phone_no` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `city`, `address`, `zip_code`, `country_code`, `phone_no`, `created_at`) VALUES
(6, 'Amsterdam', 'test address 1', '54000', 333, '5656565', '2025-06-05 16:01:12'),
(10, 'Utrecht', 'test address 2', '54000', 333, '5656565', '2025-06-05 16:12:27'),
(15, 'Deventor', 'test address 3', '54000', 333, '5656565', '2025-06-05 16:19:38'),
(19, 'Germany', 'test address 4', '54000', 333, '5656565', '2025-06-05 17:36:28'),
(25, 'Canada', 'test address 5', 'sasa', 123, '5656565', '2025-06-07 00:25:53');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`, `created_at`) VALUES
(23, 'Sports', '2025-06-06 21:15:01'),
(24, 'kitchen', '2025-06-06 21:16:00'),
(25, 'plates', '2025-06-06 21:17:20'),
(26, 'cups', '2025-06-06 21:19:54'),
(27, 'Glass', '2025-06-06 22:00:23'),
(28, 'Spoons', '2025-06-06 22:00:42'),
(29, 'Catle', '2025-06-07 11:37:50'),
(30, 'dish washer', '2025-06-07 11:39:47'),
(31, 'stove', '2025-06-07 15:53:06'),
(32, 'Water', '2025-06-07 15:58:02');

-- --------------------------------------------------------

--
-- Table structure for table `tag_facility`
--

CREATE TABLE `tag_facility` (
  `id` int(11) NOT NULL,
  `facility_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tag_facility`
--

INSERT INTO `tag_facility` (`id`, `facility_id`, `tag_id`, `created_at`) VALUES
(20, 11, 25, '2025-06-06 21:20:51'),
(21, 11, 26, '2025-06-06 21:20:51'),
(22, 11, 23, '2025-06-06 21:20:51'),
(91, 10, 26, '2025-06-06 21:45:36'),
(92, 10, 23, '2025-06-06 21:45:36'),
(125, 10, 31, '2025-06-07 15:53:06'),
(129, 13, 32, '2025-06-07 15:58:02'),
(130, 13, 26, '2025-06-07 15:58:02'),
(131, 13, 23, '2025-06-07 15:58:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL,
  `user_name` text NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `token` text DEFAULT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `user_name`, `password`, `token`, `created_at`) VALUES
(1, 'Zee', 'abc@abc.com', 'zahidislam08', 'e10adc3949ba59abbe56e057f20f883e', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NDkzMTI1NDYsImV4cCI6MTc0OTM5ODk0Niwic3ViIjoxfQ.X7KTFGvDS7kJdI4q7YqZos130OWAs6EqFCXnEAneC_k', '2025-06-06'),
(2, 'Xee', 'aa@appa.com', 'zahdislam09', 'e10adc3949ba59abbe56e057f20f883e', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NDkzMTcyMTQsImV4cCI6MTc0OTQwMzYxNCwic3ViIjoyfQ.do8FOoG2ZhqrClmT2bjlW3yGhghK005LHj7cnB8fJOE', '2025-06-06'),
(3, 'Yee', 'aa@apddpa.com', 'zahidislam10', 'e10adc3949ba59abbe56e057f20f883e', NULL, '2025-06-06'),
(4, 'Dee', 'zee@apddpa.com', 'zahidislam11', 'e10adc3949ba59abbe56e057f20f883e', NULL, '2025-06-06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facilities_ibfk_1` (`location_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `tag_facility`
--
ALTER TABLE `tag_facility`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facility_id` (`facility_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `user_name` (`user_name`) USING HASH,
  ADD UNIQUE KEY `token` (`token`) USING HASH;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tag_facility`
--
ALTER TABLE `tag_facility`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `facilities`
--
ALTER TABLE `facilities`
  ADD CONSTRAINT `facilities_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `facilities_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tag_facility`
--
ALTER TABLE `tag_facility`
  ADD CONSTRAINT `tag_facility_ibfk_1` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `tag_facility_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
