-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2019 at 02:29 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_ussd`
--
CREATE DATABASE IF NOT EXISTS `project_ussd` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `project_ussd`;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customerID` int(11) NOT NULL,
  `firstName` varchar(25) NOT NULL,
  `lastName` varchar(25) NOT NULL,
  `phoneNumber` varchar(10) NOT NULL,
  `password` varchar(80) NOT NULL,
  `balance` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customerID`, `firstName`, `lastName`, `phoneNumber`, `password`, `balance`) VALUES
(1, 'chris', 'luvanda', '0759424576', '$2y$10$99iZT7jzxxKMsWNuwkiEE.5DDiTt8OgfSkv89dWBifKHmFy2un5JC', 5129),
(2, 'irene', 'kenedy', '0759424577', '$2y$10$b1ZG3.UhEMG2yDxPLhalg.FGoOGaOicVXvkHwHYSJt.TdugculJWy', 29200),
(3, 'Jacob', 'Cross', '0759111222', '$2y$10$8sLHuEhg8/Z4sHJuw3WVfe.PgfSg227zVyWk.MUYh7sVoGBQ.1ngy', 18000),
(4, 'christopher', 'luvanda', '0759424578', '$2y$10$ETxRWhWe1qcXsNDYSXI6auDZQQ./JKuZ1rBG7cCYU5pHzjbHD4bDS', 14394),
(5, 'davis', 'luvanda', '0759424579', '$2y$10$r26lySUbJypTbk..1pqvxOCKIXJbCWA9FSXuvmw/wfGH5Agdow5M.', 17943);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customerID`),
  ADD UNIQUE KEY `phoneNumber` (`phoneNumber`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
