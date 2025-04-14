-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2024 at 06:27 PM
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
(1, 'Admin', '', 'admin@gmail.com', '$2y$10$FIBqWvTOXRMoQOAB2FBz3uUbaCwRYTM1zQreFI6i/7v6Qi8y9R1i6');

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
(40, 'BACHELOR OF SCIENCE IN COMPUTER SCIENCE(Y4)', 35, '2024-12-18', 'BSCS(Y4)');

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
(15, 'mark', 'lila', 'mark@gmail.com', '$2y$10$/st06w2mh/4adxGE9yCxROHkqHp6SzRARGhfCIg95zC3cxqbmkpaW', '07123456789', 'CIT', '2024-04-07');

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
(131, 'JOHN MICHAEL ', 'LLOSA', '0001', 'LLOSAJOHNMICHAEL25@GMAIL.COM', 'Y1', 'BSITY1', '[\"0001_image1.png\",\"0001_image2.png\",\"0001_image3.png\",\"0001_image4.png\",\"0001_image5.png\"]', '2024-12-18');

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
(17, 'ADVANCE TECH(322)', 'Y1', 'availlable', 30, '322', '2024-12-18'),
(18, 'WEB DEVELOPMENT', 'Y1', 'availlable', 30, '322', '2024-12-18');

-- --------------------------------------------------------

--
-- Table structure for table `tblunit`
--

CREATE TABLE `tblunit` (
  `Id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `unitCode` varchar(50) NOT NULL,
  `courseID` varchar(50) NOT NULL,
  `dateCreated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblunit`
--

INSERT INTO `tblunit` (`Id`, `name`, `unitCode`, `courseID`, `dateCreated`) VALUES
(15, 'WEB DEVELOPMENT (FIRST YEAR)', 'CS1112 (FIRST YEAR)', '37', '2024-12-18'),
(16, 'PROGRAMMING 1 (FIRST YEAR)', 'CS1919 (FIRST YEAR)', '37', '2024-12-18'),
(17, 'WEB DEVELOPMENT (FIRST YEAR)', 'CC101(1011)', '33', '2024-12-18'),
(18, 'DESIGN AND ALGO (2ND YEAR)', 'CS8881 (Y2)', '34', '2024-12-18');

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
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblattendance`
--
ALTER TABLE `tblattendance`
  MODIFY `attendanceID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=511;

--
-- AUTO_INCREMENT for table `tblcourse`
--
ALTER TABLE `tblcourse`
  MODIFY `Id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `tbldepthead`
--
ALTER TABLE `tbldepthead`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tblprofessor`
--
ALTER TABLE `tblprofessor`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `tblroom`
--
ALTER TABLE `tblroom`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tblunit`
--
ALTER TABLE `tblunit`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tblyearlevel`
--
ALTER TABLE `tblyearlevel`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
