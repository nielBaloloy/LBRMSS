-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 21, 2025 at 07:24 PM
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
-- Table structure for table `lbrmss_event_table_main`
--

CREATE TABLE `lbrmss_event_table_main` (
  `event_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL COMMENT 'src : lbrmss_event_services | etype_id',
  `seminar_id` int(5) NOT NULL COMMENT 'src:lbrmss_seminar_event',
  `client` text NOT NULL,
  `date` date NOT NULL,
  `time_from` time NOT NULL,
  `time_to` time NOT NULL,
  `venue_name` text NOT NULL,
  `duration` int(5) NOT NULL,
  `type` int(1) NOT NULL COMMENT '1=mass 2 special',
  `days` int(2) NOT NULL,
  `venue_type` tinyint(1) NOT NULL COMMENT '1 =church 2=pastoral center 3 =outside',
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

INSERT INTO `lbrmss_event_table_main` (`event_id`, `service_id`, `seminar_id`, `client`, `date`, `time_from`, `time_to`, `venue_name`, `duration`, `type`, `days`, `venue_type`, `priest_assigned_id`, `event_progress`, `requirement_status`, `created_at`, `created_by`, `remark`) VALUES
(1, 1, 1, 'lbrmss client', '2025-02-19', '09:23:09', '10:23:09', 'Saint Raphael Church', 60, 0, 1, 1, 1, 0, 1, '2025-02-21 03:23:09', 1, 1),
(2, 1, 1, 'across media', '2025-02-26', '09:00:00', '11:00:00', 'St Raphael Church', 120, 1, 1, 1, 1, 1, 0, '2025-02-21 17:12:29', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lbrmss_event_table_main`
--
ALTER TABLE `lbrmss_event_table_main`
  ADD PRIMARY KEY (`event_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lbrmss_event_table_main`
--
ALTER TABLE `lbrmss_event_table_main`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
