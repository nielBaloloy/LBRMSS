-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2025 at 04:36 AM
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
  `service_id` int(11) NOT NULL COMMENT 'lbrmss_event_services | service_id',
  `reference_no` varchar(255) NOT NULL,
  `payment_type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1=full 2 = downpayment',
  `amount_total` int(11) DEFAULT NULL COMMENT 'lbrmss_event_table_main',
  `payment` int(11) DEFAULT NULL,
  `balance` int(11) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '''1 =Pending'', '' 2= Partially Paid'', ''3= Paid'')',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `remark` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lbrmss_event_fee_assignment`
--

CREATE TABLE `lbrmss_event_fee_assignment` (
  `fee_ids` int(11) NOT NULL,
  `service_fee_id` int(11) NOT NULL COMMENT 'src : service_id',
  `ServiceType` int(1) NOT NULL COMMENT '1= Mass\r\n2 = Special\r\n3 = Burial- Traditional\r\n4 = Burial- Cremation\r\n5 = Certificate\r\n',
  `VenueType` int(1) NOT NULL COMMENT '0= church 1=outside',
  `name` text NOT NULL,
  `model` varchar(20) NOT NULL COMMENT 'v-model for each fee',
  `amount` decimal(10,2) NOT NULL,
  `remark` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lbrmss_event_fee_assignment`
--

INSERT INTO `lbrmss_event_fee_assignment` (`fee_ids`, `service_fee_id`, `ServiceType`, `VenueType`, `name`, `model`, `amount`, `remark`) VALUES
(1, 1, 2, 0, 'Marriage Ce', 'marriageCeremonyFee', 500.00, 1),
(2, 1, 2, 0, 'Marriage Se', 'seminarFee', 500.00, 1),
(3, 1, 2, 0, 'Documentati', 'documentationFee', 500.00, 1),
(4, 1, 2, 0, 'Church Rese', 'reservationFee', 500.00, 1),
(5, 1, 2, 0, 'Stipend for', 'priestStipend', 500.00, 1),
(6, 2, 2, 0, 'Baptism Ceremony Fee', 'BaptismCeremonyFee', 500.00, 1),
(7, 2, 2, 0, 'Priest\'s Stipend', 'BaptismPriestStipend', 1000.00, 1),
(8, 5, 5, 0, 'Marriage Certificate', '_model', 100.00, 1),
(9, 2, 5, 0, 'Baptismal Certification', '_model', 100.00, 1),
(10, 3, 2, 0, 'Confirmation Certificate', 'ConfirmationCertific', 600.00, 1),
(11, 4, 3, 0, 'Burial Certificate', 'BurialCertificate', 100.00, 1),
(12, 4, 2, 0, 'Funeral Mass Fee', 'FuneralMassFee', 900.00, 1),
(16, 4, 3, 0, 'Candles and Altar Decorations', 'CandlesandAltarDecor', 900.00, 1);

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
  `sched_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 =not scheduled  1 = scheduled ',
  `created_at` datetime NOT NULL,
  `remark` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=show 0=hide'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `status` int(11) NOT NULL COMMENT '1=done 2 =Scheduled 0=pending',
  `duration` int(11) NOT NULL,
  `days` int(11) NOT NULL,
  `seminar_venue` text NOT NULL,
  `remark` tinyint(1) NOT NULL COMMENT '1=show 0= hide'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indexes for table `lbrmss_event_fee_assignment`
--
ALTER TABLE `lbrmss_event_fee_assignment`
  ADD PRIMARY KEY (`fee_ids`);

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
  MODIFY `a_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lbrmss_baptism_main`
--
ALTER TABLE `lbrmss_baptism_main`
  MODIFY `bapt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lbrmss_bapt_person`
--
ALTER TABLE `lbrmss_bapt_person`
  MODIFY `bapt_person_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lbrmss_brgy_contact`
--
ALTER TABLE `lbrmss_brgy_contact`
  MODIFY `brgy_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lbrmss_burial`
--
ALTER TABLE `lbrmss_burial`
  MODIFY `burial_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lbrmss_burial_person`
--
ALTER TABLE `lbrmss_burial_person`
  MODIFY `bpid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lbrmss_client_list`
--
ALTER TABLE `lbrmss_client_list`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lbrmss_confirmation_main`
--
ALTER TABLE `lbrmss_confirmation_main`
  MODIFY `con_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lbrmss_con_person`
--
ALTER TABLE `lbrmss_con_person`
  MODIFY `con_person_id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `lbrmss_event_fee_assignment`
--
ALTER TABLE `lbrmss_event_fee_assignment`
  MODIFY `fee_ids` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `lbrmss_event_other`
--
ALTER TABLE `lbrmss_event_other`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lbrmss_event_table_main`
--
ALTER TABLE `lbrmss_event_table_main`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `mid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lbrmss_mass_collections`
--
ALTER TABLE `lbrmss_mass_collections`
  MODIFY `collection_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lbrmss_m_bride`
--
ALTER TABLE `lbrmss_m_bride`
  MODIFY `b_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lbrmss_m_groom`
--
ALTER TABLE `lbrmss_m_groom`
  MODIFY `g_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lbrmss_m_requirements`
--
ALTER TABLE `lbrmss_m_requirements`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `sched_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lbrmss_seminar`
--
ALTER TABLE `lbrmss_seminar`
  MODIFY `seminar_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `useraccounts`
--
ALTER TABLE `useraccounts`
  MODIFY `remark` tinyint(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `witness_testium_tbl`
--
ALTER TABLE `witness_testium_tbl`
  MODIFY `w_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
