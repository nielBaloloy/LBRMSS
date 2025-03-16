-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2025 at 05:43 PM
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
-- Database: `lbrmss_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_account_person`
--

CREATE TABLE `lbrmss_account_person` (
  `pid` int(11) NOT NULL,
  `account_id` int(11) NOT NULL COMMENT 'lbrmss_accounts',
  `lname` text NOT NULL,
  `mname` text NOT NULL,
  `fname` text NOT NULL,
  `contact` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `remark` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_annointing`
--

CREATE TABLE `lbrmss_annointing` (
  `a_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `region` text NOT NULL,
  `province` text NOT NULL,
  `city` text NOT NULL,
  `brgy` text NOT NULL,
  `description` text DEFAULT NULL,
  `assigned_priest` int(11) NOT NULL,
  `created_at` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `remark` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lbrmss_annointing`
--

INSERT INTO `lbrmss_annointing` (`a_id`, `event_id`, `region`, `province`, `city`, `brgy`, `description`, `assigned_priest`, `created_at`, `created_by`, `remark`) VALUES
(3, 33, '09', 'ZAMBOANGA DEL NORTE', 'KALAWIT', 'NEW CALAMBA', '', 1, '2025-03-10', 1, 1),
(4, 34, '08', 'NORTHERN SAMAR', 'LAS NAVAS', 'CUENCO', '', 1, '2025-03-11', 1, 1),
(5, 35, '08', 'EASTERN SAMAR', 'GUIUAN', 'BITAUGAN', '', 1, '2025-03-11', 1, 1),
(6, 36, '05', 'MASBATE', 'LAKEWOOD', 'POBLACION (LAKEWOOD)', '', 1, '2025-03-11', 1, 1),
(7, 37, '09', 'ZAMBOANGA SIBUGAY', 'OLUTANGA', 'MATIM', '', 1, '2025-03-13', 1, 1),
(8, 38, '09', 'ZAMBOANGA SIBUGAY', 'NAGA', 'KALIANTANA', '', 1, '2025-03-13', 1, 1),
(9, 39, '10', 'BUKIDNON', 'LANTAPAN', 'SONGCO', '', 1, '2025-03-15', 1, 1),
(10, 40, '10', 'BUKIDNON', 'LANTAPAN', 'SONGCO', '', 1, '2025-03-15', 1, 1),
(11, 41, '10', 'BUKIDNON', 'LANTAPAN', 'SONGCO', '', 1, '2025-03-15', 1, 1),
(12, 42, '10', 'BUKIDNON', 'LANTAPAN', 'SONGCO', '', 1, '2025-03-15', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_baptism_main`
--

CREATE TABLE `lbrmss_baptism_main` (
  `bapt_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL COMMENT 'src: lbrmss_events_table_main',
  `assigned_priest` int(11) NOT NULL COMMENT 'src:lbrmss_priest_main | priest_id',
  `remark` tinyint(1) NOT NULL COMMENT '1=show 0 =hide'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lbrmss_baptism_main`
--

INSERT INTO `lbrmss_baptism_main` (`bapt_id`, `event_id`, `assigned_priest`, `remark`) VALUES
(1, 11, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_bapt_person`
--

CREATE TABLE `lbrmss_bapt_person` (
  `bapt_person_id` int(11) NOT NULL,
  `bapt_event_id` int(11) NOT NULL COMMENT 'src : lbrmss_baptism_main | bapt_id',
  `bapt_lname` text NOT NULL,
  `bapt_mname` text NOT NULL,
  `bapt_fname` text NOT NULL,
  `bapt_suffix` text DEFAULT NULL,
  `bapt_age` int(11) NOT NULL,
  `bapt_dob` date NOT NULL,
  `bapt_birthplace` text NOT NULL,
  `bapt_gender` int(11) NOT NULL,
  `bapt_father` text NOT NULL,
  `bapt_mother` text NOT NULL,
  `bapt_legitimacy` int(11) NOT NULL COMMENT '1= illegal 2=legal',
  `bapt_region` text NOT NULL,
  `bapt_province` text NOT NULL,
  `bapt_City` text NOT NULL,
  `bapt_Barangay` text NOT NULL,
  `created_at` int(11) NOT NULL,
  `created_by` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `remark` tinyint(4) NOT NULL COMMENT '1= show 0=hide'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lbrmss_bapt_person`
--

INSERT INTO `lbrmss_bapt_person` (`bapt_person_id`, `bapt_event_id`, `bapt_lname`, `bapt_mname`, `bapt_fname`, `bapt_suffix`, `bapt_age`, `bapt_dob`, `bapt_birthplace`, `bapt_gender`, `bapt_father`, `bapt_mother`, `bapt_legitimacy`, `bapt_region`, `bapt_province`, `bapt_City`, `bapt_Barangay`, `created_at`, `created_by`, `updated_by`, `remark`) VALUES
(1, 1, 'fghj', 'ghj', 'ghj', '', 0, '2025-03-24', 'ghj', 2, 'ghj', 'jhgj', 0, 'CAR', 'ABRA', 'LACUB', 'PACOC', 2025, '0000-00-00 00:00:00', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_brgy_contact`
--

CREATE TABLE `lbrmss_brgy_contact` (
  `brgy_id` int(11) NOT NULL,
  `brgy_name` int(11) NOT NULL,
  `contact_person` int(11) NOT NULL,
  `contact_number` varchar(25) NOT NULL,
  `remark` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_burial`
--

CREATE TABLE `lbrmss_burial` (
  `burial_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL COMMENT 'src:lbrmss_event_main',
  `assigned_priest` int(11) NOT NULL,
  `remark` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lbrmss_burial`
--

INSERT INTO `lbrmss_burial` (`burial_id`, `event_id`, `assigned_priest`, `remark`) VALUES
(1, 22, 1, 1),
(2, 23, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_burial_person`
--

CREATE TABLE `lbrmss_burial_person` (
  `bpid` int(11) NOT NULL,
  `event_id` int(11) NOT NULL COMMENT 'src:lbrmss_burial_main | burial_id',
  `bu_lname` text NOT NULL,
  `bu_mname` text NOT NULL,
  `bu_fname` text NOT NULL,
  `bu_suffix` text DEFAULT NULL,
  `bu_gender` int(11) NOT NULL,
  `bu_age` int(11) NOT NULL,
  `bu_birthdate` int(11) NOT NULL,
  `bu_birthplace` int(11) NOT NULL,
  `bu_status` int(11) NOT NULL,
  `bu_father` text NOT NULL,
  `bu_mother` text NOT NULL,
  `bu_spouse` text DEFAULT NULL,
  `bu_nationality` text NOT NULL,
  `bu_reg` text NOT NULL,
  `bu_prov` text NOT NULL,
  `bu_city` text NOT NULL,
  `bu_brgy` text NOT NULL,
  `date_of_death` date NOT NULL,
  `cause_of_death` text NOT NULL,
  `burial_option` int(11) NOT NULL COMMENT '1= cremation 2= traditional 3= special',
  `date_of_burial` date NOT NULL,
  `place_of_interment` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `remark` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lbrmss_burial_person`
--

INSERT INTO `lbrmss_burial_person` (`bpid`, `event_id`, `bu_lname`, `bu_mname`, `bu_fname`, `bu_suffix`, `bu_gender`, `bu_age`, `bu_birthdate`, `bu_birthplace`, `bu_status`, `bu_father`, `bu_mother`, `bu_spouse`, `bu_nationality`, `bu_reg`, `bu_prov`, `bu_city`, `bu_brgy`, `date_of_death`, `cause_of_death`, `burial_option`, `date_of_burial`, `place_of_interment`, `created_at`, `created_by`, `updated_at`, `updated_by`, `remark`) VALUES
(1, 31730, 'uyuyu', 'iuyuii', 'uyy', NULL, 1, 23, 2025, 0, 0, 'fsd', 'sdf', 'dffg', 'Azerbaijani', '10', 'CAMIGUIN', 'MAHINOG', 'SAN MIGUEL', '2025-03-25', 'hg', 0, '2025-03-11', 0, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_client_list`
--

CREATE TABLE `lbrmss_client_list` (
  `cid` int(11) NOT NULL,
  `name` text NOT NULL,
  `contact_no` text NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'src:account table | uid',
  `remark` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=show 0 =hide'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lbrmss_client_list`
--

INSERT INTO `lbrmss_client_list` (`cid`, `name`, `contact_no`, `created_at`, `created_by`, `remark`) VALUES
(1, 'Samlgh', '9876967878', '2025-02-23 11:01:22', 1, 1),
(2, 'Jay', '9878778', '2025-02-23 14:49:33', 1, 1),
(12, 'Across Test', '987987', '2025-03-03 17:19:18', 1, 1),
(19, 'Sample Person', '9878788', '2025-03-06 10:44:41', 1, 1),
(24, 'Sample Burial', '98945596', '2025-03-07 15:13:26', 1, 1),
(37, 'Annioniting', '987987', '2025-03-10 16:20:20', 1, 1),
(38, 'sdfdsf', '9879878', '2025-03-11 15:56:03', 1, 1),
(39, 'gdf', '8979789', '2025-03-11 15:57:04', 1, 1),
(40, 'gfd', '898798798', '2025-03-11 15:58:55', 1, 1),
(41, 'sad', '98999989', '2025-03-13 10:06:49', 1, 1),
(42, 'valid', '9848595', '2025-03-13 10:07:54', 1, 1),
(43, 'valid', '9848595', '2025-03-13 10:08:33', 1, 1),
(44, 'LAN2', '9897865', '2025-03-15 18:44:49', 1, 1),
(45, 'LAN2', '9897865', '2025-03-15 18:45:04', 1, 1),
(46, 'LAN2', '9897865', '2025-03-15 18:45:20', 1, 1),
(47, 'LAN2', '9897865', '2025-03-15 18:45:33', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_confirmation_main`
--

CREATE TABLE `lbrmss_confirmation_main` (
  `con_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL COMMENT 'src:lbrmss_event_table | event_id',
  `assigned_priest` int(11) NOT NULL COMMENT 'src:lbrmss_priest_main | priest_id',
  `remark` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lbrmss_confirmation_main`
--

INSERT INTO `lbrmss_confirmation_main` (`con_id`, `event_id`, `assigned_priest`, `remark`) VALUES
(1, 18, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_con_person`
--

CREATE TABLE `lbrmss_con_person` (
  `con_person_id` int(11) NOT NULL,
  `con_event_id` int(11) NOT NULL COMMENT 'src:lbrmss_confirmation_main | con_id',
  `con_lname` text NOT NULL,
  `con_mname` text NOT NULL,
  `con_fname` text NOT NULL,
  `con_suffix` text DEFAULT NULL,
  `con_gender` int(11) NOT NULL COMMENT '1 male 2 female',
  `con_birthplace` text NOT NULL,
  `con_birth_date` date NOT NULL,
  `con_region` text NOT NULL,
  `con_province` text NOT NULL,
  `con_city` text NOT NULL,
  `con_barangay` text NOT NULL,
  `con_mother` text NOT NULL,
  `con_father` text NOT NULL,
  `con_legitimacy` int(11) DEFAULT NULL COMMENT '1=illegal 2= legal',
  `Nationality` text NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `remark` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lbrmss_con_person`
--

INSERT INTO `lbrmss_con_person` (`con_person_id`, `con_event_id`, `con_lname`, `con_mname`, `con_fname`, `con_suffix`, `con_gender`, `con_birthplace`, `con_birth_date`, `con_region`, `con_province`, `con_city`, `con_barangay`, `con_mother`, `con_father`, `con_legitimacy`, `Nationality`, `created_at`, `created_by`, `updated_at`, `updated_by`, `remark`) VALUES
(1, 1, 'jhgb', 'gbhj', 'ggbgj', NULL, 1, 'hg jg', '2025-03-28', '10', 'MISAMIS OCCIDENTAL', 'LOPEZ JAENA', 'DON ANDRES SORIANO', 'hghj', 'hgh', 1, 'Azerbaijani', '2025-03-06 10:44:42', 1, '0000-00-00 00:00:00', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_donations`
--

CREATE TABLE `lbrmss_donations` (
  `donation_id` int(11) NOT NULL,
  `donor` varchar(155) DEFAULT NULL,
  `purpose` varchar(255) DEFAULT NULL,
  `amount_donated` decimal(10,2) NOT NULL,
  `donation_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `reference_no` varchar(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(1) NOT NULL,
  `remark` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_event_fee`
--

CREATE TABLE `lbrmss_event_fee` (
  `event_fee_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL COMMENT 'lbrmss_event_main | event_id',
  `reference_no` varchar(10) NOT NULL,
  `amount_total` int(11) NOT NULL COMMENT 'lbrmss_event_table_main',
  `down_payment` int(11) NOT NULL,
  `balance` int(11) NOT NULL,
  `due_date` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '''1 =Pending'', '' 2= Partially Paid'', ''3= Paid'')',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `remark` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_event_other`
--

CREATE TABLE `lbrmss_event_other` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL COMMENT 'lbrmss_event_table_main',
  `event_desc` int(11) NOT NULL,
  `type_of_mass` int(11) DEFAULT NULL COMMENT 'lbrmss_mass_type | mt_id',
  `event_reg` int(11) NOT NULL,
  `event_prov` int(11) NOT NULL,
  `event_city` int(11) NOT NULL,
  `event_brgy` int(11) NOT NULL,
  `assigned_priest` int(11) NOT NULL COMMENT 'lbrmss_priest_id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_event_services`
--

CREATE TABLE `lbrmss_event_services` (
  `etype_id` int(11) NOT NULL,
  `event_name` text NOT NULL,
  `event_description` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `remark` tinyint(1) NOT NULL COMMENT '0 = hide 1=show'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lbrmss_event_services`
--

INSERT INTO `lbrmss_event_services` (`etype_id`, `event_name`, `event_description`, `created_at`, `remark`) VALUES
(1, 'Marriage', '', '2025-02-21 03:20:50', 1),
(2, 'Baptism', 'Sacrament of Baptism service', '2025-02-22 11:17:47', 1),
(3, 'Confirmation', 'Sacrament of Confirmation service', '2025-02-22 11:17:47', 1),
(4, 'Burial', 'Burial service', '2025-02-22 11:17:47', 1),
(5, 'Annointing of the Sick', 'Sacrament of Anointing of the Sick service', '2025-02-22 11:17:47', 1),
(6, 'Blessing', 'Blessing service', '2025-02-22 11:17:47', 1),
(7, 'Misa', 'Holy Mass service', '2025-02-22 11:17:47', 1),
(8, 'Others', 'Other church services', '2025-02-22 11:17:47', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_event_table_main`
--

CREATE TABLE `lbrmss_event_table_main` (
  `event_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL COMMENT 'src : lbrmss_event_services | etype_id',
  `client` int(11) NOT NULL COMMENT 'src : lbrmss_client_list | cid',
  `date` date NOT NULL,
  `date_to` date NOT NULL,
  `time_from` time NOT NULL,
  `time_to` time NOT NULL,
  `venue_name` text DEFAULT NULL,
  `duration` int(5) NOT NULL,
  `type` int(1) NOT NULL COMMENT '1=mass 2 special',
  `days` int(2) NOT NULL,
  `venue_type` tinyint(1) DEFAULT NULL COMMENT '1 =church 2=pastoral center 3 =outside',
  `priest_assigned_id` int(5) NOT NULL COMMENT 'src:lbrmss_priest_main | priest_id',
  `event_progress` int(1) NOT NULL COMMENT '0=pending 1=scheduled 2=done',
  `requirement_status` int(1) NOT NULL COMMENT '0 = incomplete 1= complete',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `remark` tinyint(1) NOT NULL COMMENT '0=hide 1=show'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lbrmss_event_table_main`
--

INSERT INTO `lbrmss_event_table_main` (`event_id`, `service_id`, `client`, `date`, `date_to`, `time_from`, `time_to`, `venue_name`, `duration`, `type`, `days`, `venue_type`, `priest_assigned_id`, `event_progress`, `requirement_status`, `created_at`, `created_by`, `remark`) VALUES
(1, 1, 1, '2025-02-26', '2025-02-26', '10:00:00', '09:59:00', 'Pastoral Center', 1, 2, 1, 0, 1, 0, 0, '2025-02-23 11:01:22', 1, 1),
(2, 1, 2, '2025-02-25', '2025-02-25', '11:00:00', '09:00:00', 'Pastoral Center', 120, 2, 1, 0, 1, 0, 0, '2025-02-23 14:49:33', 1, 1),
(11, 2, 12, '2025-03-31', '2025-03-31', '10:00:00', '09:00:00', 'Pastoral Center', 60, 2, 1, 0, 1, 0, 0, '2025-03-03 17:19:18', 1, 1),
(18, 3, 19, '2025-03-24', '2025-03-24', '10:00:00', '09:00:00', 'Pastoral Center', 60, 2, 1, 0, 1, 0, 0, '2025-03-06 10:44:41', 1, 1),
(23, 4, 24, '2025-03-25', '2025-03-25', '11:00:00', '09:50:00', 'Pastoral Center', 70, 2, 1, 0, 1, 0, 0, '2025-03-07 15:13:26', 1, 1),
(33, 5, 37, '2025-03-25', '2025-03-25', '10:00:00', '09:00:00', NULL, 60, 1, 1, NULL, 1, 1, 1, '2025-03-10 16:20:20', 1, 1),
(34, 5, 38, '2025-03-25', '2025-03-25', '10:55:00', '10:00:00', NULL, 55, 1, 1, NULL, 1, 1, 1, '2025-03-11 15:56:03', 1, 1),
(35, 5, 39, '2025-03-25', '2025-03-25', '11:30:00', '11:00:00', NULL, 30, 1, 1, NULL, 1, 1, 1, '2025-03-11 15:57:04', 1, 1),
(36, 5, 40, '2025-03-25', '2025-03-25', '07:50:00', '06:55:00', NULL, 55, 1, 1, NULL, 1, 1, 1, '2025-03-11 15:58:55', 1, 1),
(37, 5, 41, '2025-03-26', '2025-03-26', '10:00:00', '09:00:00', NULL, 60, 1, 1, NULL, 1, 1, 1, '2025-03-13 10:06:49', 1, 1),
(38, 5, 43, '2025-03-26', '2025-03-26', '11:00:00', '10:55:00', NULL, 5, 1, 1, NULL, 1, 1, 1, '2025-03-13 10:08:33', 1, 1),
(39, 5, 44, '2025-03-31', '2025-03-31', '11:00:00', '09:00:00', NULL, 120, 1, 1, NULL, 1, 1, 1, '2025-03-15 18:44:49', 1, 1),
(40, 5, 45, '2025-04-08', '2025-04-08', '11:00:00', '09:00:00', NULL, 120, 1, 1, NULL, 1, 1, 1, '2025-03-15 18:45:04', 1, 1),
(41, 5, 46, '2025-04-08', '2025-04-08', '07:56:00', '05:55:00', NULL, 121, 1, 1, NULL, 1, 1, 1, '2025-03-15 18:45:20', 1, 1),
(42, 5, 47, '2025-04-08', '2025-04-08', '07:56:00', '05:55:00', NULL, 121, 1, 1, NULL, 1, 1, 1, '2025-03-15 18:45:33', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_file`
--

CREATE TABLE `lbrmss_file` (
  `file_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(255) NOT NULL,
  `remark` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_logs`
--

CREATE TABLE `lbrmss_logs` (
  `logid` int(11) NOT NULL,
  `account_id` int(11) NOT NULL COMMENT 'lbrmss_accounts',
  `action` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_mariage_main`
--

CREATE TABLE `lbrmss_mariage_main` (
  `mid` int(11) NOT NULL,
  `event_id` int(11) NOT NULL COMMENT 'src:lbrmss_event_table_main | event_id',
  `assigned_priest` int(11) NOT NULL COMMENT 'src: lbrmss_priest_main | priest_id',
  `remark` tinyint(1) NOT NULL COMMENT '1=show 0 hide'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lbrmss_mariage_main`
--

INSERT INTO `lbrmss_mariage_main` (`mid`, `event_id`, `assigned_priest`, `remark`) VALUES
(1, 1, 1, 1),
(2, 2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_mass_collections`
--

CREATE TABLE `lbrmss_mass_collections` (
  `collection_id` int(11) NOT NULL,
  `mass_id` int(11) NOT NULL,
  `amount_collected` decimal(10,2) NOT NULL,
  `collected_by` varchar(100) DEFAULT NULL,
  `collection_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `reference_no` varchar(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(1) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int(1) NOT NULL,
  `remark` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_m_bride`
--

CREATE TABLE `lbrmss_m_bride` (
  `b_id` int(11) NOT NULL,
  `b_event_id` int(11) NOT NULL,
  `bride_lname` text NOT NULL,
  `bride_mname` text DEFAULT NULL,
  `bride_fname` text NOT NULL,
  `age` int(3) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `b_region` text DEFAULT NULL,
  `b_province` text DEFAULT NULL,
  `b_city` text DEFAULT NULL,
  `b_brgy` text DEFAULT NULL,
  `b_civil_status` text DEFAULT NULL,
  `b_father` text DEFAULT NULL,
  `b_mother` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(5) NOT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `remark` tinyint(1) DEFAULT 1 COMMENT '1=show, 0=hide'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lbrmss_m_bride`
--

INSERT INTO `lbrmss_m_bride` (`b_id`, `b_event_id`, `bride_lname`, `bride_mname`, `bride_fname`, `age`, `dob`, `b_region`, `b_province`, `b_city`, `b_brgy`, `b_civil_status`, `b_father`, `b_mother`, `created_at`, `created_by`, `updated_at`, `updated_by`, `remark`) VALUES
(1, 1, 'fghgg', 'gfh', 'hfgh', 19, '2006-02-08', '07', 'SIQUIJOR', 'SAN JUAN', 'PALITON', '1', 'gfh', 'fghgfh', '2025-02-23 11:01:22', 1, '0000-00-00 00:00:00', 0, 1),
(2, 2, 'gfg', 'dfg', 'fgf', 22, '2003-02-12', '11', 'DAVAO DEL SUR', 'SANTA CRUZ', 'TIBOLO', '1', 'fdgf', 'fdgf', '2025-02-23 14:49:33', 1, '0000-00-00 00:00:00', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_m_groom`
--

CREATE TABLE `lbrmss_m_groom` (
  `g_id` int(11) NOT NULL,
  `g_event_id` int(11) NOT NULL COMMENT 'src : lbrmss_event_table_main | event_id',
  `groom_lname` text NOT NULL,
  `groom_mname` text DEFAULT NULL,
  `groom_fname` text NOT NULL,
  `groom_suffix` text DEFAULT NULL,
  `age` int(3) NOT NULL,
  `dob` date NOT NULL,
  `g_region` text NOT NULL,
  `g_province` text NOT NULL,
  `g_city` text NOT NULL,
  `g_brgy` text NOT NULL,
  `g_civil_status` text NOT NULL,
  `g_father` text NOT NULL,
  `g_mother` text NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(5) NOT NULL COMMENT 'src:lbrmss_accounts | acccountId',
  `updated_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL COMMENT 'src:lbrmss_accounts | acccountId',
  `remark` tinyint(1) NOT NULL COMMENT '1=show 0= hide'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lbrmss_m_groom`
--

INSERT INTO `lbrmss_m_groom` (`g_id`, `g_event_id`, `groom_lname`, `groom_mname`, `groom_fname`, `groom_suffix`, `age`, `dob`, `g_region`, `g_province`, `g_city`, `g_brgy`, `g_civil_status`, `g_father`, `g_mother`, `created_at`, `created_by`, `updated_at`, `updated_by`, `remark`) VALUES
(1, 1, 'fghfgh', 'gfh', 'gfhgh', '', 19, '2006-02-07', '06', 'GUIMARAS', 'SAN LORENZO', 'SAN ENRIQUE (LEBAS)', '1', 'ghgfh', 'fghgf', '2025-02-23 11:01:22', 1, '0000-00-00 00:00:00', 0, 1),
(2, 2, 'fdf', 'dfgf', 'dfgf', '', 22, '2003-02-13', '01', 'ILOCOS NORTE', 'BATAC CITY', 'BEN-AGAN (POB.)', '0', 'dfgdf', 'dfgf', '2025-02-23 14:49:33', 1, '0000-00-00 00:00:00', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_m_requirements`
--

CREATE TABLE `lbrmss_m_requirements` (
  `req_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL COMMENT 'src:lbrmss_event_table_main | event_id',
  `baptismal_certificate` enum('true','no') DEFAULT 'no',
  `marriage_license` enum('true','no') DEFAULT 'no',
  `confirmation` enum('true','no') DEFAULT 'no',
  `birth_certificate` enum('true','no') DEFAULT 'no',
  `cenomar` enum('true','no') DEFAULT 'no',
  `interrogation` enum('true','no') DEFAULT 'no',
  `precana_seminar` enum('true','no') DEFAULT 'no',
  `confession` enum('true','no') DEFAULT 'no',
  `remark` tinyint(1) NOT NULL COMMENT '1=show 0 hide',
  `family_consent` enum('true','no') NOT NULL DEFAULT 'no',
  `cremation_authorization` enum('true','no') NOT NULL DEFAULT 'no',
  `death_certificate` enum('true','no') NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lbrmss_m_requirements`
--

INSERT INTO `lbrmss_m_requirements` (`req_id`, `event_id`, `baptismal_certificate`, `marriage_license`, `confirmation`, `birth_certificate`, `cenomar`, `interrogation`, `precana_seminar`, `confession`, `remark`, `family_consent`, `cremation_authorization`, `death_certificate`) VALUES
(1, 1, 'true', 'true', 'true', 'true', 'true', 'true', 'no', 'no', 1, 'no', 'no', 'no'),
(2, 2, 'true', 'no', 'true', 'no', 'true', 'true', 'no', 'no', 1, 'no', 'no', 'no'),
(4, 11, 'no', 'true', 'true', 'true', 'no', 'no', 'no', 'true', 1, 'no', 'no', 'no'),
(7, 18, 'true', 'true', 'true', 'true', 'true', 'no', 'no', 'true', 1, 'no', 'no', 'no'),
(10, 23, 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 1, 'no', 'no', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_m_witness`
--

CREATE TABLE `lbrmss_m_witness` (
  `wid` int(11) NOT NULL,
  `marriage_id` int(11) NOT NULL COMMENT 'src:lbrmss_event_table_main | event_id',
  `full_name` text NOT NULL,
  `witness_to` int(1) NOT NULL COMMENT '1= groom 2 =bride',
  `address` text NOT NULL,
  `remark` tinyint(1) NOT NULL COMMENT '1=show 0=hide'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_position`
--

CREATE TABLE `lbrmss_position` (
  `pos_id` int(11) NOT NULL,
  `pos_name` text NOT NULL,
  `pos_prefix` text NOT NULL,
  `remark` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=hide 1= show'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lbrmss_position`
--

INSERT INTO `lbrmss_position` (`pos_id`, `pos_name`, `pos_prefix`, `remark`) VALUES
(1, 'Reverend Father', 'Rev. Fr.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_priest_main`
--

CREATE TABLE `lbrmss_priest_main` (
  `priest_id` int(11) NOT NULL,
  `lname` text NOT NULL,
  `mname` text NOT NULL,
  `fname` text NOT NULL,
  `status` int(1) NOT NULL COMMENT '0 =inactive 1= active',
  `contact_number` text NOT NULL,
  `position` int(5) NOT NULL COMMENT 'src:lbrmss_church_position',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `remark` int(11) NOT NULL DEFAULT 1 COMMENT '1=show 0=hide'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lbrmss_priest_main`
--

INSERT INTO `lbrmss_priest_main` (`priest_id`, `lname`, `mname`, `fname`, `status`, `contact_number`, `position`, `created_at`, `created_by`, `remark`) VALUES
(1, 'Murillo', 'Paldo', 'Leandro', 1, '09876685745', 1, '2025-02-21 02:38:14', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_priest_schedule`
--

CREATE TABLE `lbrmss_priest_schedule` (
  `sched_id` int(11) NOT NULL,
  `priest_id` int(11) NOT NULL COMMENT 'src: lbrmss_priest_main | priest_id',
  `sched_event_id` int(11) NOT NULL COMMENT 'src : lbrmss_event_table_main | event+id',
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `time_from` time NOT NULL,
  `time_to` time NOT NULL,
  `created_at` datetime NOT NULL,
  `remark` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=show 0=hide'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lbrmss_priest_schedule`
--

INSERT INTO `lbrmss_priest_schedule` (`sched_id`, `priest_id`, `sched_event_id`, `date_from`, `date_to`, `time_from`, `time_to`, `created_at`, `remark`) VALUES
(1, 1, 0, '2025-02-24', '2025-02-24', '09:00:00', '11:00:00', '2025-02-22 00:00:00', 1),
(2, 1, 34, '2025-03-25', '2025-03-25', '10:55:00', '10:00:00', '2025-03-11 15:56:03', 1),
(3, 1, 35, '2025-03-25', '2025-03-25', '11:30:00', '11:00:00', '2025-03-11 15:57:04', 1),
(4, 1, 36, '2025-03-25', '2025-03-25', '07:50:00', '06:55:00', '2025-03-11 15:58:55', 1),
(5, 1, 37, '2025-03-26', '2025-03-26', '10:00:00', '09:00:00', '2025-03-13 10:06:49', 1),
(6, 1, 38, '2025-03-26', '2025-03-26', '11:00:00', '10:55:00', '2025-03-13 10:08:33', 1),
(7, 1, 39, '2025-03-31', '2025-03-31', '11:00:00', '09:00:00', '2025-03-15 18:44:49', 1),
(8, 1, 40, '2025-04-08', '2025-04-08', '11:00:00', '09:00:00', '2025-03-15 18:45:04', 1),
(9, 1, 41, '2025-04-08', '2025-04-08', '07:56:00', '05:55:00', '2025-03-15 18:45:20', 1),
(10, 1, 42, '2025-04-08', '2025-04-08', '07:56:00', '05:55:00', '2025-03-15 18:45:33', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_seminar`
--

CREATE TABLE `lbrmss_seminar` (
  `seminar_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL COMMENT 'src: lbrmss_event_table_main',
  `seminar_title` text NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `time_from` time NOT NULL,
  `time_to` time NOT NULL,
  `status` int(11) NOT NULL COMMENT '1=done 0=pending',
  `duration` int(11) NOT NULL,
  `days` int(11) NOT NULL,
  `seminar_venue` text NOT NULL,
  `remark` tinyint(1) NOT NULL COMMENT '1=show 0= hide'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lbrmss_seminar`
--

INSERT INTO `lbrmss_seminar` (`seminar_id`, `event_id`, `seminar_title`, `date_from`, `date_to`, `time_from`, `time_to`, `status`, `duration`, `days`, `seminar_venue`, `remark`) VALUES
(1, 1, 'fghgf', '2025-02-18', '2025-02-18', '09:00:00', '11:00:00', 0, 120, 1, 'fghgf', 1),
(2, 2, 'sdfds', '2025-02-27', '2025-02-27', '09:00:00', '10:00:00', 0, 60, 1, 'dsfdsf', 1),
(4, 11, 'ghhj', '2025-03-28', '2025-03-28', '09:00:00', '10:00:00', 0, 60, 1, 'fghjhjjhj', 1),
(7, 18, 'jgjhgg', '2025-03-26', '2025-03-26', '09:00:00', '10:00:00', 0, 60, 1, 'hghhj', 1);

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
  `isActive` int(255) NOT NULL,
  `remark` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `useraccounts`
--

INSERT INTO `useraccounts` (`UID`, `Name`, `AccessLvl`, `Username`, `Password`, `isActive`, `remark`) VALUES
(1, 'Jay Barcellano', 'Secretary', 'JAY_1', 'Admins', 1, 1),
(1, 'Cashier', 'Cashier', 'cashier1', 'cashier', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `witness_testium_tbl`
--

CREATE TABLE `witness_testium_tbl` (
  `w_id` int(11) NOT NULL,
  `ServiceID` varchar(255) DEFAULT NULL,
  `Ninong` varchar(255) DEFAULT NULL,
  `Ninong_Address` varchar(255) DEFAULT NULL,
  `Ninang` varchar(255) DEFAULT NULL,
  `Ninang_Address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `witness_testium_tbl`
--

INSERT INTO `witness_testium_tbl` (`w_id`, `ServiceID`, `Ninong`, `Ninong_Address`, `Ninang`, `Ninang_Address`) VALUES
(1, '1', 'fgh', 'hfgh', 'gfhfg', 'h'),
(2, '1', 'fghghgfh', 'fghgfh', 'gf', 'gfh'),
(3, '2', 'dfg', 'fdg', 'gf', 'fg'),
(9, '18', 'ngh', 'hgh', 'hg', 'gj');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lbrmss_account_person`
--
ALTER TABLE `lbrmss_account_person`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `lbrmss_annointing`
--
ALTER TABLE `lbrmss_annointing`
  ADD PRIMARY KEY (`a_id`);

--
-- Indexes for table `lbrmss_baptism_main`
--
ALTER TABLE `lbrmss_baptism_main`
  ADD PRIMARY KEY (`bapt_id`);

--
-- Indexes for table `lbrmss_bapt_person`
--
ALTER TABLE `lbrmss_bapt_person`
  ADD PRIMARY KEY (`bapt_person_id`);

--
-- Indexes for table `lbrmss_brgy_contact`
--
ALTER TABLE `lbrmss_brgy_contact`
  ADD PRIMARY KEY (`brgy_id`);

--
-- Indexes for table `lbrmss_burial`
--
ALTER TABLE `lbrmss_burial`
  ADD PRIMARY KEY (`burial_id`);

--
-- Indexes for table `lbrmss_burial_person`
--
ALTER TABLE `lbrmss_burial_person`
  ADD PRIMARY KEY (`bpid`);

--
-- Indexes for table `lbrmss_client_list`
--
ALTER TABLE `lbrmss_client_list`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `lbrmss_confirmation_main`
--
ALTER TABLE `lbrmss_confirmation_main`
  ADD PRIMARY KEY (`con_id`);

--
-- Indexes for table `lbrmss_con_person`
--
ALTER TABLE `lbrmss_con_person`
  ADD PRIMARY KEY (`con_person_id`);

--
-- Indexes for table `lbrmss_donations`
--
ALTER TABLE `lbrmss_donations`
  ADD PRIMARY KEY (`donation_id`);

--
-- Indexes for table `lbrmss_event_fee`
--
ALTER TABLE `lbrmss_event_fee`
  ADD PRIMARY KEY (`event_fee_id`);

--
-- Indexes for table `lbrmss_event_other`
--
ALTER TABLE `lbrmss_event_other`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lbrmss_event_table_main`
--
ALTER TABLE `lbrmss_event_table_main`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `lbrmss_file`
--
ALTER TABLE `lbrmss_file`
  ADD PRIMARY KEY (`file_id`);

--
-- Indexes for table `lbrmss_logs`
--
ALTER TABLE `lbrmss_logs`
  ADD PRIMARY KEY (`logid`);

--
-- Indexes for table `lbrmss_mariage_main`
--
ALTER TABLE `lbrmss_mariage_main`
  ADD PRIMARY KEY (`mid`);

--
-- Indexes for table `lbrmss_mass_collections`
--
ALTER TABLE `lbrmss_mass_collections`
  ADD PRIMARY KEY (`collection_id`);

--
-- Indexes for table `lbrmss_m_bride`
--
ALTER TABLE `lbrmss_m_bride`
  ADD PRIMARY KEY (`b_id`);

--
-- Indexes for table `lbrmss_m_groom`
--
ALTER TABLE `lbrmss_m_groom`
  ADD PRIMARY KEY (`g_id`);

--
-- Indexes for table `lbrmss_m_requirements`
--
ALTER TABLE `lbrmss_m_requirements`
  ADD PRIMARY KEY (`req_id`);

--
-- Indexes for table `lbrmss_m_witness`
--
ALTER TABLE `lbrmss_m_witness`
  ADD PRIMARY KEY (`wid`);

--
-- Indexes for table `lbrmss_position`
--
ALTER TABLE `lbrmss_position`
  ADD PRIMARY KEY (`pos_id`);

--
-- Indexes for table `lbrmss_priest_main`
--
ALTER TABLE `lbrmss_priest_main`
  ADD PRIMARY KEY (`priest_id`);

--
-- Indexes for table `lbrmss_priest_schedule`
--
ALTER TABLE `lbrmss_priest_schedule`
  ADD PRIMARY KEY (`sched_id`);

--
-- Indexes for table `lbrmss_seminar`
--
ALTER TABLE `lbrmss_seminar`
  ADD PRIMARY KEY (`seminar_id`);

--
-- Indexes for table `useraccounts`
--
ALTER TABLE `useraccounts`
  ADD PRIMARY KEY (`remark`);

--
-- Indexes for table `witness_testium_tbl`
--
ALTER TABLE `witness_testium_tbl`
  ADD PRIMARY KEY (`w_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lbrmss_account_person`
--
ALTER TABLE `lbrmss_account_person`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lbrmss_annointing`
--
ALTER TABLE `lbrmss_annointing`
  MODIFY `a_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `lbrmss_baptism_main`
--
ALTER TABLE `lbrmss_baptism_main`
  MODIFY `bapt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lbrmss_bapt_person`
--
ALTER TABLE `lbrmss_bapt_person`
  MODIFY `bapt_person_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lbrmss_brgy_contact`
--
ALTER TABLE `lbrmss_brgy_contact`
  MODIFY `brgy_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lbrmss_burial`
--
ALTER TABLE `lbrmss_burial`
  MODIFY `burial_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lbrmss_burial_person`
--
ALTER TABLE `lbrmss_burial_person`
  MODIFY `bpid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lbrmss_client_list`
--
ALTER TABLE `lbrmss_client_list`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `lbrmss_confirmation_main`
--
ALTER TABLE `lbrmss_confirmation_main`
  MODIFY `con_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lbrmss_con_person`
--
ALTER TABLE `lbrmss_con_person`
  MODIFY `con_person_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lbrmss_donations`
--
ALTER TABLE `lbrmss_donations`
  MODIFY `donation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lbrmss_event_fee`
--
ALTER TABLE `lbrmss_event_fee`
  MODIFY `event_fee_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lbrmss_event_other`
--
ALTER TABLE `lbrmss_event_other`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lbrmss_event_table_main`
--
ALTER TABLE `lbrmss_event_table_main`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `lbrmss_file`
--
ALTER TABLE `lbrmss_file`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lbrmss_logs`
--
ALTER TABLE `lbrmss_logs`
  MODIFY `logid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lbrmss_mariage_main`
--
ALTER TABLE `lbrmss_mariage_main`
  MODIFY `mid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lbrmss_mass_collections`
--
ALTER TABLE `lbrmss_mass_collections`
  MODIFY `collection_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lbrmss_m_bride`
--
ALTER TABLE `lbrmss_m_bride`
  MODIFY `b_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lbrmss_m_groom`
--
ALTER TABLE `lbrmss_m_groom`
  MODIFY `g_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lbrmss_m_requirements`
--
ALTER TABLE `lbrmss_m_requirements`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `lbrmss_m_witness`
--
ALTER TABLE `lbrmss_m_witness`
  MODIFY `wid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lbrmss_position`
--
ALTER TABLE `lbrmss_position`
  MODIFY `pos_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lbrmss_priest_main`
--
ALTER TABLE `lbrmss_priest_main`
  MODIFY `priest_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lbrmss_priest_schedule`
--
ALTER TABLE `lbrmss_priest_schedule`
  MODIFY `sched_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `lbrmss_seminar`
--
ALTER TABLE `lbrmss_seminar`
  MODIFY `seminar_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `useraccounts`
--
ALTER TABLE `useraccounts`
  MODIFY `remark` tinyint(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `witness_testium_tbl`
--
ALTER TABLE `witness_testium_tbl`
  MODIFY `w_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
