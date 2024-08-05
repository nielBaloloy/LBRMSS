-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 26, 2024 at 05:08 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lbrmss_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `baptism`
--

CREATE TABLE `baptism` (
  `BID` varchar(255) DEFAULT NULL,
  `First_Name` varchar(255) DEFAULT NULL,
  `Middle_Name` varchar(255) DEFAULT NULL,
  `Last_Name` varchar(255) DEFAULT NULL,
  `Suffix` varchar(255) DEFAULT NULL,
  `Gender` varchar(255) DEFAULT NULL,
  `Birth_Date` varchar(255) DEFAULT NULL,
  `Birth_Place` varchar(255) DEFAULT NULL,
  `Legitimacy` varchar(255) DEFAULT NULL,
  `Father_Name` varchar(255) DEFAULT NULL,
  `Mother_Name` varchar(255) DEFAULT NULL,
  `EventProgress` varchar(255) DEFAULT NULL,
  `Status` varchar(255) DEFAULT NULL,
  `ContactNumber` varchar(255) DEFAULT NULL,
  `Contact_Person` varchar(255) DEFAULT NULL,
  `EventScheduleID` varchar(255) DEFAULT NULL,
  `Region` varchar(255) DEFAULT NULL,
  `Province` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `Barangay` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `confirmation`
--

CREATE TABLE `confirmation` (
  `CID` varchar(255) DEFAULT NULL,
  `First_Name` varchar(255) DEFAULT NULL,
  `Middle_Name` varchar(255) DEFAULT NULL,
  `Last_Name` varchar(255) DEFAULT NULL,
  `Suffix` varchar(255) DEFAULT NULL,
  `Gender` varchar(255) DEFAULT NULL,
  `Birth_Date` varchar(255) DEFAULT NULL,
  `Birth_Place` varchar(255) DEFAULT NULL,
  `Legitimacy` varchar(255) DEFAULT NULL,
  `Nationality` varchar(255) DEFAULT NULL,
  `Father_Name` varchar(255) DEFAULT NULL,
  `Mother_Name` varchar(255) DEFAULT NULL,
  `EventProgress` varchar(255) DEFAULT NULL,
  `Status` varchar(255) DEFAULT NULL,
  `ContactNumber` varchar(255) DEFAULT NULL,
  `Contact_Person` varchar(255) DEFAULT NULL,
  `EventScheduleID` varchar(255) DEFAULT NULL,
  `Region` varchar(255) DEFAULT NULL,
  `Province` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `Barangay` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `eventstable`
--

CREATE TABLE `eventstable` (
  `E_ID` varchar(255) DEFAULT NULL,
  `EventServiceID` varchar(255) DEFAULT NULL,
  `Service` varchar(50) NOT NULL,
  `Client` varchar(255) DEFAULT NULL,
  `Others` varchar(255) DEFAULT NULL,
  `TypeofMass` varchar(255) DEFAULT NULL,
  `Type` varchar(255) DEFAULT NULL,
  `TimeTo` varchar(255) DEFAULT NULL,
  `TimeFrom` varchar(255) DEFAULT NULL,
  `Date` varchar(255) DEFAULT NULL,
  `Venue` varchar(255) DEFAULT NULL,
  `Duration` varchar(255) DEFAULT NULL,
  `Days` varchar(255) DEFAULT NULL,
  `Venue_type` varchar(255) DEFAULT NULL,
  `Assigned_Priest` varchar(255) DEFAULT NULL,
  `Contact_Number` varchar(255) DEFAULT NULL,
  `CertificateFor` varchar(255) DEFAULT NULL,
  `EventProgress` varchar(255) DEFAULT NULL,
  `RequirementStatus` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eventstable`
--

INSERT INTO `eventstable` (`E_ID`, `EventServiceID`, `Service`, `Client`, `Others`, `TypeofMass`, `Type`, `TimeTo`, `TimeFrom`, `Date`, `Venue`, `Duration`, `Days`, `Venue_type`, `Assigned_Priest`, `Contact_Number`, `CertificateFor`, `EventProgress`, `RequirementStatus`) VALUES
(NULL, '89874', 'Marriage', 'Jay Jay Barcellano', NULL, NULL, 'Special', '10:00', '09:00', '2025-07-24', 'St Raphael Church', '60', '1', 'Church', 'Jay Jay', '9873823434', NULL, 'Pending', 'Complete'),
(NULL, '89874', 'DSDS', 'Jay Jay Barcellano', NULL, NULL, 'Seminar', '09:00', '10:01', '2024-08-14', 'DSD', '61', '1', 'Church', NULL, NULL, NULL, 'Pending', 'Complete');

-- --------------------------------------------------------

--
-- Table structure for table `marriagetb`
--

CREATE TABLE `marriagetb` (
  `MID` int(11) NOT NULL,
  `Groom_First_Name` varchar(255) DEFAULT NULL,
  `Groom_Middle_Name` varchar(255) DEFAULT NULL,
  `Groom_Last_Name` varchar(255) DEFAULT NULL,
  `Groom_Suffix` varchar(10) DEFAULT NULL,
  `Groom_Status` varchar(50) DEFAULT NULL,
  `Groom_BirthDate` varchar(50) DEFAULT NULL,
  `Groom_Region` varchar(100) DEFAULT NULL,
  `Groom_Province` varchar(100) DEFAULT NULL,
  `Groom_City` varchar(100) DEFAULT NULL,
  `Groom_Barangay` varchar(100) DEFAULT NULL,
  `Grooms_Age` varchar(50) DEFAULT NULL,
  `Groom_Father` varchar(255) DEFAULT NULL,
  `Groom_Mother` varchar(255) DEFAULT NULL,
  `Bride_First_Name` varchar(255) DEFAULT NULL,
  `Bride_Middle_Name` varchar(255) DEFAULT NULL,
  `Bride_Last_Name` varchar(255) DEFAULT NULL,
  `Bride_BirthDate` varchar(50) DEFAULT NULL,
  `Bride_Status` varchar(50) DEFAULT NULL,
  `Bride_Age` varchar(50) DEFAULT NULL,
  `Bride_Father` varchar(255) DEFAULT NULL,
  `Bride_Mother` varchar(255) DEFAULT NULL,
  `Bride_Region` varchar(100) DEFAULT NULL,
  `Bride_Province` varchar(100) DEFAULT NULL,
  `Bride_City` varchar(100) DEFAULT NULL,
  `Bride_Barangay` varchar(100) DEFAULT NULL,
  `EventProgress` varchar(50) DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL,
  `ContactNumber` varchar(20) DEFAULT NULL,
  `Contact_Person` varchar(255) DEFAULT NULL,
  `EventServiceID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `marriagetb`
--

INSERT INTO `marriagetb` (`MID`, `Groom_First_Name`, `Groom_Middle_Name`, `Groom_Last_Name`, `Groom_Suffix`, `Groom_Status`, `Groom_BirthDate`, `Groom_Region`, `Groom_Province`, `Groom_City`, `Groom_Barangay`, `Grooms_Age`, `Groom_Father`, `Groom_Mother`, `Bride_First_Name`, `Bride_Middle_Name`, `Bride_Last_Name`, `Bride_BirthDate`, `Bride_Status`, `Bride_Age`, `Bride_Father`, `Bride_Mother`, `Bride_Region`, `Bride_Province`, `Bride_City`, `Bride_Barangay`, `EventProgress`, `Status`, `ContactNumber`, `Contact_Person`, `EventServiceID`) VALUES
(1, 'CRIS NIEL', 'TOLEDO', 'BALOLOY', 'III', '1', '2001-12-03', '05', 'ALBAY', 'LEGAZPI CITY', 'BGY. 24 - RIZAL STREET', '23', 'NELSON BALOLOY', 'FRANALAIN BALOLOY', 'CYRIL', 'CARINO', 'YUTUC', '2002-05-14', '1', '23', 'SAS', 'KJSA', '08', 'LEYTE', 'CAPOOCAN', 'LIBERTAD', 'Pending', 'Complete', NULL, NULL, 89874);

-- --------------------------------------------------------

--
-- Table structure for table `requirements_tbl`
--

CREATE TABLE `requirements_tbl` (
  `ID` varchar(255) DEFAULT NULL,
  `ServiceID` varchar(255) DEFAULT NULL,
  `Marriage_License` varchar(255) DEFAULT 'no',
  `Confirmation` varchar(255) DEFAULT 'no',
  `LiveBirthCert` varchar(255) DEFAULT 'no',
  `Cenomar` varchar(255) DEFAULT 'no',
  `Baptismal` varchar(255) DEFAULT 'no',
  `Interogation` varchar(255) DEFAULT 'no',
  `PreCana` varchar(255) DEFAULT 'no',
  `Confession` varchar(255) DEFAULT 'no',
  `PermissionLetter` varchar(255) DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requirements_tbl`
--

INSERT INTO `requirements_tbl` (`ID`, `ServiceID`, `Marriage_License`, `Confirmation`, `LiveBirthCert`, `Cenomar`, `Baptismal`, `Interogation`, `PreCana`, `Confession`, `PermissionLetter`) VALUES
(NULL, '89874', 'true', 'true', 'true', 'true', 'true', 'true', 'true', 'true', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `useraccounts`
--

CREATE TABLE `useraccounts` (
  `UID` int(50) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `AccessLvl` varchar(50) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Attachment` longtext DEFAULT NULL,
  `isActive` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `useraccounts`
--

INSERT INTO `useraccounts` (`UID`, `Name`, `AccessLvl`, `Username`, `Password`, `Attachment`, `isActive`) VALUES
(1, 'Jay Barcellano', 'Secretary', 'JAY_1', 'Admins', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `witness_testium_tbl`
--

CREATE TABLE `witness_testium_tbl` (
  `ID` varchar(255) DEFAULT NULL,
  `ServiceID` varchar(255) DEFAULT NULL,
  `Ninong` varchar(255) DEFAULT NULL,
  `Ninong_Address` varchar(255) DEFAULT NULL,
  `Ninang` varchar(255) DEFAULT NULL,
  `Ninang_Address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `witness_testium_tbl`
--

INSERT INTO `witness_testium_tbl` (`ID`, `ServiceID`, `Ninong`, `Ninong_Address`, `Ninang`, `Ninang_Address`) VALUES
(NULL, '89874', 'ASA', 'ASA', 'ASA', 'S'),
(NULL, '89874', 'FDF', 'FDF', 'DFDF', 'F');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `marriagetb`
--
ALTER TABLE `marriagetb`
  ADD PRIMARY KEY (`MID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `marriagetb`
--
ALTER TABLE `marriagetb`
  MODIFY `MID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
