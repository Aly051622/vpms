-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2024 at 02:52 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `parking`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblguard`
--

CREATE TABLE `tblguard` (
  `ID` int(11) NOT NULL,
  `GuardName` varchar(50) NOT NULL,
  `UserName` varchar(50) NOT NULL,
  `MobileNumber` varchar(15) DEFAULT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `GuardRegdate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblguard`
--

INSERT INTO `tblguard` (`ID`, `GuardName`, `UserName`, `MobileNumber`, `Email`, `Password`, `GuardRegdate`) VALUES
(1, 'GuardLogin', 'inguard', '09915597891', 'inguard@gmail.com', '57a6ca8df7a2147ac17ea5972ec98b4d191d23da47d7136c4bbfc7310fb6a189', '2024-11-09 03:08:17'),
(2, 'GuardLogout', 'outguard', '09913494357', 'outguard@gmail.com', 'e17c63bdc86873b39586b78cb5c4b6006e3ea44e55762631d063acf830b61a5a', '2024-11-09 03:08:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblguard`
--
ALTER TABLE `tblguard`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `UserName` (`UserName`),
  ADD UNIQUE KEY `Email` (`Email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
