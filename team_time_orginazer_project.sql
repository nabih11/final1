-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 11, 2024 at 01:41 PM
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
-- Database: `team time orginazer project`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` int(11) NOT NULL,
  `Interested_content` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `manger`
--

CREATE TABLE `manger` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `Experience` int(11) DEFAULT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `phone`, `age`, `Experience`, `image`) VALUES
(12689182, 'tamer', '054-5575433', 35, 5, 'worker.png'),
(12689212, 'slengo', '054-9876543', 25, 2, 'worker.png'),
(12789123, 'salem', '054-8765432', 21, 1, 'worker.png'),
(78932143, 'waled', '054-7654321', 24, 4, 'worker.png'),
(87654123, 'franko', '054-3456987', 35, 8, 'worker.png'),
(123455432, 'rabea', '052-9876543', 26, 10, 'worker.png'),
(123456789, 'melo', '501234567', 30, 2, 'worker.png'),
(126544567, 'chen', '054-5638983', 28, 12, 'worker.png'),
(456789231, 'nabih', '0545555555', 27, 10, 'worker.png'),
(543871211, 'shadi', '054-1329875', 43, 11, 'worker.png');

-- --------------------------------------------------------

--
-- Table structure for table `products_profiles`
--

CREATE TABLE `products_profiles` (
  `profile_number` int(11) NOT NULL,
  `id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_table`
--

CREATE TABLE `user_table` (
  `username` varchar(255) NOT NULL,
  `pass` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `birtday_user` date DEFAULT NULL,
  `looked` tinyint(1) DEFAULT NULL,
  `login_attempts` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_table`
--

INSERT INTO `user_table` (`username`, `pass`, `email`, `phone`, `first_name`, `last_name`, `birtday_user`, `looked`, `login_attempts`) VALUES
('admin', 'admin', 'grfbgdcx@gfbgvc.com', '0522222222', 'grfvd', 'fgdbvc', '2024-03-06', 1, 0),
('chen11', '333', 'chen.1@hotmail.com', '0522224325', 'chen', 'cohn', '1990-01-01', 1, 0),
('franko', '777', 'franko22.1@hotmail.com', '0549255675', 'franko', 'franko', '1992-07-09', 1, 0),
('melo', '999', 'melo@gmail.com', '054264232', 'melad', 'nekola', '2004-03-05', 1, 0),
('nabih', '111', 'nabih.1@hotmail.com', '0549232325', 'nabih', 'mazzawi', '0000-00-00', 1, 2),
('rabea', 'rabea124$', 'rabea12@gmail.com', '056545623', 'rabea', 'kher', '1997-03-12', 1, 1),
('salem', '444', 'salem12.1@hotmail.com', '0549221122', 'salem', 'slame', '1999-04-10', 1, 0),
('shadi', '222', 'shadi.1@hotmail.com', '0549114325', 'shadi', 'shadi', '1977-04-02', 1, 0),
('slengo', '666', 'slengo99.1@hotmail.com', '0549774155', 'slengo', 'asaf', '2000-09-01', 1, 0),
('tamer', '555', 'tamer.1@hotmail.com', '0555524325', 'tamer', 'kabha', '2002-02-02', 1, 0),
('waled', '888', 'waled1234@hotmail.com', '052424242', 'waled', 'farh', '2024-12-04', 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`phone`);

--
-- Indexes for table `manger`
--
ALTER TABLE `manger`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products_profiles`
--
ALTER TABLE `products_profiles`
  ADD PRIMARY KEY (`profile_number`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `user_table`
--
ALTER TABLE `user_table`
  ADD PRIMARY KEY (`username`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products_profiles`
--
ALTER TABLE `products_profiles`
  ADD CONSTRAINT `products_profiles_ibfk_1` FOREIGN KEY (`id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
