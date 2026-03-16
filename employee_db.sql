-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 14, 2025 at 08:15 AM
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
-- Database: `employee_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_department`
--

CREATE TABLE `tbl_department` (
  `tbl_department_id` int(11) NOT NULL,
  `department` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_department`
--

INSERT INTO `tbl_department` (`tbl_department_id`, `department`, `date_added`) VALUES
(2, 'IT Department', '2025-01-13 15:25:01');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_designation`
--

CREATE TABLE `tbl_designation` (
  `tbl_designation_id` int(11) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_designation`
--

INSERT INTO `tbl_designation` (`tbl_designation_id`, `designation`, `date_added`) VALUES
(2, 'System Administrator', '2025-01-13 15:25:12');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_employee`
--

CREATE TABLE `tbl_employee` (
  `tbl_employee_id` int(11) NOT NULL,
  `tbl_department_id` int(11) NOT NULL,
  `tbl_designation_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `birthday` date NOT NULL,
  `gender` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `salary` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `employee_type` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_employee`
--

INSERT INTO `tbl_employee` (`tbl_employee_id`, `tbl_department_id`, `tbl_designation_id`, `name`, `birthday`, `gender`, `phone`, `email`, `address`, `salary`, `start_date`, `employee_type`, `status`, `date_added`) VALUES
(1, 2, 2, 'Lorem Ipsum', '2021-07-07', 'Male', '09129002891', 'lorem@gmail.com', 'Canada', 12000, '2025-01-13', 'Full-time', 1, '2025-01-13 14:49:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_department`
--
ALTER TABLE `tbl_department`
  ADD PRIMARY KEY (`tbl_department_id`);

--
-- Indexes for table `tbl_designation`
--
ALTER TABLE `tbl_designation`
  ADD PRIMARY KEY (`tbl_designation_id`);

--
-- Indexes for table `tbl_employee`
--
ALTER TABLE `tbl_employee`
  ADD PRIMARY KEY (`tbl_employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_department`
--
ALTER TABLE `tbl_department`
  MODIFY `tbl_department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_designation`
--
ALTER TABLE `tbl_designation`
  MODIFY `tbl_designation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_employee`
--
ALTER TABLE `tbl_employee`
  MODIFY `tbl_employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
