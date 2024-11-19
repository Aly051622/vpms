-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2024 at 08:32 AM
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
-- Database: `parkingz`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `username`, `comment`, `created_at`) VALUES
(1, 'sdfsdf', 'sfsdf', '2024-10-15 07:06:18'),
(2, 'asdasd', 'asdasd', '2024-10-15 07:09:37'),
(3, 'hi', 'hi', '2024-10-15 07:23:06'),
(4, 'sdfsdf', 'sddfsdf', '2024-10-15 07:26:39'),
(5, 'Anonymous', 'sdfsfsdf', '2024-10-15 07:29:47'),
(6, 'Anonymous', 'sdfsfsdf', '2024-10-15 07:30:07'),
(7, 'sd', 'sd', '2024-10-15 07:34:40'),
(8, 'Anonymous', 'sdfsdf', '2024-10-15 07:36:00'),
(9, 'hi', 'hi', '2024-10-15 07:36:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2024 at 08:32 AM
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
-- Database: `parkingz`
--

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT 'Anonymous',
  `feedback` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `username`, `feedback`, `created_at`) VALUES
(1, 'Anonymous', 'sdsd', '2024-10-15 07:48:37'),
(2, 'Anonymous', 'sd', '2024-10-15 07:50:26'),
(3, 'Anonymous', 'sdsd', '2024-10-15 07:54:00'),
(4, 'Anonymous', 'asasd', '2024-10-15 07:55:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2024 at 08:33 AM
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
-- Database: `parkingz`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `isSupport` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `username`, `message`, `isSupport`, `created_at`) VALUES
(1, 'User', 'i wanna have a compalaint', 0, '2024-10-15 08:01:31'),
(2, 'Admin', 'yes', 1, '2024-10-15 08:04:39'),
(3, 'Admin', 'do you encounter any problems?', 1, '2024-10-15 08:06:34'),
(4, 'User', 'i do not have any qr code', 0, '2024-10-15 08:08:35'),
(5, 'User', 'hi', 0, '2024-10-17 01:19:58'),
(6, 'Admin', 'i have a complaint', 1, '2024-10-17 01:20:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2024 at 08:33 AM
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
-- Database: `parkingz`
--

-- --------------------------------------------------------

--
-- Table structure for table `table_attendance`
--

CREATE TABLE `table_attendance` (
  `ID` int(11) NOT NULL,
  `STUDENTID` varchar(250) NOT NULL,
  `TIMEIN` varchar(250) NOT NULL,
  `TIMEOUT` varchar(250) NOT NULL,
  `LOGDATE` varchar(250) NOT NULL,
  `AM` varchar(250) NOT NULL,
  `PM` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `table_attendance`
--

INSERT INTO `table_attendance` (`ID`, `STUDENTID`, `TIMEIN`, `TIMEOUT`, `LOGDATE`, `AM`, `PM`) VALUES
(70, 'ABC123456789', '2021-04-09 11:58:29', '', '', '', ''),
(81, 'Rhynia Impas', '2024-01-02 13:32:46', '', '', '', ''),
(82, 'Whyyy', '2024-01-02 13:33:11', '', '', '', ''),
(83, 'Hakdog', '2024-01-02 13:34:11', '', '', '', ''),
(84, 'Gana please', '2024-01-02 13:38:35', '', '', '', ''),
(85, 'Hangakk', '2024-01-02 13:51:21', '', '', '', ''),
(86, 'Oks nani?', '2024-01-02 13:58:26', '', '', '', ''),
(87, 'Dunno', '2024-01-02 14:33:33', '', '', '', ''),
(88, 'Rainya', '2024-01-07 21:23:52', '', '', '', ''),
(89, 'Rainya', '2024-01-07 21:23:57', '', '', '', ''),
(90, 'Gana na kuno oi', '2024-01-08 21:15:47', '', '', '', ''),
(91, 'Gana naba ni?', '2024-01-08 21:17:30', '', '', '', ''),
(92, 'Mao najud ni?', '2024-01-08 21:19:20', '', '', '', ''),
(93, 'Mao najud ni?', '2024-01-08 21:44:12', '', '', '', ''),
(94, 'Hmmm suspicious', '2024-01-08 22:06:17', '', '', '', ''),
(95, 'Hmmm suspicious', '2024-01-08 22:06:24', '', '', '', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2024 at 08:33 AM
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
-- Database: `parkingz`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `ID` int(10) NOT NULL,
  `AdminName` varchar(120) DEFAULT NULL,
  `UserName` varchar(120) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `Password` varchar(120) DEFAULT NULL,
  `AdminRegdate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`ID`, `AdminName`, `UserName`, `MobileNumber`, `Email`, `Password`, `AdminRegdate`) VALUES
(1, 'Admin', 'admin', 7898799798, 'tester1@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2019-07-05 05:38:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2024 at 08:34 AM
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
-- Database: `parkingz`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblcategory`
--

CREATE TABLE `tblcategory` (
  `ID` int(10) NOT NULL,
  `VehicleCat` varchar(120) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblcategory`
--

INSERT INTO `tblcategory` (`ID`, `VehicleCat`, `CreationDate`) VALUES
(1, 'Four Wheeler Vehicle', '2022-05-01 11:06:50'),
(2, 'Two Wheeler Vehicle', '2022-03-02 11:07:09'),
(4, 'Bicycles', '2022-05-03 11:31:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblcategory`
--
ALTER TABLE `tblcategory`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `VehicleCat` (`VehicleCat`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblcategory`
--
ALTER TABLE `tblcategory`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2024 at 08:34 AM
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
-- Database: `parkingz`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblparkingslots`
--

CREATE TABLE `tblparkingslots` (
  `SlotNumber` varchar(50) NOT NULL,
  `Status` varchar(50) NOT NULL DEFAULT 'Vacant'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblparkingslots`
--

INSERT INTO `tblparkingslots` (`SlotNumber`, `Status`) VALUES
('1', 'vacant'),
('1000', 'vacant'),
('10921321', 'vacant'),
('11', 'vacant'),
('110', 'vacant'),
('111', 'vacant'),
('23', 'vacant'),
('25', 'vacant'),
('3', 'vacant'),
('32232323', 'vacant'),
('4', 'occupied'),
('4354564565', 'vacant'),
('999', 'vacant');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblparkingslots`
--
ALTER TABLE `tblparkingslots`
  ADD UNIQUE KEY `unique_slot_number` (`SlotNumber`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2024 at 08:34 AM
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
-- Database: `parkingz`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblregusers`
--

CREATE TABLE `tblregusers` (
  `ID` int(5) NOT NULL,
  `FirstName` varchar(250) DEFAULT NULL,
  `LastName` varchar(250) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Email` varchar(250) DEFAULT NULL,
  `Password` varchar(250) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `user_type` varchar(255) NOT NULL,
  `place` varchar(255) NOT NULL,
  `LicenseNumber` varchar(50) DEFAULT NULL,
  `registration_status` varchar(25) NOT NULL,
  `or_image` varchar(255) DEFAULT NULL,
  `cr_image` varchar(255) DEFAULT NULL,
  `nv_image` varchar(255) DEFAULT NULL,
  `verification_status` enum('pending','verified') DEFAULT 'pending',
  `profile_pictures` varchar(25) DEFAULT NULL,
  `validity` tinyint(4) NOT NULL DEFAULT -2,
  `status` enum('active','inactive','pending') DEFAULT 'inactive'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblregusers`
--

INSERT INTO `tblregusers` (`ID`, `FirstName`, `LastName`, `MobileNumber`, `Email`, `Password`, `RegDate`, `user_type`, `place`, `LicenseNumber`, `registration_status`, `or_image`, `cr_image`, `nv_image`, `verification_status`, `profile_pictures`, `validity`, `status`) VALUES
(3, '', '', 5345345345, 'c@gmail.com', '$2y$10$K6ozQUqOnvJ6kuuDHPo4au1JGYC8KETpMJjRWk/yWwRHj6d50G0m6', '2024-09-08 12:54:27', 'faculty', 'Beside Kadasig Gym', '2324242342', 'for_registration', 'dsadasds.PNG', 'eee.PNG', 'images.jpg', 'pending', 'dsadasds.PNG', 0, 'inactive'),
(4, '', '', 3648273642, 'r@gmail.com', '$2y$10$.YXjs6FFvBMdpdotVZXkGOO5trHBC86Q5UgT7tzRLLoTmpDzY98R2', '2024-09-08 13:31:51', 'faculty', 'Beside Kadasig Gym', '3423423423', 'for_registration', 'images.jpg', 'hey.jpg', '461086229_837698654844722_3954539777229713562_n.jpg', 'pending', 'taho.jpg', 1, 'inactive'),
(5, '', '', 1231231231, 'fg@gmail.com', '$2y$10$GMpRVPdeou9h/ZdG1fStMehR/aaE3G7yFzHVEmB978MTSR2kacFRu', '2024-09-08 13:41:23', 'faculty', 'Beside Kadasig Gym', '1314314234', 'for_registration', NULL, NULL, 'nihao.jpg', 'pending', NULL, 0, 'inactive'),
(6, '', '', 6237846234, 'rt@gmail.com', '$2y$10$X5YS7GcU6ww4etqfIO1T/u0njYLHDiIBbSZUZzSpFRXd/NA5dDzHe', '2024-09-08 14:59:26', 'visitor', 'Front', '4234242424', 'for_registration', 'nihao.jpg', '451381076_839638931592843_7655874659988860484_n.jpg', '450789575_364281893361273_8567366221750570159_n.jpg', 'pending', NULL, 1, 'inactive'),
(34, 'gffdfgd', 'dgfegre', 4565778798, 'bnn@gmail.com', '$2y$10$3N0QsdK7DzH2zUOZFWRJkO6jSnERENnU2oQXbmrWUZTvP.HLZK9iS', '2024-09-12 06:52:38', 'faculty', 'Beside Kadasig Gym', '4564565767', '', NULL, NULL, NULL, 'pending', NULL, 0, 'inactive'),
(40, 'Hsjajah', 'Fagahaj', 9991395287, 'jh@gmail.com', '$2y$10$0TXjAEOQ6T1i7AfOBTQ2VOHeQQOdnfqcNUDChK0Ut0fhZcihdyxle', '2024-09-19 06:11:02', 'visitor', 'Front', '6474748484', '', NULL, NULL, NULL, 'pending', NULL, 0, 'inactive'),
(41, 'Reign', 'Imps', 9915597891, 'rhyniaimpas@gmail.com', '$2y$10$bXt2eIMsDWTnSutG414w6.c2cJ3YiaCDkaeOXD0.QDOhvVuSADcKO', '2024-09-19 06:11:31', 'faculty', 'Beside Kadasig Gym', '7282822728', '', NULL, NULL, NULL, 'pending', NULL, 0, 'inactive'),
(42, 'Judel', 'Yu', 999310192, 'judel@yahoo.com', '$2y$10$PR0.6wASxU11Qa23Ee3YGe2Ju96ARaewS4sPoJ5.5eirs/HolIhM6', '2024-09-19 06:19:14', 'student', 'Beside the CME Building', '123', '', NULL, NULL, NULL, 'pending', NULL, 0, 'inactive'),
(43, 'sdfsdf', 'sdfsdf', 3454656756, 'we@gmail.com', '$2y$10$OBNjns2c3GgbXHVCv1jIAeP16l/z0RueoLaaD.dyZEUANQmQYT0hO', '2024-10-21 03:19:02', 'faculty', 'Beside Kadasig Gym', '4364565676', '', NULL, NULL, NULL, 'pending', NULL, -2, 'inactive'),
(55, 'asda', 'asd', 3435454645, 'ysonmolde03@gmail.com', '$2y$10$PfGZ6cQUuhEgnjZM7G.aseq0bRnrA8fA2.Tt5cmSBah/IjoSRMure', '2024-10-21 05:41:11', 'student', 'Beside the CME Building', '2343454545', '', NULL, NULL, NULL, 'pending', NULL, -2, 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblregusers`
--
ALTER TABLE `tblregusers`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `MobileNumber` (`MobileNumber`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblregusers`
--
ALTER TABLE `tblregusers`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2024 at 08:35 AM
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
-- Database: `parkingz`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblvehicle`
--

CREATE TABLE `tblvehicle` (
  `ID` int(10) NOT NULL,
  `ParkingNumber` varchar(120) DEFAULT NULL,
  `VehicleCategory` varchar(120) NOT NULL,
  `VehicleCompanyname` varchar(120) DEFAULT NULL,
  `RegistrationNumber` varchar(120) DEFAULT NULL,
  `OwnerName` varchar(120) DEFAULT NULL,
  `OwnerContactNumber` bigint(10) DEFAULT NULL,
  `InTime` timestamp NULL DEFAULT current_timestamp(),
  `OutTime` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `ParkingCharge` varchar(120) NOT NULL,
  `Remark` mediumtext NOT NULL,
  `Status` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblvehicle`
--

INSERT INTO `tblvehicle` (`ID`, `ParkingNumber`, `VehicleCategory`, `VehicleCompanyname`, `RegistrationNumber`, `OwnerName`, `OwnerContactNumber`, `InTime`, `OutTime`, `ParkingCharge`, `Remark`, `Status`) VALUES
(0, '396982660', 'Four Wheeler Vehicle', 'sfd', '6456456456466456', 'dfgtdg', 3453645645, '2024-10-20 06:41:15', '2024-10-20 06:44:24', '', '', 'Out'),
(0, '915203976', 'Two Wheeler Vehicle', 'stert', '4564564545646464', 'sd', 3453645645, '2024-10-20 06:45:07', '2024-10-20 07:22:33', '', '', 'Out'),
(0, '314455543', 'Four Wheeler Vehicle', 'asda', 'asd3453453453463', 'sdgsgsgreye', 5444354364, '2024-10-20 07:06:54', '2024-10-20 07:22:33', '', '', 'Out'),
(0, '565439032', 'Four Wheeler Vehicle', 'sdf', '3453453453463463', 'HOOYY', 2534534563, '2024-10-20 07:12:18', '2024-10-20 07:22:33', '', '', 'Out'),
(0, '510021627', 'Four Wheeler Vehicle', 'sdfsdffseg', '34553443fgsgfy43', 'HIIIIIIIIIIIIII', 8425627592, '2024-10-20 07:20:06', '2024-10-20 07:22:33', '', '', 'Out'),
(0, '447290052', 'Four Wheeler Vehicle', 'sddsfdsdgs', 'sdfjrgew78o6r328', 'SAMPLE NI BAIII', 8723423428, '2024-10-20 07:23:46', '2024-10-20 07:26:48', '', '', 'Out'),
(0, '566500894', 'Four Wheeler Vehicle', 'hgi', '6234578yeufheyby', 'USER NI', 3648273642, '2024-10-20 07:30:23', '2024-10-20 07:37:07', '', '', 'Out'),
(0, '893378851', 'Four Wheeler Vehicle', 'dthyeetyt', '434656456464564d', 'BALIK NASAD NI ', 3648273642, '2024-10-20 07:31:05', '2024-10-20 07:37:07', '', '', 'Out'),
(0, '897295397', 'Four Wheeler Vehicle', 'sdfsd', 'xfgdfe5654645rtg', 'sdfsdfs', 3536456575, '2024-10-20 13:56:31', NULL, '', '', ''),
(0, '578604716', 'Four Wheeler Vehicle', 'sdfswei', 'hgsdfyg345863405', 'HUY KADLAWN NA', 3648273642, '2024-10-20 18:19:25', NULL, '', '', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2024 at 08:35 AM
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
-- Database: `parkingz`
--

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `id` int(11) NOT NULL,
  `filename` varchar(25) NOT NULL,
  `file_size` int(11) NOT NULL CHECK (`file_size` > 0),
  `file_type` varchar(50) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `email` varchar(250) DEFAULT NULL,
  `validity` int(11) DEFAULT 0,
  `expiration_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uploads`
--

INSERT INTO `uploads` (`id`, `filename`, `file_size`, `file_type`, `uploaded_at`, `status`, `email`, `validity`, `expiration_date`) VALUES
(39, '461259101_304842384865619', 14812, 'image/jpeg', '2024-10-20 16:36:57', 'approved', 'r@gmail.com', 2, '2027-10-22'),
(40, 'sdfsdfsdfsdgsfgjyuujy.PNG', 652, 'image/png', '2024-10-20 16:41:52', 'approved', 'rt@gmail.com', 0, '2024-10-21'),
(41, 'fsdfsdfsdfsdf.PNG', 636, 'image/png', '2024-10-20 17:34:59', 'approved', 'rt@gmail.com', 0, '2024-11-20'),
(43, 'sgdsdfdfsdad.PNG', 684, 'image/png', '2024-10-20 17:54:55', 'approved', 'r@gmail.com', 2, '2030-12-01'),
(47, 'hiiiiiiiiiiiiii.PNG', 615, 'image/png', '2024-10-21 03:33:44', 'approved', 'r@gmail.com', 1, '2025-11-11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `filename` (`filename`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
