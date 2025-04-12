-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 12, 2025 at 01:01 PM
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
-- Database: `attendify`
--

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires`, `created_at`) VALUES
(1, 'admin@gmail.com', '3b9faec98ca5803efaad249117f3bc606a8b0c5c5162f172073af87226958265', 1736096036, '2025-01-05 16:23:56'),
(7, 'llosajohnmichael25@gmail.com', '4ab7a4e1f693713eeebdc4e2c9068cefb0b73b4e910f16be5a7cf7f6dacbb6cc', 1736096883, '2025-01-05 16:38:03');

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `Id` int(10) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `emailAddress` varchar(50) NOT NULL,
  `password` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`Id`, `firstName`, `lastName`, `emailAddress`, `password`) VALUES
(1, 'Admin', '', 'admin@gmail.com', '$2y$10$FIBqWvTOXRMoQOAB2FBz3uUbaCwRYTM1zQreFI6i/7v6Qi8y9R1i6'),
(2, 'John Michael', 'llosa', 'llosajohnmichael25@gmail.com', '$2y$10$zITVd9QSEVs75uzr6NVHeejOSuHEvr0/x9SS7Xp6D.gGhEeGSYLEW'),
(3, 'Edzel', 'Olaer', 'edzelolaer@gmail.com', '$2y$10$/Vlspo/c2duCRzpEAlgREey.7T1MPlhO8rulydkKNag/BySHdg3/q');

-- --------------------------------------------------------

--
-- Table structure for table `tblattendance`
--

CREATE TABLE `tblattendance` (
  `attendanceID` int(50) NOT NULL,
  `professorRegistrationNumber` varchar(100) NOT NULL,
  `course` varchar(100) NOT NULL,
  `attendanceStatus` varchar(100) NOT NULL,
  `dateMarked` date NOT NULL,
  `unit` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblattendance`
--

INSERT INTO `tblattendance` (`attendanceID`, `professorRegistrationNumber`, `course`, `attendanceStatus`, `dateMarked`, `unit`) VALUES
(24, 'TUPM-21-0395', 'BSITY1', '06:51:18 PM\n←', '2025-04-12', 'CS50'),
(25, 'TUPM-21-0395', 'BSITY1', '06:51:51 PM\n←', '2025-04-12', 'CS50');

-- --------------------------------------------------------

--
-- Table structure for table `tblcourse`
--

