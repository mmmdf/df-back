-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 23, 2018 at 08:06 PM
-- Server version: 5.6.22-72.0
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tersian_drivefly`
--

-- --------------------------------------------------------

--
-- Table structure for table `consolidator`
--

CREATE TABLE `consolidator` (
  `id` tinyint(4) NOT NULL,
  `acronym` varchar(8) NOT NULL,
  `name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `consolidator`
--

INSERT INTO `consolidator` (`id`, `acronym`, `name`) VALUES
(1, 'DriveFly', 'DriveFly'),
(2, 'FHR', 'FHR'),
(3, 'APH', 'AHP'),
(4, 'SKYP', 'SKYP'),
(6, 'L4P', 'Looking For Parking'),
(7, 'NEW', 'NEW'),
(16, 'CHR', 'CHR'),
(17, 'HD', 'Happy Days'),
(24, 'EX', 'EX'),
(25, 'SPF', 'SPF');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `consolidator`
--
ALTER TABLE `consolidator`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `consolidator`
--
ALTER TABLE `consolidator`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