CREATE TABLE `tblcourse` (
  `Id` int(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `yearlevelID` int(50) NOT NULL,
  `dateCreated` date NOT NULL,
  `courseCode` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcourse`
--

INSERT INTO `tblcourse` (`Id`, `name`, `yearlevelID`, `dateCreated`, `courseCode`) VALUES
(33, 'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY(Y1)', 28, '2024-12-18', 'BSITY1'),
(34, 'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY(Y2)', 29, '2024-12-18', 'BSITY2'),
(35, 'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY(Y3)', 34, '2024-12-18', 'BSITY3'),
(36, 'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY(Y4)', 35, '2024-12-18', 'BSITY4'),
(37, 'BACHELOR OF SCIENCE IN COMPUTER SCIENCE(Y1)', 28, '2024-12-18', 'BSCSY1'),
(38, 'BACHELOR OF SCIENCE IN COMPUTER SCIENCE(Y2)', 29, '2024-12-18', 'BSCSY2'),
(39, 'BACHELOR OF SCIENCE IN COMPUTER SCIENCE(Y3)', 34, '2024-12-18', 'BSCS(Y3)'),
(40, 'BACHELOR OF SCIENCE IN COMPUTER SCIENCE(Y4)', 35, '2024-12-18', 'BSCS(Y4)'),
(42, 'BSCS', 28, '2025-01-14', 'BSCS1');

-- --------------------------------------------------------

--
-- Table structure for table `tbldepthead`
--

CREATE TABLE `tbldepthead` (
  `Id` int(10) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `emailAddress` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phoneNo` varchar(50) NOT NULL,
  `yearlevelCode` varchar(50) NOT NULL,
  `dateCreated` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbldepthead`
--

INSERT INTO `tbldepthead` (`Id`, `firstName`, `lastName`, `emailAddress`, `password`, `phoneNo`, `yearlevelCode`, `dateCreated`) VALUES
(18, 'johnmichael', 'llosa', 'johnmichael_llosa@yahoo.com', '$2y$10$wfqDOlkiObffGgX/7vYi.eVLbzoNwLXHoRbrAHzMyYD/VFXwpoNLq', '09102021642', 'Y4', '2025-04-12');

-- --------------------------------------------------------

--
-- Table structure for table `tblprofessor`
--

CREATE TABLE `tblprofessor` (
  `Id` int(10) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `registrationNumber` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `yearlevel` varchar(10) NOT NULL,
  `courseCode` varchar(20) NOT NULL,
  `professorImage` varchar(300) NOT NULL,
  `dateRegistered` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblprofessor`
--

INSERT INTO `tblprofessor` (`Id`, `firstName`, `lastName`, `registrationNumber`, `email`, `yearlevel`, `courseCode`, `professorImage`, `dateRegistered`) VALUES
(134, 'John Michael', 'Llosa', 'TUPM-21-0395', 'llosajohnmichael25@gmail.com', 'Y1', 'BSITY1', '[\"TUPM-21-0395_image1.png\",\"TUPM-21-0395_image2.png\",\"TUPM-21-0395_image3.png\",\"TUPM-21-0395_image4.png\",\"TUPM-21-0395_image5.png\"]', '2025-01-13'),
(135, 'Lorenzo', 'Sypio', 'TUPM-21-0396', 'lorenzosypio@gmail.com', 'Y4', 'BSCS(Y4)', '[\"TUPM-21-0396_image1.png\",\"TUPM-21-0396_image2.png\",\"TUPM-21-0396_image3.png\",\"TUPM-21-0396_image4.png\",\"TUPM-21-0396_image5.png\"]', '2025-01-14');

-- --------------------------------------------------------

--
-- Table structure for table `tblroom`
--

CREATE TABLE `tblroom` (
  `Id` int(10) NOT NULL,
  `className` varchar(50) NOT NULL,
  `yearlevelCode` varchar(50) NOT NULL,
  `currentStatus` varchar(50) NOT NULL,
  `capacity` int(10) NOT NULL,
  `classification` varchar(50) NOT NULL,
  `dateCreated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblroom`
--

INSERT INTO `tblroom` (`Id`, `className`, `yearlevelCode`, `currentStatus`, `capacity`, `classification`, `dateCreated`) VALUES
(23, '322', 'Y1', 'availlable', 50, 'Normal', '2025-01-06');

-- --------------------------------------------------------

--
-- Table structure for table `tblunit`
--

CREATE TABLE `tblunit` (
  `Id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `unitCode` varchar(50) NOT NULL,
  `courseID` varchar(50) NOT NULL,
  `scheduleDay` varchar(10) NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL,
  `dateCreated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblunit`
--

INSERT INTO `tblunit` (`Id`, `name`, `unitCode`, `courseID`, `scheduleDay`, `startTime`, `endTime`, `dateCreated`) VALUES
(15, 'WEB DEVELOPMENT', 'CS50', '33', 'Monday', '08:30:00', '10:00:00', '2024-12-18'),
(16, 'PYTHON PROGRAMMING', 'CS50', '33', 'Monday', '22:00:00', '12:36:00', '2024-12-18'),
(17, 'WEB DEVELOPMENT (FIRST YEAR)', 'CC101(1011)', '33', 'MONDAY', '07:00:00', '10:00:00', '2024-12-18'),
(19, 'PRORAMMING LANGAGE', 'CS1101', '33', 'Tuesday', '08:30:00', '10:03:00', '2025-01-14');

-- --------------------------------------------------------

--
-- Table structure for table `tblyearlevel`
--

CREATE TABLE `tblyearlevel` (
  `Id` int(10) NOT NULL,
  `yearlevelName` varchar(255) NOT NULL,
  `yearlevelCode` varchar(50) NOT NULL,
  `dateRegistered` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblyearlevel`
--

INSERT INTO `tblyearlevel` (`Id`, `yearlevelName`, `yearlevelCode`, `dateRegistered`) VALUES
(35, 'FOURTH YEAR', 'Y4', '2024-12-18'),
(34, 'THIRD YEAR', 'Y3', '2024-12-18'),
(29, 'SECOND YEAR', 'Y2', '2024-12-18'),
(28, 'FIRST YEAR ', 'Y1', '2024-12-18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblattendance`
--
ALTER TABLE `tblattendance`
  ADD PRIMARY KEY (`attendanceID`);

--
-- Indexes for table `tblcourse`
--
ALTER TABLE `tblcourse`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tbldepthead`
--
ALTER TABLE `tbldepthead`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblprofessor`
--
ALTER TABLE `tblprofessor`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblroom`
--
ALTER TABLE `tblroom`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblunit`
--
ALTER TABLE `tblunit`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblyearlevel`
--
ALTER TABLE `tblyearlevel`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblattendance`
--
ALTER TABLE `tblattendance`
  MODIFY `attendanceID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tblcourse`
--
ALTER TABLE `tblcourse`
  MODIFY `Id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `tbldepthead`
--
ALTER TABLE `tbldepthead`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tblprofessor`
--
ALTER TABLE `tblprofessor`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `tblroom`
--
ALTER TABLE `tblroom`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tblunit`
--
ALTER TABLE `tblunit`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tblyearlevel`
--
ALTER TABLE `tblyearlevel`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
