-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 18, 2026 at 11:58 AM
-- Server version: 11.8.8-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u414500830_sitetrack`
--

-- --------------------------------------------------------

--
-- Table structure for table `assign_materials`
--

CREATE TABLE `assign_materials` (
  `amaterial_id` int(11) NOT NULL,
  `amaterial_date` date NOT NULL,
  `amaterial_project` int(11) NOT NULL,
  `amaterial_category` int(11) NOT NULL,
  `amaterial_material` int(11) NOT NULL,
  `amaterial_unit` int(11) NOT NULL,
  `amaterial_qty` varchar(255) DEFAULT NULL,
  `amaterial_added_on` datetime NOT NULL,
  `amaterial_added_by` int(11) NOT NULL,
  `amaterial_updated_on` datetime NOT NULL,
  `amaterial_updated_by` int(11) NOT NULL,
  `amaterial_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assign_materials`
--

INSERT INTO `assign_materials` (`amaterial_id`, `amaterial_date`, `amaterial_project`, `amaterial_category`, `amaterial_material`, `amaterial_unit`, `amaterial_qty`, `amaterial_added_on`, `amaterial_added_by`, `amaterial_updated_on`, `amaterial_updated_by`, `amaterial_status`) VALUES
(1, '2026-08-02', 1, 7, 8, 8, '3', '2026-08-02 17:55:12', 1, '2026-08-02 17:55:12', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(10) UNSIGNED NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_added_on` datetime NOT NULL,
  `category_added_by` int(11) NOT NULL,
  `category_updated_on` datetime NOT NULL,
  `category_updated_by` int(11) NOT NULL,
  `category_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `category_added_on`, `category_added_by`, `category_updated_on`, `category_updated_by`, `category_status`) VALUES
(1, 'Masonry', '2026-07-09 08:06:58', 1, '2026-07-09 08:06:58', 1, 1),
(2, 'Painting', '2026-07-09 08:07:12', 1, '2026-07-09 08:07:12', 1, 1),
(3, 'Centering/Shuttering', '2026-07-09 08:07:25', 1, '2026-07-09 08:07:25', 1, 1),
(4, 'Plumbing', '2026-07-09 08:07:33', 1, '2026-07-09 08:07:33', 1, 1),
(5, 'Electrical', '2026-07-09 08:07:41', 1, '2026-07-09 08:07:41', 1, 1),
(6, 'Carpentry', '2026-07-09 08:07:48', 1, '2026-07-09 08:07:48', 1, 1),
(7, 'RCC/Concrete', '2026-07-09 08:07:57', 1, '2026-07-09 08:08:11', 1, 1),
(8, 'Flooring/Tiling', '2026-07-09 08:08:44', 1, '2026-07-09 08:08:44', 1, 1),
(9, 'Plastering', '2026-07-09 08:08:52', 1, '2026-07-09 08:08:52', 1, 1),
(10, 'Waterproofing', '2026-07-09 08:08:59', 1, '2026-07-09 08:08:59', 1, 1),
(11, 'Steel/Reinforcement', '2026-07-09 08:09:11', 1, '2026-07-09 08:09:11', 1, 1),
(12, 'Finishing', '2026-07-09 08:09:19', 1, '2026-07-09 08:09:19', 1, 1),
(13, 'Earthwork', '2026-07-09 08:09:28', 1, '2026-07-09 08:09:28', 1, 1),
(14, 'UPVC WINDOWS', '2026-08-07 10:02:01', 13, '2026-08-07 10:02:01', 13, 1);

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `client_id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `client_email` varchar(255) NOT NULL,
  `client_phone` varchar(255) NOT NULL,
  `client_address` text NOT NULL,
  `client_added_on` datetime NOT NULL,
  `client_added_by` int(11) NOT NULL,
  `client_updated_on` datetime NOT NULL,
  `client_updated_by` int(11) NOT NULL,
  `client_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`client_id`, `client_name`, `client_email`, `client_phone`, `client_address`, `client_added_on`, `client_added_by`, `client_updated_on`, `client_updated_by`, `client_status`) VALUES
(1, 'Rama Developers', 'ramadevelopers@gmail.com', '7848564820', 'Hyderabad, India', '2026-07-12 11:56:06', 1, '2026-07-12 11:57:23', 1, 1),
(2, 'Metro Builders', 'metrobuilders@gmail.com', '9845152302', 'Bengaluru, India', '2026-07-12 11:56:39', 1, '2026-07-12 11:56:39', 1, 1),
(3, 'VP Infrastructure', 'vpinfrastructure@gmail.com', '7564153250', 'Vijayawada, India', '2026-07-12 11:57:10', 1, '2026-07-12 11:57:10', 1, 1),
(4, 'MR. RAMANJANEYULU', 'planning@aashishinfra.com', '9999666633', 'Hyderabad', '2026-08-07 09:58:11', 13, '2026-08-07 09:58:11', 13, 1),
(5, 'QURESHI', 'planning@aashishinfragroup.com', '8374722942', 'Hyderabad', '2026-08-11 18:24:50', 13, '2026-08-11 18:26:03', 13, 1);

-- --------------------------------------------------------

--
-- Table structure for table `daily_progress`
--

CREATE TABLE `daily_progress` (
  `dprogress_id` int(11) NOT NULL,
  `dprogress_date` date NOT NULL,
  `dprogress_project` int(11) NOT NULL,
  `dprogress_category` int(11) NOT NULL,
  `dprogress_description` varchar(255) NOT NULL,
  `dprogress_boq` int(11) NOT NULL,
  `dprogress_qty` varchar(255) NOT NULL,
  `dprogress_pdone` varchar(255) NOT NULL,
  `dprogress_location` varchar(255) NOT NULL,
  `dprogress_subcontractor` int(11) NOT NULL,
  `dprogress_incharge` int(11) NOT NULL,
  `dprogress_remarks` text DEFAULT NULL,
  `dprogress_cforward` int(11) NOT NULL,
  `dprogress_added_on` datetime NOT NULL,
  `dprogress_added_by` int(11) NOT NULL,
  `dprogress_updated_on` datetime NOT NULL,
  `dprogress_updated_by` int(11) NOT NULL,
  `dprogress_status` int(11) NOT NULL,
  `dprogress_trash` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_progress`
--

INSERT INTO `daily_progress` (`dprogress_id`, `dprogress_date`, `dprogress_project`, `dprogress_category`, `dprogress_description`, `dprogress_boq`, `dprogress_qty`, `dprogress_pdone`, `dprogress_location`, `dprogress_subcontractor`, `dprogress_incharge`, `dprogress_remarks`, `dprogress_cforward`, `dprogress_added_on`, `dprogress_added_by`, `dprogress_updated_on`, `dprogress_updated_by`, `dprogress_status`, `dprogress_trash`) VALUES
(1, '2026-07-12', 1, 3, 'RCC Slab 2nd Floor', 9, '28', '75', '3rd Floor', 1, 8, 'Pump breakdown 1.5hrs', 1, '2026-07-12 13:44:04', 1, '2026-07-12 13:55:46', 1, 2, 1),
(2, '2026-07-13', 2, 1, 'Brickwork South Wing', 11, '45', '100', '2nd Floor', 3, 7, NULL, 2, '2026-07-12 13:46:34', 1, '2026-07-12 13:51:16', 1, 1, 1),
(3, '2026-08-06', 1, 3, 'Centring & Shuttering for footings', 8, '0.4', '57', 'Hafeezpet', 4, 14, 'Due to material Shortage', 1, '2026-08-06 11:08:29', 13, '2026-08-07 12:42:01', 14, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `daily_works`
--

CREATE TABLE `daily_works` (
  `dwork_id` int(11) NOT NULL,
  `dwork_date` date NOT NULL,
  `dwork_project` int(11) NOT NULL,
  `dwork_description` varchar(255) NOT NULL,
  `dwork_location` varchar(255) NOT NULL,
  `dwork_qty` varchar(255) NOT NULL,
  `dwork_category` int(11) NOT NULL,
  `dwork_subcontractor` int(11) NOT NULL,
  `dwork_incharge` int(11) NOT NULL,
  `dwork_remarks` text DEFAULT NULL,
  `dwork_cforward` int(11) NOT NULL,
  `dwork_added_on` datetime NOT NULL,
  `dwork_added_by` int(11) NOT NULL,
  `dwork_updated_on` datetime NOT NULL,
  `dwork_updated_by` int(11) NOT NULL,
  `dwork_status` int(11) NOT NULL,
  `dwork_trash` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_works`
--

INSERT INTO `daily_works` (`dwork_id`, `dwork_date`, `dwork_project`, `dwork_description`, `dwork_location`, `dwork_qty`, `dwork_category`, `dwork_subcontractor`, `dwork_incharge`, `dwork_remarks`, `dwork_cforward`, `dwork_added_on`, `dwork_added_by`, `dwork_updated_on`, `dwork_updated_by`, `dwork_status`, `dwork_trash`) VALUES
(1, '2026-07-12', 1, 'Bar bending for columns', '3rd Floor', '4.2 MT', 1, 1, 8, 'Fe500 grade', 2, '2026-07-12 12:58:15', 1, '2026-07-12 12:58:15', 1, 1, 1),
(2, '2026-07-13', 2, 'Brickwork South Wing 2nd floor', '2nd Floor', '95 Sqm', 1, 3, 7, 'CM 1:6', 1, '2026-07-12 12:59:11', 1, '2026-07-12 13:05:40', 1, 2, 1),
(3, '2026-07-11', 1, 'RCC Column Casting Grid C1-C5', '3rd Floor', '38 Cum', 11, 4, 8, 'M30 grade', 1, '2026-07-12 13:00:13', 1, '2026-08-05 22:26:11', 14, 1, 1),
(4, '2026-08-07', 1, 'retaining wall shuttering work', 'dhone u. kothapalli', '180 cft', 3, 1, 14, 'Not found', 1, '2026-08-06 11:04:32', 13, '2026-08-07 12:11:19', 14, 5, 1),
(5, '2026-08-11', 4, 'BRICK WORK DPC WORK IS IN PROGRESS', '1st floor', '100', 12, 5, 12, 'INPROGRESS', 1, '2026-08-11 18:35:04', 13, '2026-08-11 18:35:04', 13, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `indents`
--

CREATE TABLE `indents` (
  `indent_id` int(11) NOT NULL,
  `indent_date` date NOT NULL,
  `indent_project` int(11) NOT NULL,
  `indent_urgency` int(11) NOT NULL,
  `indent_engineer` int(11) NOT NULL,
  `indent_notes` text DEFAULT NULL,
  `indent_napproval` text DEFAULT NULL,
  `indent_added_on` datetime NOT NULL,
  `indent_added_by` int(11) NOT NULL,
  `indent_updated_on` datetime NOT NULL,
  `indent_updated_by` int(11) NOT NULL,
  `indent_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `indents`
--

INSERT INTO `indents` (`indent_id`, `indent_date`, `indent_project`, `indent_urgency`, `indent_engineer`, `indent_notes`, `indent_napproval`, `indent_added_on`, `indent_added_by`, `indent_updated_on`, `indent_updated_by`, `indent_status`) VALUES
(1, '2026-07-12', 1, 3, 9, 'Road layer 2 urgent', 'Critical path road blocked without this', '2026-07-12 18:05:22', 1, '2026-07-29 15:42:47', 1, 3),
(2, '2026-07-12', 2, 1, 3, 'Internal walls 2nd floor', 'Painting scheduled 20th — please approve', '2026-07-12 18:07:11', 1, '2026-07-26 22:18:41', 1, 1),
(3, '2026-07-12', 2, 2, 9, 'Slab work blocked', 'Required urgently for slab pour', '2026-07-12 18:09:33', 1, '2026-07-26 22:18:11', 1, 1),
(4, '2026-08-06', 1, 2, 9, 'Please approve sir', '200000', '2026-08-06 11:12:46', 13, '2026-08-06 11:12:46', 13, 1);

-- --------------------------------------------------------

--
-- Table structure for table `indent_items`
--

CREATE TABLE `indent_items` (
  `iitem_id` int(11) NOT NULL,
  `iitem_indent` int(11) NOT NULL,
  `iitem_item` int(11) NOT NULL,
  `iitem_category` int(11) NOT NULL,
  `iitem_size` varchar(255) NOT NULL,
  `iitem_qty` varchar(255) NOT NULL,
  `iitem_unit` int(11) NOT NULL,
  `iitem_added_on` datetime NOT NULL,
  `iitem_added_by` int(11) NOT NULL,
  `iitem_updated_on` datetime NOT NULL,
  `iitem_updated_by` int(11) NOT NULL,
  `iitem_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `indent_items`
--

INSERT INTO `indent_items` (`iitem_id`, `iitem_indent`, `iitem_item`, `iitem_category`, `iitem_size`, `iitem_qty`, `iitem_unit`, `iitem_added_on`, `iitem_added_by`, `iitem_updated_on`, `iitem_updated_by`, `iitem_status`) VALUES
(8, 3, 1, 1, '1.5mm', '400', 5, '2026-07-26 22:18:11', 1, '2026-07-26 22:18:11', 1, 1),
(9, 3, 3, 3, '1mm', '20', 1, '2026-07-26 22:18:11', 1, '2026-07-29 16:13:06', 1, 2),
(10, 2, 8, 2, '1ltr', '80', 8, '2026-07-26 22:18:41', 1, '2026-07-26 22:18:41', 1, 1),
(11, 1, 4, 13, '1m', '150', 4, '2026-07-26 22:19:26', 1, '2026-07-26 22:19:26', 1, 1),
(12, 4, 5, 12, '9\"X6\"X12\"', '700', 6, '2026-08-06 11:12:46', 13, '2026-08-06 11:12:46', 13, 1);

-- --------------------------------------------------------

--
-- Table structure for table `indent_quotations`
--

CREATE TABLE `indent_quotations` (
  `iquote_id` int(11) NOT NULL,
  `iquote_indent` int(11) NOT NULL,
  `iquote_vendor` int(11) NOT NULL,
  `iquote_rate` varchar(255) NOT NULL,
  `iquote_total` varchar(255) NOT NULL,
  `iquote_delivery` varchar(255) NOT NULL,
  `iquote_notes` text DEFAULT NULL,
  `iquote_quotation` varchar(255) DEFAULT NULL,
  `iquote_added_on` datetime NOT NULL,
  `iquote_added_by` int(11) NOT NULL,
  `iquote_updated_on` datetime NOT NULL,
  `iquote_updated_by` int(11) NOT NULL,
  `iquote_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `indent_quotations`
--

INSERT INTO `indent_quotations` (`iquote_id`, `iquote_indent`, `iquote_vendor`, `iquote_rate`, `iquote_total`, `iquote_delivery`, `iquote_notes`, `iquote_quotation`, `iquote_added_on`, `iquote_added_by`, `iquote_updated_on`, `iquote_updated_by`, `iquote_status`) VALUES
(1, 1, 2, '5000', '126000', '1 Day', 'Tomorrow Received', 'zVMZclI3EwbS1FymgmqdGwQbVy83Q255kf4aZC0g.pdf', '2026-07-12 18:46:16', 1, '2026-07-12 18:46:16', 1, 2),
(2, 1, 1, '5500', '132000', '2 Days', NULL, NULL, '2026-07-12 18:52:37', 1, '2026-07-12 18:52:37', 1, 1),
(3, 1, 3, '5750', '134500', '2 Days', NULL, NULL, '2026-07-12 18:53:48', 1, '2026-07-12 18:53:48', 1, 1),
(4, 4, 3, '30', '21000', '2 DAYS', 'It will be arrived by 08-08-26', NULL, '2026-08-06 11:39:30', 11, '2026-08-06 11:39:30', 11, 1);

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--

CREATE TABLE `issues` (
  `issue_id` int(11) NOT NULL,
  `issue_date` date NOT NULL,
  `issue_project` int(11) NOT NULL,
  `issue_title` varchar(255) NOT NULL,
  `issue_description` text NOT NULL,
  `issue_category` int(11) DEFAULT NULL,
  `issue_priority` int(11) DEFAULT NULL,
  `issue_assign` int(11) NOT NULL,
  `issue_remarks` text DEFAULT NULL,
  `issue_image` varchar(255) DEFAULT NULL,
  `issue_sremarks` text DEFAULT NULL,
  `issue_added_on` datetime NOT NULL,
  `issue_added_by` int(11) NOT NULL,
  `issue_updated_on` datetime NOT NULL,
  `issue_updated_by` int(11) NOT NULL,
  `issue_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issues`
--

INSERT INTO `issues` (`issue_id`, `issue_date`, `issue_project`, `issue_title`, `issue_description`, `issue_category`, `issue_priority`, `issue_assign`, `issue_remarks`, `issue_image`, `issue_sremarks`, `issue_added_on`, `issue_added_by`, `issue_updated_on`, `issue_updated_by`, `issue_status`) VALUES
(1, '2026-07-12', 1, 'Labour shortage', 'Only 5 bar benders attended vs 12 required.', 4, 2, 3, NULL, NULL, 'Test Remarks', '2026-07-12 23:34:37', 1, '2026-07-29 17:21:28', 1, 2),
(2, '2026-07-12', 1, 'Brick quality issue', '2000 nos bricks rejected - high water absorption.', 2, 3, 9, NULL, NULL, NULL, '2026-07-12 23:35:23', 1, '2026-07-12 23:35:23', 1, 1),
(3, '2026-07-29', 3, 'Labour shortage', 'Only 4 bar benders attended vs 10 required.', 4, 4, 10, 'Very Urgent', 'AsEOMv1Sa9luK8wV5LwKmxZYScrRd9RPBMQlhsGe.jpg', NULL, '2026-07-29 17:09:44', 1, '2026-07-29 17:12:28', 1, 1),
(4, '2026-08-06', 4, 'Labour shortage', 'Due to labour shortage brickwork & Plastering & Painting delayed', 4, 3, 3, 'Please rectify immediately', NULL, NULL, '2026-08-06 11:17:37', 13, '2026-08-06 11:17:37', 13, 1);

-- --------------------------------------------------------

--
-- Table structure for table `issue_category`
--

CREATE TABLE `issue_category` (
  `icategory_id` int(10) UNSIGNED NOT NULL,
  `icategory_name` varchar(255) NOT NULL,
  `icategory_added_on` datetime NOT NULL,
  `icategory_added_by` int(11) NOT NULL,
  `icategory_updated_on` datetime NOT NULL,
  `icategory_updated_by` int(11) NOT NULL,
  `icategory_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `issue_category`
--

INSERT INTO `issue_category` (`icategory_id`, `icategory_name`, `icategory_added_on`, `icategory_added_by`, `icategory_updated_on`, `icategory_updated_by`, `icategory_status`) VALUES
(1, 'Equipment', '2026-07-12 23:31:40', 1, '2026-07-12 23:31:40', 1, 1),
(2, 'Material', '2026-07-12 23:31:50', 1, '2026-07-12 23:31:50', 1, 1),
(3, 'Weather', '2026-07-12 23:32:00', 1, '2026-07-12 23:32:00', 1, 1),
(4, 'Labour', '2026-07-12 23:32:09', 1, '2026-07-12 23:32:09', 1, 1),
(5, 'Quality', '2026-07-12 23:32:20', 1, '2026-07-12 23:32:20', 1, 1),
(6, 'Design', '2026-07-12 23:32:29', 1, '2026-07-12 23:32:29', 1, 1),
(7, 'Safety', '2026-07-12 23:32:39', 1, '2026-07-12 23:32:39', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `labour_trackers`
--

CREATE TABLE `labour_trackers` (
  `ltracker_id` int(11) NOT NULL,
  `ltracker_date` date NOT NULL,
  `ltracker_project` int(11) NOT NULL,
  `ltracker_subcontractor` int(11) NOT NULL,
  `ltracker_incharge` int(11) NOT NULL,
  `ltracker_shift` int(11) NOT NULL,
  `ltracker_remarks` text DEFAULT NULL,
  `ltracker_in` time NOT NULL,
  `ltracker_out` time NOT NULL,
  `ltracker_thours` varchar(255) DEFAULT NULL,
  `ltracker_total` varchar(255) NOT NULL,
  `ltracker_added_on` datetime NOT NULL,
  `ltracker_added_by` int(11) NOT NULL,
  `ltracker_updated_on` datetime NOT NULL,
  `ltracker_updated_by` int(11) NOT NULL,
  `ltracker_status` int(11) NOT NULL,
  `ltracker_trash` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `labour_trackers`
--

INSERT INTO `labour_trackers` (`ltracker_id`, `ltracker_date`, `ltracker_project`, `ltracker_subcontractor`, `ltracker_incharge`, `ltracker_shift`, `ltracker_remarks`, `ltracker_in`, `ltracker_out`, `ltracker_thours`, `ltracker_total`, `ltracker_added_on`, `ltracker_added_by`, `ltracker_updated_on`, `ltracker_updated_by`, `ltracker_status`, `ltracker_trash`) VALUES
(1, '2026-07-13', 1, 1, 8, 1, 'Test Remarks', '08:00:00', '17:00:00', '9.00', '11500', '2026-07-12 21:49:07', 1, '2026-07-13 08:58:27', 1, 1, 1),
(2, '2026-07-13', 2, 3, 8, 1, NULL, '09:00:00', '16:00:00', '7.00', '2600', '2026-07-12 23:21:42', 1, '2026-07-13 09:04:14', 1, 1, 1),
(3, '2026-08-06', 4, 4, 14, 1, NULL, '08:00:00', '17:00:00', '9.00', '3500', '2026-08-06 11:14:55', 13, '2026-08-06 11:14:55', 13, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `labour_workers`
--

CREATE TABLE `labour_workers` (
  `lworker_id` int(11) NOT NULL,
  `lworker_tracker` int(11) NOT NULL,
  `lworker_category` int(11) NOT NULL,
  `lworker_skill` int(11) NOT NULL,
  `lworker_male` varchar(255) NOT NULL,
  `lworker_female` varchar(255) NOT NULL,
  `lworker_wage` varchar(255) DEFAULT NULL,
  `lworker_total` varchar(255) NOT NULL,
  `lworker_added_on` datetime NOT NULL,
  `lworker_added_by` int(11) NOT NULL,
  `lworker_updated_on` datetime NOT NULL,
  `lworker_updated_by` int(11) NOT NULL,
  `lworker_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `labour_workers`
--

INSERT INTO `labour_workers` (`lworker_id`, `lworker_tracker`, `lworker_category`, `lworker_skill`, `lworker_male`, `lworker_female`, `lworker_wage`, `lworker_total`, `lworker_added_on`, `lworker_added_by`, `lworker_updated_on`, `lworker_updated_by`, `lworker_status`) VALUES
(10, 1, 6, 1, '5', '3', '1000', '8000', '2026-07-13 08:58:28', 1, '2026-07-13 08:58:28', 1, 1),
(11, 1, 3, 2, '2', '2', '500', '2000', '2026-07-13 08:58:28', 1, '2026-07-13 08:58:28', 1, 1),
(12, 1, 5, 3, '1', '2', '500', '1500', '2026-07-13 08:58:28', 1, '2026-07-13 08:58:28', 1, 1),
(13, 2, 5, 1, '2', '3', '500', '2500', '2026-07-13 09:04:14', 1, '2026-07-13 09:04:14', 1, 1),
(14, 2, 13, 1, '1', '0', '100', '100', '2026-07-13 09:04:14', 1, '2026-07-13 09:04:14', 1, 1),
(15, 3, 12, 1, '3', '2', '700', '3500', '2026-08-06 11:14:55', 13, '2026-08-06 11:14:55', 13, 1);

-- --------------------------------------------------------

--
-- Table structure for table `main_modules`
--

CREATE TABLE `main_modules` (
  `mmodule_id` int(11) NOT NULL,
  `mmodule_name` varchar(255) NOT NULL,
  `mmodule_order` int(11) NOT NULL,
  `mmodule_added_on` datetime NOT NULL,
  `mmodule_added_by` int(11) NOT NULL,
  `mmodule_updated_on` datetime NOT NULL,
  `mmodule_updated_by` int(11) NOT NULL,
  `mmodule_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `main_modules`
--

INSERT INTO `main_modules` (`mmodule_id`, `mmodule_name`, `mmodule_order`, `mmodule_added_on`, `mmodule_added_by`, `mmodule_updated_on`, `mmodule_updated_by`, `mmodule_status`) VALUES
(1, 'Projects', 1, '2026-07-13 11:17:43', 1, '2026-07-13 11:17:43', 1, 1),
(2, 'Daily Works', 2, '2026-07-13 11:17:43', 1, '2026-07-13 11:17:43', 1, 1),
(3, 'Daily Progress', 3, '2026-07-13 11:18:09', 1, '2026-07-13 11:18:09', 1, 1),
(4, 'BOQ Tracker', 4, '2026-07-13 11:18:09', 1, '2026-07-13 11:18:09', 1, 1),
(5, 'Materials', 5, '2026-07-13 11:18:31', 1, '2026-07-13 11:18:31', 1, 1),
(6, 'Indents', 6, '2026-07-13 11:18:31', 1, '2026-07-13 11:18:31', 1, 1),
(7, 'Labour Tracker', 7, '2026-07-13 11:18:53', 1, '2026-07-13 11:18:53', 1, 1),
(8, 'Issues', 8, '2026-07-13 11:18:53', 1, '2026-07-13 11:18:53', 1, 1),
(9, 'Ledger', 9, '2026-07-13 11:19:14', 1, '2026-07-13 11:19:14', 1, 1),
(10, 'Vendors', 10, '2026-07-13 11:19:14', 1, '2026-07-13 11:19:14', 1, 1),
(11, 'Subcontractors', 11, '2026-07-13 11:19:33', 1, '2026-07-13 11:19:33', 1, 1),
(12, 'Work Category', 12, '2026-07-13 11:19:33', 1, '2026-07-13 11:19:33', 1, 1),
(13, 'Units', 13, '2026-07-13 11:19:55', 1, '2026-07-13 11:19:55', 1, 1),
(14, 'QMS Reports', 14, '2026-07-13 11:19:55', 1, '2026-07-13 11:19:55', 1, 1),
(15, 'Roles & Permissions', 15, '2026-07-13 11:20:15', 1, '2026-07-13 11:20:15', 1, 1),
(16, 'Settings', 16, '2026-07-13 12:06:11', 1, '2026-07-13 12:06:11', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `material_id` int(11) NOT NULL,
  `material_category` int(11) NOT NULL,
  `material_name` varchar(255) NOT NULL,
  `material_unit` int(11) NOT NULL,
  `material_hsn` varchar(255) DEFAULT NULL,
  `material_added_on` datetime NOT NULL,
  `material_added_by` int(11) NOT NULL,
  `material_updated_on` datetime NOT NULL,
  `material_updated_by` int(11) NOT NULL,
  `material_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`material_id`, `material_category`, `material_name`, `material_unit`, `material_hsn`, `material_added_on`, `material_added_by`, `material_updated_on`, `material_updated_by`, `material_status`) VALUES
(1, 1, 'Cement OPC 53', 5, 'C123456', '2026-07-12 17:45:35', 1, '2026-08-02 14:32:23', 1, 1),
(2, 2, 'Steel TMT Fe500', 4, 'ST534543', '2026-07-12 17:45:58', 1, '2026-08-02 14:32:12', 1, 1),
(3, 3, 'River Sand', 1, '222', '2026-07-12 17:50:57', 1, '2026-08-11 16:47:12', 11, 1),
(4, 3, 'Aggregate 20mm', 1, 'A543', '2026-07-12 17:51:16', 1, '2026-07-12 17:51:16', 1, 1),
(5, 4, 'Bricks 9 inch', 6, 'BR43342', '2026-07-12 17:51:33', 1, '2026-08-02 14:31:47', 1, 1),
(6, 5, 'Electrical Wire', 3, 'EW43', '2026-07-12 17:51:53', 1, '2026-08-02 14:31:31', 1, 1),
(7, 5, 'Binding Wire', 9, 'BD353', '2026-07-12 17:52:09', 1, '2026-08-02 14:31:12', 1, 0),
(8, 7, 'Acrylic Paint White', 8, 'AP462', '2026-07-12 18:07:38', 1, '2026-08-02 18:35:46', 1, 1),
(9, 1, 'cement', 4, '550P', '2026-08-06 11:35:00', 11, '2026-08-07 12:29:03', 14, 1);

-- --------------------------------------------------------

--
-- Table structure for table `material_category`
--

CREATE TABLE `material_category` (
  `mcategory_id` int(10) UNSIGNED NOT NULL,
  `mcategory_name` varchar(255) NOT NULL,
  `mcategory_added_on` datetime NOT NULL,
  `mcategory_added_by` int(11) NOT NULL,
  `mcategory_updated_on` datetime NOT NULL,
  `mcategory_updated_by` int(11) NOT NULL,
  `mcategory_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_category`
--

INSERT INTO `material_category` (`mcategory_id`, `mcategory_name`, `mcategory_added_on`, `mcategory_added_by`, `mcategory_updated_on`, `mcategory_updated_by`, `mcategory_status`) VALUES
(1, 'Cement', '2026-07-12 11:13:26', 1, '2026-07-12 11:13:26', 1, 1),
(2, 'Steel/TMT', '2026-07-12 11:13:35', 1, '2026-07-12 11:13:35', 1, 1),
(3, 'Sand/Aggregate', '2026-07-12 11:13:43', 1, '2026-07-12 11:13:43', 1, 1),
(4, 'Bricks/Blocks', '2026-07-12 11:13:53', 1, '2026-07-12 11:13:53', 1, 1),
(5, 'Electrical', '2026-07-12 11:14:00', 1, '2026-07-12 11:14:00', 1, 1),
(6, 'Plumbing', '2026-07-12 11:14:07', 1, '2026-07-12 11:14:07', 1, 1),
(7, 'Paint', '2026-07-12 11:14:14', 1, '2026-07-12 11:14:14', 1, 1),
(8, 'Hardware', '2026-07-12 11:14:21', 1, '2026-07-12 11:14:21', 1, 1),
(9, 'Tiles/Marble', '2026-07-12 11:14:29', 1, '2026-07-12 11:14:29', 1, 1),
(10, 'Wood/Ply', '2026-07-12 11:14:37', 1, '2026-07-12 11:14:37', 1, 1),
(11, 'Glass', '2026-07-12 11:14:43', 1, '2026-07-12 11:14:43', 1, 1),
(12, 'Sanitary', '2026-07-12 11:14:49', 1, '2026-07-12 11:14:49', 1, 1),
(13, 'Tools', '2026-07-12 11:14:55', 1, '2026-08-02 18:35:33', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_06_30_100302_create_cache_table', 1),
(6, '2026_06_30_100309_create_sessions_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `module_id` int(11) NOT NULL,
  `module_main` int(11) NOT NULL,
  `module_name` varchar(255) NOT NULL,
  `module_added_on` datetime NOT NULL,
  `module_added_by` int(11) NOT NULL,
  `module_updated_on` datetime NOT NULL,
  `module_updated_by` int(11) NOT NULL,
  `module_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`module_id`, `module_main`, `module_name`, `module_added_on`, `module_added_by`, `module_updated_on`, `module_updated_by`, `module_status`) VALUES
(1, 1, 'Projects', '2026-07-13 11:22:26', 1, '2026-07-13 11:22:26', 1, 1),
(2, 1, 'Clients', '2026-07-13 11:22:37', 1, '2026-07-13 11:22:38', 1, 1),
(3, 2, 'Daily Works', '2026-07-13 11:22:47', 1, '2026-07-13 11:22:47', 1, 1),
(4, 3, 'Daily Progress', '2026-07-13 11:22:47', 1, '2026-07-13 11:22:47', 1, 1),
(5, 4, 'BOQ Tracker', '2026-07-13 11:23:16', 1, '2026-07-13 11:23:16', 1, 1),
(6, 5, 'Orders', '2026-07-13 11:23:16', 1, '2026-07-13 11:23:16', 1, 1),
(7, 5, 'Materials', '2026-07-13 11:23:48', 1, '2026-07-13 11:23:48', 1, 1),
(8, 5, 'Category', '2026-07-13 11:23:48', 1, '2026-07-13 11:23:48', 1, 1),
(9, 6, 'Indents', '2026-07-13 11:24:11', 1, '2026-07-13 11:24:11', 1, 1),
(10, 7, 'Labour Tracker', '2026-07-13 11:24:11', 1, '2026-07-13 11:24:11', 1, 1),
(11, 8, 'Issues', '2026-07-13 11:24:42', 1, '2026-07-13 11:24:42', 1, 1),
(12, 8, 'Category', '2026-07-13 11:24:42', 1, '2026-07-13 11:24:42', 1, 1),
(13, 9, 'Subcontractor', '2026-07-13 11:25:04', 1, '2026-07-13 11:25:04', 1, 1),
(14, 9, 'Vendor', '2026-07-13 11:25:04', 1, '2026-07-13 11:25:04', 1, 1),
(15, 10, 'Vendors', '2026-07-13 11:25:32', 1, '2026-07-13 11:25:32', 1, 1),
(16, 11, 'Subcontractors', '2026-07-13 11:25:32', 1, '2026-07-13 11:25:32', 1, 1),
(17, 12, 'Work Category', '2026-07-13 11:25:55', 1, '2026-07-13 11:25:55', 1, 1),
(18, 13, 'Units', '2026-07-13 11:25:55', 1, '2026-07-13 11:25:55', 1, 1),
(19, 14, 'QMS Reports', '2026-07-13 11:26:17', 1, '2026-07-13 11:26:17', 1, 1),
(20, 15, 'Users', '2026-07-13 11:26:17', 1, '2026-07-13 11:26:17', 1, 1),
(21, 15, 'Roles', '2026-07-13 11:26:39', 1, '2026-07-13 11:26:39', 1, 1),
(22, 16, 'Settings', '2026-07-13 12:06:30', 1, '2026-07-13 12:06:30', 1, 1),
(23, 16, 'Email Settings', '2026-07-13 12:06:30', 1, '2026-07-13 12:06:30', 1, 1),
(24, 5, 'Assign Materials', '2026-07-13 11:23:48', 1, '2026-07-13 11:23:48', 1, 1),
(25, 5, 'Stocks', '2026-07-13 11:23:48', 1, '2026-07-13 11:23:48', 1, 1),
(26, 5, 'Return Materials', '2026-07-13 11:23:48', 1, '2026-07-13 11:23:48', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `module_permissions`
--

CREATE TABLE `module_permissions` (
  `mpermission_id` int(11) NOT NULL,
  `mpermission_module` int(11) NOT NULL,
  `mpermission_name` varchar(255) NOT NULL,
  `mpermission_slug` varchar(255) NOT NULL,
  `mpermission_added_on` datetime NOT NULL,
  `mpermission_added_by` int(11) NOT NULL,
  `mpermission_updated_on` datetime NOT NULL,
  `mpermission_updated_by` int(11) NOT NULL,
  `mpermission_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `module_permissions`
--

INSERT INTO `module_permissions` (`mpermission_id`, `mpermission_module`, `mpermission_name`, `mpermission_slug`, `mpermission_added_on`, `mpermission_added_by`, `mpermission_updated_on`, `mpermission_updated_by`, `mpermission_status`) VALUES
(1, 1, 'Add', 'user.add_project', '2026-07-13 11:28:00', 1, '2026-07-13 11:28:00', 1, 1),
(2, 1, 'List', 'user.projects', '2026-07-13 11:28:00', 1, '2026-07-13 11:28:00', 1, 1),
(3, 1, 'View', 'user.view_project', '2026-07-13 11:29:11', 1, '2026-07-13 11:29:11', 1, 1),
(4, 1, 'Edit', 'user.edit_project', '2026-07-13 11:29:11', 1, '2026-07-13 11:29:11', 1, 1),
(5, 1, 'Status', 'user.project_status', '2026-07-13 11:29:48', 1, '2026-07-13 11:29:48', 1, 1),
(6, 2, 'Add', 'user.add_client', '2026-07-13 11:28:00', 1, '2026-07-13 11:28:00', 1, 1),
(7, 2, 'List', 'user.clients', '2026-07-13 11:28:00', 1, '2026-07-13 11:28:00', 1, 1),
(8, 2, 'Edit', 'user.edit_client', '2026-07-13 11:29:11', 1, '2026-07-13 11:29:11', 1, 1),
(9, 2, 'Status', 'user.client_status', '2026-07-13 11:29:48', 1, '2026-07-13 11:29:48', 1, 1),
(10, 3, 'Add', 'user.add_daily_work', '2026-07-13 11:28:00', 1, '2026-07-13 11:28:00', 1, 1),
(11, 3, 'List', 'user.daily_works', '2026-07-13 11:28:00', 1, '2026-07-13 11:28:00', 1, 1),
(12, 3, 'Download', 'user.works_download_excel', '2026-07-13 11:29:11', 1, '2026-07-13 11:29:11', 1, 1),
(13, 3, 'Edit', 'user.edit_daily_work', '2026-07-13 11:29:11', 1, '2026-07-13 11:29:11', 1, 1),
(14, 3, 'Status', 'user.daily_work_status', '2026-07-13 11:29:48', 1, '2026-07-13 11:29:48', 1, 1),
(15, 4, 'Add', 'user.add_daily_progress', '2026-07-13 11:28:00', 1, '2026-07-13 11:28:00', 1, 1),
(16, 4, 'List', 'user.daily_progress', '2026-07-13 11:28:00', 1, '2026-07-13 11:28:00', 1, 1),
(17, 4, 'Download', 'user.progress_download_excel', '2026-07-13 11:29:11', 1, '2026-07-13 11:29:11', 1, 1),
(18, 4, 'Edit', 'user.edit_daily_progress', '2026-07-13 11:29:11', 1, '2026-07-13 11:29:11', 1, 1),
(19, 4, 'Status', 'user.daily_progress_status', '2026-07-13 11:29:48', 1, '2026-07-13 11:29:48', 1, 1),
(20, 5, 'View', 'user.boq_tracker', '2026-07-13 11:40:20', 1, '2026-07-13 11:40:20', 1, 1),
(21, 5, 'Add Extra Item', 'user.add_boq_item', '2026-07-13 11:40:20', 1, '2026-07-13 11:40:20', 1, 1),
(22, 5, 'Edit Item', 'user.edit_boq_item', '2026-07-13 11:41:01', 1, '2026-07-13 11:41:01', 1, 1),
(23, 6, 'Add', 'user.add_order', '2026-07-13 11:42:31', 1, '2026-07-13 11:42:31', 1, 1),
(24, 6, 'List', 'user.orders', '2026-07-13 11:42:31', 1, '2026-07-13 11:42:31', 1, 1),
(25, 6, 'Edit', 'user.edit_order', '2026-07-13 11:43:19', 1, '2026-07-13 11:43:19', 1, 1),
(26, 6, 'Status', 'user.order_pay_status', '2026-07-13 11:43:19', 1, '2026-07-13 11:43:19', 1, 1),
(27, 6, 'Delivery', 'user.order_delivery', '2026-07-13 11:44:39', 1, '2026-07-13 11:44:39', 1, 1),
(28, 6, 'Add Delivery', 'user.add_order_delivery', '2026-07-13 11:44:39', 1, '2026-07-13 11:44:39', 1, 1),
(29, 7, 'Add', 'user.add_material', '2026-07-13 11:42:31', 1, '2026-07-13 11:42:31', 1, 1),
(30, 7, 'List', 'user.materials', '2026-07-13 11:42:31', 1, '2026-07-13 11:42:31', 1, 1),
(31, 7, 'Edit', 'user.edit_material', '2026-07-13 11:43:19', 1, '2026-07-13 11:43:19', 1, 1),
(32, 7, 'Status', 'user.material_status', '2026-07-13 11:43:19', 1, '2026-07-13 11:43:19', 1, 1),
(33, 8, 'Add', 'user.add_material_category', '2026-07-13 11:42:31', 1, '2026-07-13 11:42:31', 1, 1),
(34, 8, 'List', 'user.material_category', '2026-07-13 11:42:31', 1, '2026-07-13 11:42:31', 1, 1),
(35, 8, 'Edit', 'user.edit_material_category', '2026-07-13 11:43:19', 1, '2026-07-13 11:43:19', 1, 1),
(36, 8, 'Status', 'user.material_category_status', '2026-07-13 11:43:19', 1, '2026-07-13 11:43:19', 1, 1),
(37, 9, 'Add', 'user.add_indent', '2026-07-13 11:48:43', 1, '2026-07-13 11:48:43', 1, 1),
(38, 9, 'List', 'user.indents', '2026-07-13 11:48:43', 1, '2026-07-13 11:48:43', 1, 1),
(39, 9, 'Edit', 'user.edit_indent', '2026-07-13 11:49:28', 1, '2026-07-13 11:49:28', 1, 1),
(40, 9, 'Quotations', 'user.indent_quotations', '2026-07-13 11:49:28', 1, '2026-07-13 11:49:28', 1, 1),
(41, 9, 'Add Quotation', 'user.add_quotation', '2026-07-13 11:50:35', 1, '2026-07-13 11:50:35', 1, 1),
(42, 9, 'Approve Quotation', 'user.status_quotation', '2026-07-13 11:50:35', 1, '2026-07-13 11:50:35', 1, 1),
(43, 9, 'Reject Quotation', 'user.reject_quotations', '2026-07-13 11:51:34', 1, '2026-07-13 11:51:34', 1, 1),
(44, 9, 'Download', 'user.download_indent', '2026-07-13 11:51:34', 1, '2026-07-13 11:51:34', 1, 1),
(45, 10, 'Add', 'user.add_labour_tracker', '2026-07-13 11:48:43', 1, '2026-07-13 11:48:43', 1, 1),
(46, 10, 'List', 'user.labour_trackers', '2026-07-13 11:48:43', 1, '2026-07-13 11:48:43', 1, 1),
(47, 10, 'Edit', 'user.edit_labour_tracker', '2026-07-13 11:49:28', 1, '2026-07-13 11:49:28', 1, 1),
(48, 10, 'Download', 'user.labours_download_excel', '2026-07-13 11:49:28', 1, '2026-07-13 11:49:28', 1, 1),
(49, 11, 'Add', 'user.add_issue', '2026-07-13 11:48:43', 1, '2026-07-13 11:48:43', 1, 1),
(50, 11, 'List', 'user.issues', '2026-07-13 11:48:43', 1, '2026-07-13 11:48:43', 1, 1),
(51, 11, 'Edit', 'user.edit_issue', '2026-07-13 11:49:28', 1, '2026-07-13 11:49:28', 1, 1),
(52, 11, 'Download', 'user.issues_download_excel', '2026-07-13 11:49:28', 1, '2026-07-13 11:49:28', 1, 1),
(53, 11, 'Status', 'user.issue_status', '2026-07-13 11:49:28', 1, '2026-07-13 11:49:28', 1, 1),
(54, 12, 'Add', 'user.add_issue_category', '2026-07-13 11:42:31', 1, '2026-07-13 11:42:31', 1, 1),
(55, 12, 'List', 'user.issue_category', '2026-07-13 11:42:31', 1, '2026-07-13 11:42:31', 1, 1),
(56, 12, 'Edit', 'user.edit_issue_category', '2026-07-13 11:43:19', 1, '2026-07-13 11:43:19', 1, 1),
(57, 12, 'Status', 'user.issue_category_status', '2026-07-13 11:43:19', 1, '2026-07-13 11:43:19', 1, 1),
(58, 13, 'Add', 'user.add_subcontractor_ledger', '2026-07-13 11:42:31', 1, '2026-07-13 11:42:31', 1, 1),
(59, 13, 'List', 'user.subcontractor_ledger', '2026-07-13 11:42:31', 1, '2026-07-13 11:42:31', 1, 1),
(60, 13, 'Download', 'user.subcontractor_ledger_download_excel', '2026-07-13 11:43:19', 1, '2026-07-13 11:43:19', 1, 1),
(61, 14, 'Add', 'user.add_vendor_ledger', '2026-07-13 11:42:31', 1, '2026-07-13 11:42:31', 1, 1),
(62, 14, 'List', 'user.vendor_ledger', '2026-07-13 11:42:31', 1, '2026-07-13 11:42:31', 1, 1),
(63, 14, 'Download', 'user.vendor_ledger_download_excel', '2026-07-13 11:43:19', 1, '2026-07-13 11:43:19', 1, 1),
(64, 15, 'Add', 'user.add_vendor', '2026-07-13 11:48:43', 1, '2026-07-13 11:48:43', 1, 1),
(65, 15, 'List', 'user.vendors', '2026-07-13 11:48:43', 1, '2026-07-13 11:48:43', 1, 1),
(66, 15, 'Edit', 'user.edit_vendor', '2026-07-13 11:49:28', 1, '2026-07-13 11:49:28', 1, 1),
(67, 15, 'Download', 'user.vendors_download_excel', '2026-07-13 11:49:28', 1, '2026-07-13 11:49:28', 1, 1),
(68, 15, 'Status', 'user.vendor_status', '2026-07-13 11:49:28', 1, '2026-07-13 11:49:28', 1, 1),
(69, 16, 'Add', 'user.add_subcontractor', '2026-07-13 11:42:31', 1, '2026-07-13 11:42:31', 1, 1),
(70, 16, 'List', 'user.subcontractors', '2026-07-13 11:42:31', 1, '2026-07-13 11:42:31', 1, 1),
(71, 16, 'Edit', 'user.edit_subcontractor', '2026-07-13 11:43:19', 1, '2026-07-13 11:43:19', 1, 1),
(72, 16, 'Status', 'user.subcontractor_status', '2026-07-13 11:43:19', 1, '2026-07-13 11:43:19', 1, 1),
(73, 17, 'Add', 'user.add_category', '2026-07-13 11:42:31', 1, '2026-07-13 11:42:31', 1, 1),
(74, 17, 'List', 'user.category', '2026-07-13 11:42:31', 1, '2026-07-13 11:42:31', 1, 1),
(75, 17, 'Edit', 'user.edit_category', '2026-07-13 11:43:19', 1, '2026-07-13 11:43:19', 1, 1),
(76, 17, 'Status', 'user.category_status', '2026-07-13 11:43:19', 1, '2026-07-13 11:43:19', 1, 1),
(77, 18, 'Add', 'user.add_unit', '2026-07-13 11:42:31', 1, '2026-07-13 11:42:31', 1, 1),
(78, 18, 'List', 'user.units', '2026-07-13 11:42:31', 1, '2026-07-13 11:42:31', 1, 1),
(79, 18, 'Edit', 'user.edit_unit', '2026-07-13 11:43:19', 1, '2026-07-13 11:43:19', 1, 1),
(80, 18, 'Status', 'user.unit_status', '2026-07-13 11:43:19', 1, '2026-07-13 11:43:19', 1, 1),
(81, 19, 'View', 'user.qms', '2026-07-13 12:03:04', 1, '2026-07-13 12:03:04', 1, 1),
(82, 19, 'Download', 'user.qms_download_excel', '2026-07-13 12:03:04', 1, '2026-07-13 12:03:04', 1, 1),
(83, 20, 'Add', 'user.add_user', '2026-07-13 11:42:31', 1, '2026-07-13 11:42:31', 1, 1),
(84, 20, 'List', 'user.users', '2026-07-13 11:42:31', 1, '2026-07-13 11:42:31', 1, 1),
(85, 20, 'Edit', 'user.edit_user', '2026-07-13 11:43:19', 1, '2026-07-13 11:43:19', 1, 1),
(86, 20, 'Status', 'user.user_status', '2026-07-13 11:43:19', 1, '2026-07-13 11:43:19', 1, 1),
(87, 21, 'List', 'user.roles', '2026-07-13 11:42:31', 1, '2026-07-13 11:42:31', 1, 1),
(88, 21, 'Edit', 'user.edit_role', '2026-07-13 11:43:19', 1, '2026-07-13 11:43:19', 1, 1),
(89, 21, 'Status', 'user.role_status', '2026-07-13 11:43:19', 1, '2026-07-13 11:43:19', 1, 1),
(90, 22, 'View', 'user.settings', '2026-07-13 12:06:59', 1, '2026-07-13 12:06:59', 1, 1),
(91, 23, 'View', 'user.email_config_settings', '2026-07-13 12:06:59', 1, '2026-07-13 12:06:59', 1, 1),
(92, 9, 'Items', 'user.indent_items', '2026-07-13 11:49:28', 1, '2026-07-13 11:49:28', 1, 1),
(93, 9, 'Item Status', 'user.indent_item_status', '2026-07-13 11:49:28', 1, '2026-07-13 11:49:28', 1, 1),
(94, 24, 'Add', 'user.add_assign_material', '2026-07-13 11:42:31', 1, '2026-07-13 11:42:31', 1, 1),
(95, 24, 'List', 'user.assign_materials', '2026-07-13 11:42:31', 1, '2026-07-13 11:42:31', 1, 1),
(96, 25, 'List', 'user.stocks', '2026-07-13 11:42:31', 1, '2026-07-13 11:42:31', 1, 1),
(97, 26, 'Add', 'user.add_return_material', '2026-07-13 11:42:31', 1, '2026-07-13 11:42:31', 1, 1),
(98, 26, 'List', 'user.return_materials', '2026-07-13 11:42:31', 1, '2026-07-13 11:42:31', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_date` date NOT NULL,
  `order_po_no` varchar(255) NOT NULL,
  `order_vendor` int(11) NOT NULL,
  `order_material` int(11) NOT NULL,
  `order_unit` int(11) NOT NULL,
  `order_qty` varchar(255) NOT NULL,
  `order_rate` varchar(255) NOT NULL,
  `order_pay_status` int(11) NOT NULL,
  `order_added_on` datetime NOT NULL,
  `order_added_by` int(11) NOT NULL,
  `order_updated_on` datetime NOT NULL,
  `order_updated_by` int(11) NOT NULL,
  `order_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_date`, `order_po_no`, `order_vendor`, `order_material`, `order_unit`, `order_qty`, `order_rate`, `order_pay_status`, `order_added_on`, `order_added_by`, `order_updated_on`, `order_updated_by`, `order_status`) VALUES
(1, '2026-08-02', 'DS435', 3, 8, 8, '50', '10000', 1, '2026-08-02 14:55:19', 1, '2026-08-02 14:57:53', 1, 1),
(2, '2026-08-02', 'VE4342', 1, 1, 5, '100', '20000', 1, '2026-08-02 16:31:18', 1, '2026-08-02 16:31:18', 1, 1),
(3, '2026-08-06', 'AIP/PO/2026-27/01', 3, 8, 8, '25', '230', 1, '2026-08-06 11:31:27', 11, '2026-08-06 11:31:27', 11, 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_delivery`
--

CREATE TABLE `order_delivery` (
  `odelivery_id` int(11) NOT NULL,
  `odelivery_order` int(11) NOT NULL,
  `odelivery_date` date NOT NULL,
  `odelivery_qty` varchar(255) NOT NULL,
  `odelivery_dc` varchar(255) NOT NULL,
  `odelivery_added_on` datetime NOT NULL,
  `odelivery_added_by` int(11) NOT NULL,
  `odelivery_updated_on` datetime NOT NULL,
  `odelivery_updated_by` int(11) NOT NULL,
  `odelivery_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_delivery`
--

INSERT INTO `order_delivery` (`odelivery_id`, `odelivery_order`, `odelivery_date`, `odelivery_qty`, `odelivery_dc`, `odelivery_added_on`, `odelivery_added_by`, `odelivery_updated_on`, `odelivery_updated_by`, `odelivery_status`) VALUES
(1, 1, '2026-08-02', '10', 'D123', '2026-08-02 16:29:18', 1, '2026-08-02 16:29:18', 1, 1),
(2, 1, '2026-08-02', '5', 'C423', '2026-08-02 16:29:46', 1, '2026-08-02 16:29:46', 1, 1),
(3, 2, '2026-08-02', '10', 'C3576', '2026-08-02 16:31:42', 1, '2026-08-02 16:31:42', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `project_id` int(11) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `project_location` varchar(255) NOT NULL,
  `project_client` int(11) NOT NULL,
  `project_engineer` int(11) NOT NULL,
  `project_start` date NOT NULL,
  `project_end` date NOT NULL,
  `project_budget` varchar(255) NOT NULL,
  `project_added_on` datetime NOT NULL,
  `project_added_by` int(11) NOT NULL,
  `project_updated_on` datetime NOT NULL,
  `project_updated_by` int(11) NOT NULL,
  `project_status` int(11) NOT NULL,
  `project_trash` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`project_id`, `project_name`, `project_location`, `project_client`, `project_engineer`, `project_start`, `project_end`, `project_budget`, `project_added_on`, `project_added_by`, `project_updated_on`, `project_updated_by`, `project_status`, `project_trash`) VALUES
(1, 'Block A - Residential Tower', 'Hyderabad', 1, 9, '2026-07-12', '2026-12-15', '45000000', '2026-07-12 12:02:47', 1, '2026-07-12 12:06:30', 1, 1, 1),
(2, 'Block B - Commercial Complex', 'Bengaluru', 2, 9, '2026-07-20', '2026-12-20', '72000000', '2026-07-12 12:04:05', 1, '2026-07-12 12:04:05', 1, 1, 1),
(3, 'Site C - Township', 'Vijayawada', 3, 3, '2026-07-29', '2027-01-10', '120000000', '2026-07-12 12:04:59', 1, '2026-07-13 08:47:57', 1, 1, 1),
(4, 'Residential Building', 'Hafeezpet', 3, 9, '2026-01-01', '2026-12-31', '24076815', '2026-08-06 10:55:10', 13, '2026-08-06 10:55:10', 13, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `project_boqs`
--

CREATE TABLE `project_boqs` (
  `pboq_id` int(11) NOT NULL,
  `pboq_project` int(11) NOT NULL,
  `pboq_item` varchar(255) NOT NULL,
  `pboq_unit` varchar(255) NOT NULL,
  `pboq_qty` varchar(255) NOT NULL,
  `pboq_rate` varchar(255) NOT NULL,
  `pboq_amount` varchar(255) NOT NULL,
  `pboq_remarks` text DEFAULT NULL,
  `pboq_extra` int(11) NOT NULL,
  `pboq_added_on` datetime NOT NULL,
  `pboq_added_by` int(11) NOT NULL,
  `pboq_updated_on` datetime NOT NULL,
  `pboq_updated_by` int(11) NOT NULL,
  `pboq_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_boqs`
--

INSERT INTO `project_boqs` (`pboq_id`, `pboq_project`, `pboq_item`, `pboq_unit`, `pboq_qty`, `pboq_rate`, `pboq_amount`, `pboq_remarks`, `pboq_extra`, `pboq_added_on`, `pboq_added_by`, `pboq_updated_on`, `pboq_updated_by`, `pboq_status`) VALUES
(8, 1, 'Earthwork Excavation', 'Cum', '500', '280', '140000', 'Test Remarks', 0, '2026-07-12 12:02:48', 1, '2026-07-12 16:07:44', 1, 1),
(9, 1, 'RCC Slab', 'Cum', '85', '7800', '663000', NULL, 0, '2026-07-12 12:02:48', 1, '2026-07-12 16:07:10', 1, 1),
(10, 1, 'Brickwork', 'Sqm', '800', '850', '680000', NULL, 0, '2026-07-12 12:02:48', 1, '2026-07-12 12:02:48', 1, 1),
(11, 2, 'Earthwork Excavation', 'Cum', '500', '280', '140000', 'Test Remarks', 0, '2026-07-12 12:04:05', 1, '2026-07-12 12:04:05', 1, 1),
(12, 2, 'RCC Slab', 'Cum', '85', '7800', '663000', NULL, 0, '2026-07-12 12:04:05', 1, '2026-07-12 12:04:05', 1, 1),
(13, 3, 'RCC Slab', 'Cum', '85', '7800', '663000', NULL, 0, '2026-07-12 12:04:59', 1, '2026-07-12 12:04:59', 1, 1),
(14, 3, 'Brickwork', 'Sqm', '800', '850', '680000', NULL, 0, '2026-07-12 12:04:59', 1, '2026-07-12 16:06:34', 1, 1),
(15, 1, 'Cement', 'Bag', '10', '500', '5000', 'Cement Remarks', 1, '2026-07-12 16:38:44', 1, '2026-07-12 16:39:11', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `return_materials`
--

CREATE TABLE `return_materials` (
  `rmaterial_id` int(11) NOT NULL,
  `rmaterial_date` date NOT NULL,
  `rmaterial_project` int(11) NOT NULL,
  `rmaterial_category` int(11) NOT NULL,
  `rmaterial_material` int(11) NOT NULL,
  `rmaterial_unit` int(11) NOT NULL,
  `rmaterial_qty` varchar(255) NOT NULL,
  `rmaterial_added_on` datetime NOT NULL,
  `rmaterial_added_by` int(11) NOT NULL,
  `rmaterial_updated_on` datetime NOT NULL,
  `rmaterial_updated_by` int(11) NOT NULL,
  `rmaterial_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `return_materials`
--

INSERT INTO `return_materials` (`rmaterial_id`, `rmaterial_date`, `rmaterial_project`, `rmaterial_category`, `rmaterial_material`, `rmaterial_unit`, `rmaterial_qty`, `rmaterial_added_on`, `rmaterial_added_by`, `rmaterial_updated_on`, `rmaterial_updated_by`, `rmaterial_status`) VALUES
(1, '2026-08-03', 1, 7, 8, 8, '1', '2026-08-03 19:50:48', 1, '2026-08-03 19:50:48', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(255) NOT NULL,
  `role_added_on` datetime NOT NULL,
  `role_added_by` int(11) NOT NULL,
  `role_updated_on` datetime NOT NULL,
  `role_updated_by` int(11) NOT NULL,
  `role_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `role_added_on`, `role_added_by`, `role_updated_on`, `role_updated_by`, `role_status`) VALUES
(1, 'Administrator', '2026-06-30 08:19:36', 1, '2026-08-03 19:49:12', 1, 1),
(2, 'Managing Director', '2026-06-30 08:31:21', 1, '2026-08-03 19:49:22', 1, 1),
(3, 'Site Engineer', '2026-06-30 08:31:32', 1, '2026-08-03 19:49:30', 1, 1),
(4, 'Sales', '2026-07-07 18:35:57', 1, '2026-08-03 19:49:39', 1, 1),
(5, 'QMS', '2026-07-07 18:36:08', 1, '2026-08-03 19:49:47', 1, 1),
(6, 'Accounts', '2026-07-07 18:36:17', 1, '2026-08-03 19:49:56', 1, 1),
(7, 'Site Incharge', '2026-07-07 18:36:53', 1, '2026-08-03 19:50:04', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `rpermission_id` int(11) NOT NULL,
  `rpermission_role` int(11) NOT NULL,
  `rpermission_permission` int(11) NOT NULL,
  `rpermission_added_on` datetime NOT NULL,
  `rpermission_added_by` int(11) NOT NULL,
  `rpermission_updated_on` datetime NOT NULL,
  `rpermission_updated_by` int(11) NOT NULL,
  `rpermission_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`rpermission_id`, `rpermission_role`, `rpermission_permission`, `rpermission_added_on`, `rpermission_added_by`, `rpermission_updated_on`, `rpermission_updated_by`, `rpermission_status`) VALUES
(1495, 1, 1, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1496, 1, 2, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1497, 1, 3, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1498, 1, 4, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1499, 1, 5, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1500, 1, 6, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1501, 1, 7, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1502, 1, 8, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1503, 1, 9, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1504, 1, 10, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1505, 1, 11, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1506, 1, 12, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1507, 1, 13, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1508, 1, 14, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1509, 1, 15, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1510, 1, 16, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1511, 1, 17, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1512, 1, 18, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1513, 1, 19, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1514, 1, 20, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1515, 1, 21, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1516, 1, 22, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1517, 1, 23, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1518, 1, 24, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1519, 1, 25, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1520, 1, 26, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1521, 1, 27, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1522, 1, 28, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1523, 1, 29, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1524, 1, 30, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1525, 1, 31, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1526, 1, 32, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1527, 1, 33, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1528, 1, 34, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1529, 1, 35, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1530, 1, 36, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1531, 1, 94, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1532, 1, 95, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1533, 1, 96, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1534, 1, 97, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1535, 1, 98, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1536, 1, 37, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1537, 1, 38, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1538, 1, 39, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1539, 1, 40, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1540, 1, 41, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1541, 1, 42, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1542, 1, 43, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1543, 1, 44, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1544, 1, 92, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1545, 1, 93, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1546, 1, 45, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1547, 1, 46, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1548, 1, 47, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1549, 1, 48, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1550, 1, 49, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1551, 1, 50, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1552, 1, 51, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1553, 1, 52, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1554, 1, 53, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1555, 1, 54, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1556, 1, 55, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1557, 1, 56, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1558, 1, 57, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1559, 1, 58, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1560, 1, 59, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1561, 1, 60, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1562, 1, 61, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1563, 1, 62, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1564, 1, 63, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1565, 1, 64, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1566, 1, 65, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1567, 1, 66, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1568, 1, 67, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1569, 1, 68, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1570, 1, 69, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1571, 1, 70, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1572, 1, 71, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1573, 1, 72, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1574, 1, 73, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1575, 1, 74, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1576, 1, 75, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1577, 1, 76, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1578, 1, 77, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1579, 1, 78, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1580, 1, 79, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1581, 1, 80, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1582, 1, 81, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1583, 1, 82, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1584, 1, 83, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1585, 1, 84, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1586, 1, 85, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1587, 1, 86, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1588, 1, 87, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1589, 1, 88, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1590, 1, 89, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1591, 1, 90, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1592, 1, 91, '2026-08-03 19:49:12', 1, '2026-08-03 19:49:12', 1, 1),
(1593, 2, 1, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1594, 2, 2, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1595, 2, 3, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1596, 2, 4, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1597, 2, 5, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1598, 2, 6, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1599, 2, 7, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1600, 2, 8, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1601, 2, 9, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1602, 2, 10, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1603, 2, 11, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1604, 2, 12, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1605, 2, 13, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1606, 2, 14, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1607, 2, 15, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1608, 2, 16, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1609, 2, 17, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1610, 2, 18, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1611, 2, 19, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1612, 2, 20, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1613, 2, 21, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1614, 2, 22, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1615, 2, 23, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1616, 2, 24, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1617, 2, 25, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1618, 2, 26, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1619, 2, 27, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1620, 2, 28, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1621, 2, 29, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1622, 2, 30, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1623, 2, 31, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1624, 2, 32, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1625, 2, 33, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1626, 2, 34, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1627, 2, 35, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1628, 2, 36, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1629, 2, 94, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1630, 2, 95, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1631, 2, 96, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1632, 2, 97, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1633, 2, 98, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1634, 2, 37, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1635, 2, 38, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1636, 2, 39, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1637, 2, 40, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1638, 2, 41, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1639, 2, 42, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1640, 2, 43, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1641, 2, 44, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1642, 2, 92, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1643, 2, 93, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1644, 2, 45, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1645, 2, 46, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1646, 2, 47, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1647, 2, 48, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1648, 2, 49, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1649, 2, 50, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1650, 2, 51, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1651, 2, 52, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1652, 2, 53, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1653, 2, 54, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1654, 2, 55, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1655, 2, 56, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1656, 2, 57, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1657, 2, 58, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1658, 2, 59, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1659, 2, 60, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1660, 2, 61, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1661, 2, 62, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1662, 2, 63, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1663, 2, 64, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1664, 2, 65, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1665, 2, 66, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1666, 2, 67, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1667, 2, 68, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1668, 2, 69, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1669, 2, 70, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1670, 2, 71, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1671, 2, 72, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1672, 2, 73, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1673, 2, 74, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1674, 2, 75, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1675, 2, 76, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1676, 2, 77, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1677, 2, 78, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1678, 2, 79, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1679, 2, 80, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1680, 2, 81, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1681, 2, 82, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1682, 2, 83, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1683, 2, 84, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1684, 2, 85, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1685, 2, 86, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1686, 2, 87, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1687, 2, 88, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1688, 2, 89, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1689, 2, 90, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1690, 2, 91, '2026-08-03 19:49:22', 1, '2026-08-03 19:49:22', 1, 1),
(1691, 3, 1, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1692, 3, 2, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1693, 3, 3, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1694, 3, 4, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1695, 3, 5, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1696, 3, 6, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1697, 3, 7, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1698, 3, 8, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1699, 3, 9, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1700, 3, 10, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1701, 3, 11, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1702, 3, 12, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1703, 3, 13, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1704, 3, 14, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1705, 3, 15, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1706, 3, 16, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1707, 3, 17, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1708, 3, 18, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1709, 3, 19, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1710, 3, 20, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1711, 3, 21, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1712, 3, 22, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1713, 3, 23, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1714, 3, 24, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1715, 3, 25, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1716, 3, 26, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1717, 3, 27, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1718, 3, 28, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1719, 3, 29, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1720, 3, 30, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1721, 3, 31, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1722, 3, 32, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1723, 3, 33, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1724, 3, 34, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1725, 3, 35, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1726, 3, 36, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1727, 3, 94, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1728, 3, 95, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1729, 3, 96, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1730, 3, 97, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1731, 3, 98, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1732, 3, 37, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1733, 3, 38, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1734, 3, 39, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1735, 3, 40, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1736, 3, 41, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1737, 3, 42, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1738, 3, 43, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1739, 3, 44, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1740, 3, 92, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1741, 3, 93, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1742, 3, 45, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1743, 3, 46, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1744, 3, 47, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1745, 3, 48, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1746, 3, 49, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1747, 3, 50, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1748, 3, 51, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1749, 3, 52, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1750, 3, 53, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1751, 3, 54, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1752, 3, 55, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1753, 3, 56, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1754, 3, 57, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1755, 3, 58, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1756, 3, 59, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1757, 3, 60, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1758, 3, 61, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1759, 3, 62, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1760, 3, 63, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1761, 3, 64, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1762, 3, 65, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1763, 3, 66, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1764, 3, 67, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1765, 3, 68, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1766, 3, 69, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1767, 3, 70, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1768, 3, 71, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1769, 3, 72, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1770, 3, 73, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1771, 3, 74, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1772, 3, 75, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1773, 3, 76, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1774, 3, 77, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1775, 3, 78, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1776, 3, 79, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1777, 3, 80, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1778, 3, 81, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1779, 3, 82, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1780, 3, 83, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1781, 3, 84, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1782, 3, 85, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1783, 3, 86, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1784, 3, 87, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1785, 3, 88, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1786, 3, 89, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1787, 3, 90, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1788, 3, 91, '2026-08-03 19:49:30', 1, '2026-08-03 19:49:30', 1, 1),
(1789, 4, 1, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1790, 4, 2, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1791, 4, 3, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1792, 4, 4, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1793, 4, 5, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1794, 4, 6, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1795, 4, 7, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1796, 4, 8, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1797, 4, 9, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1798, 4, 10, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1799, 4, 11, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1800, 4, 12, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1801, 4, 13, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1802, 4, 14, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1803, 4, 15, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1804, 4, 16, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1805, 4, 17, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1806, 4, 18, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1807, 4, 19, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1808, 4, 20, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1809, 4, 21, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1810, 4, 22, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1811, 4, 23, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1812, 4, 24, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1813, 4, 25, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1814, 4, 26, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1815, 4, 27, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1816, 4, 28, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1817, 4, 29, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1818, 4, 30, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1819, 4, 31, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1820, 4, 32, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1821, 4, 33, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1822, 4, 34, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1823, 4, 35, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1824, 4, 36, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1825, 4, 94, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1826, 4, 95, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1827, 4, 96, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1828, 4, 97, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1829, 4, 98, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1830, 4, 37, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1831, 4, 38, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1832, 4, 39, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1833, 4, 40, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1834, 4, 41, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1835, 4, 42, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1836, 4, 43, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1837, 4, 44, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1838, 4, 92, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1839, 4, 93, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1840, 4, 45, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1841, 4, 46, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1842, 4, 47, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1843, 4, 48, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1844, 4, 49, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1845, 4, 50, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1846, 4, 51, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1847, 4, 52, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1848, 4, 53, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1849, 4, 54, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1850, 4, 55, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1851, 4, 56, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1852, 4, 57, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1853, 4, 58, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1854, 4, 59, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1855, 4, 60, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1856, 4, 61, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1857, 4, 62, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1858, 4, 63, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1859, 4, 64, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1860, 4, 65, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1861, 4, 66, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1862, 4, 67, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1863, 4, 68, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1864, 4, 69, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1865, 4, 70, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1866, 4, 71, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1867, 4, 72, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1868, 4, 73, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1869, 4, 74, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1870, 4, 75, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1871, 4, 76, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1872, 4, 77, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1873, 4, 78, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1874, 4, 79, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1875, 4, 80, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1876, 4, 81, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1877, 4, 82, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1878, 4, 83, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1879, 4, 84, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1880, 4, 85, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1881, 4, 86, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1882, 4, 87, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1883, 4, 88, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1884, 4, 89, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1885, 4, 90, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1886, 4, 91, '2026-08-03 19:49:39', 1, '2026-08-03 19:49:39', 1, 1),
(1887, 5, 1, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1888, 5, 2, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1889, 5, 3, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1890, 5, 4, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1891, 5, 5, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1892, 5, 6, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1893, 5, 7, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1894, 5, 8, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1895, 5, 9, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1896, 5, 10, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1897, 5, 11, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1898, 5, 12, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1899, 5, 13, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1900, 5, 14, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1901, 5, 15, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1902, 5, 16, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1903, 5, 17, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1904, 5, 18, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1905, 5, 19, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1906, 5, 20, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1907, 5, 21, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1908, 5, 22, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1909, 5, 23, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1910, 5, 24, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1911, 5, 25, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1912, 5, 26, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1913, 5, 27, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1914, 5, 28, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1915, 5, 29, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1916, 5, 30, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1917, 5, 31, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1918, 5, 32, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1919, 5, 33, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1920, 5, 34, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1921, 5, 35, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1922, 5, 36, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1923, 5, 94, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1924, 5, 95, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1925, 5, 96, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1926, 5, 97, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1927, 5, 98, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1928, 5, 37, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1929, 5, 38, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1930, 5, 39, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1931, 5, 40, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1932, 5, 41, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1933, 5, 42, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1934, 5, 43, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1935, 5, 44, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1936, 5, 92, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1937, 5, 93, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1938, 5, 45, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1939, 5, 46, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1940, 5, 47, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1941, 5, 48, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1942, 5, 49, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1943, 5, 50, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1944, 5, 51, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1945, 5, 52, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1946, 5, 53, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1947, 5, 54, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1948, 5, 55, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1949, 5, 56, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1950, 5, 57, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1951, 5, 58, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1952, 5, 59, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1953, 5, 60, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1954, 5, 61, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1955, 5, 62, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1956, 5, 63, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1957, 5, 64, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1958, 5, 65, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1959, 5, 66, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1960, 5, 67, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1961, 5, 68, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1962, 5, 69, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1963, 5, 70, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1964, 5, 71, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1965, 5, 72, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1966, 5, 73, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1967, 5, 74, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1968, 5, 75, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1969, 5, 76, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1970, 5, 77, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1971, 5, 78, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1972, 5, 79, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1973, 5, 80, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1974, 5, 81, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1975, 5, 82, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1976, 5, 83, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1977, 5, 84, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1978, 5, 85, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1979, 5, 86, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1980, 5, 87, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1981, 5, 88, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1982, 5, 89, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1983, 5, 90, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1984, 5, 91, '2026-08-03 19:49:47', 1, '2026-08-03 19:49:47', 1, 1),
(1985, 6, 1, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(1986, 6, 2, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(1987, 6, 3, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(1988, 6, 4, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(1989, 6, 5, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(1990, 6, 6, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(1991, 6, 7, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(1992, 6, 8, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(1993, 6, 9, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(1994, 6, 10, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(1995, 6, 11, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(1996, 6, 12, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(1997, 6, 13, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(1998, 6, 14, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(1999, 6, 15, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2000, 6, 16, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2001, 6, 17, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2002, 6, 18, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2003, 6, 19, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2004, 6, 20, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2005, 6, 21, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2006, 6, 22, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2007, 6, 23, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2008, 6, 24, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2009, 6, 25, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2010, 6, 26, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2011, 6, 27, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2012, 6, 28, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2013, 6, 29, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2014, 6, 30, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2015, 6, 31, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2016, 6, 32, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2017, 6, 33, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2018, 6, 34, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2019, 6, 35, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2020, 6, 36, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2021, 6, 94, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2022, 6, 95, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2023, 6, 96, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2024, 6, 97, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2025, 6, 98, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2026, 6, 37, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2027, 6, 38, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2028, 6, 39, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2029, 6, 40, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2030, 6, 41, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2031, 6, 42, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2032, 6, 43, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2033, 6, 44, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2034, 6, 92, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2035, 6, 93, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2036, 6, 45, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2037, 6, 46, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2038, 6, 47, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2039, 6, 48, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2040, 6, 49, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2041, 6, 50, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2042, 6, 51, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2043, 6, 52, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2044, 6, 53, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2045, 6, 54, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2046, 6, 55, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2047, 6, 56, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2048, 6, 57, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2049, 6, 58, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2050, 6, 59, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2051, 6, 60, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2052, 6, 61, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2053, 6, 62, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2054, 6, 63, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2055, 6, 64, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2056, 6, 65, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2057, 6, 66, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2058, 6, 67, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2059, 6, 68, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2060, 6, 69, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2061, 6, 70, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2062, 6, 71, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2063, 6, 72, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2064, 6, 73, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2065, 6, 74, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2066, 6, 75, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2067, 6, 76, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2068, 6, 77, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2069, 6, 78, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2070, 6, 79, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2071, 6, 80, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2072, 6, 81, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2073, 6, 82, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2074, 6, 83, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2075, 6, 84, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2076, 6, 85, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2077, 6, 86, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2078, 6, 87, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2079, 6, 88, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2080, 6, 89, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2081, 6, 90, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2082, 6, 91, '2026-08-03 19:49:56', 1, '2026-08-03 19:49:56', 1, 1),
(2083, 7, 1, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2084, 7, 2, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2085, 7, 3, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2086, 7, 4, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2087, 7, 5, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2088, 7, 6, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2089, 7, 7, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2090, 7, 8, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2091, 7, 9, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2092, 7, 10, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2093, 7, 11, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2094, 7, 12, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2095, 7, 13, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2096, 7, 14, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2097, 7, 15, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2098, 7, 16, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2099, 7, 17, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2100, 7, 18, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2101, 7, 19, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2102, 7, 20, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2103, 7, 21, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2104, 7, 22, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2105, 7, 23, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2106, 7, 24, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2107, 7, 25, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2108, 7, 26, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2109, 7, 27, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2110, 7, 28, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2111, 7, 29, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2112, 7, 30, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2113, 7, 31, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2114, 7, 32, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2115, 7, 33, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2116, 7, 34, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2117, 7, 35, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2118, 7, 36, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2119, 7, 94, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2120, 7, 95, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2121, 7, 96, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2122, 7, 97, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2123, 7, 98, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2124, 7, 37, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2125, 7, 38, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2126, 7, 39, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2127, 7, 40, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2128, 7, 41, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2129, 7, 42, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2130, 7, 43, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2131, 7, 44, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2132, 7, 92, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2133, 7, 93, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2134, 7, 45, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2135, 7, 46, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2136, 7, 47, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2137, 7, 48, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2138, 7, 49, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2139, 7, 50, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2140, 7, 51, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2141, 7, 52, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2142, 7, 53, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2143, 7, 54, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2144, 7, 55, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2145, 7, 56, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2146, 7, 57, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2147, 7, 58, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2148, 7, 59, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2149, 7, 60, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2150, 7, 61, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2151, 7, 62, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2152, 7, 63, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2153, 7, 64, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2154, 7, 65, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2155, 7, 66, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2156, 7, 67, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2157, 7, 68, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2158, 7, 69, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2159, 7, 70, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2160, 7, 71, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2161, 7, 72, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2162, 7, 73, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2163, 7, 74, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2164, 7, 75, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2165, 7, 76, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2166, 7, 77, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2167, 7, 78, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2168, 7, 79, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2169, 7, 80, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2170, 7, 81, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2171, 7, 82, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2172, 7, 83, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2173, 7, 84, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2174, 7, 85, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2175, 7, 86, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2176, 7, 87, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2177, 7, 88, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2178, 7, 89, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2179, 7, 90, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1),
(2180, 7, 91, '2026-08-03 19:50:04', 1, '2026-08-03 19:50:04', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('3fK4fRzkboLqzOMuBrYqJ12DNt2orNoOkiYy54O3', 1, '2406:b400:3a:5261:c52b:cbb5:4864:f926', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNzNVcW1xd2xtQmRRenJlY1RKMVBYRm1MUHhrQ2JnV0FsYXEyOGFjUiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDk6Imh0dHBzOi8vc2l0ZXRyYWNrLmNvZGV0cmlja3ouY29tL3VzZXIvZGFpbHlfd29ya3MiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1787051853),
('83QYwkUNdWY0ZI03nMwJCKsI00znYRrbd5DzZHX7', 1, '103.158.43.43', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTVNRS1I3RHdGY1pFdTZYT3gzZTJadExrdjROYVNKQVRGalFyd1JaeCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDc6Imh0dHBzOi8vc2l0ZXRyYWNrLmNvZGV0cmlja3ouY29tL3VzZXIvZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1787052678),
('8edbGr1ZoqXq4Jj4h6OglpgekyvK2nUWDnbeAqsk', 1, '2406:b400:3a:5261:c52b:cbb5:4864:f926', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNjBZZnlDbGlTSzRLN2NaOFp3OEttcWsyUzJCbFQ4MWN4NjNEZnp5aCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDc6Imh0dHBzOi8vc2l0ZXRyYWNrLmNvZGV0cmlja3ouY29tL3VzZXIvZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1787026616),
('96kde44txObtS6H8ERjsiY3Tz5Ndf7Y874dGj6iT', 1, '2406:b400:3a:5261:b12e:34f5:945a:c439', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQmxXTFJwSGZDTGRBZk56OFlvVExDWWFIY2dWWFJGME5ua3pQZlhjNSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDU6Imh0dHBzOi8vc2l0ZXRyYWNrLmNvZGV0cmlja3ouY29tL3VzZXIvdmVuZG9ycyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1786953445),
('IPgivlIsJwHuVS7RSY6SeWq78WTedILvMWaEGtiI', NULL, '34.48.86.77', 'Mozilla/5.0 (compatible; CMS-Checker/1.0; +https://example.com)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidW5SUnRnbWc4NXA4U2FHQ2IyR1JFc2lBRFAyZnBzTW1ZTFdXa1NrcCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vc2l0ZXRyYWNrLmNvZGV0cmlja3ouY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1786988452),
('LeahubG6QfjSxgc8tlQ2KX02TcTsvkVG9hG3BTCL', 1, '2406:b400:3a:5261:b12e:34f5:945a:c439', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiM0kxTDdUQzBGY2QzS0dkWkttcUtOU29WcGJqWTM1NXVBZ2R0V1dDWiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDk6Imh0dHBzOi8vc2l0ZXRyYWNrLmNvZGV0cmlja3ouY29tL3VzZXIvZGFpbHlfd29ya3MiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1786966882),
('mYhx5KyQ7YzdAQdJe7FLWi0oSLj8xYBCdKGpZDA8', NULL, '136.66.80.4', 'Mozilla/5.0 (compatible; CMS-Checker/1.0; +https://example.com)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR3ZsOEtVTEhSREJlWjg2Wk1LM3EzRXFjZFB5Vk1zN0hkRXd3U3BZOCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vc2l0ZXRyYWNrLmNvZGV0cmlja3ouY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1787002765),
('vNSZyqGMzPNyqsb6rsq0EAWAJuxJF57uY3Vrgi5V', NULL, '54.73.170.99', 'Mozilla/5.0 (compatible; NetcraftSurveyAgent/1.0; +info@netcraft.com)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicndwcnVjamRxN21NMDltdUlYZ1R3QkFYRUt3R3p2V0NoZENUY2pUaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vc2l0ZXRyYWNrLmNvZGV0cmlja3ouY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1786966525);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `settings_id` int(10) UNSIGNED NOT NULL,
  `settings_title` varchar(255) NOT NULL,
  `settings_company` varchar(255) NOT NULL,
  `settings_email` varchar(255) NOT NULL,
  `settings_phone` varchar(255) NOT NULL,
  `settings_address` text NOT NULL,
  `settings_logo` varchar(255) NOT NULL,
  `settings_wlogo` varchar(255) NOT NULL,
  `settings_favicon` varchar(255) NOT NULL,
  `settings_mailer` varchar(255) NOT NULL,
  `settings_host` varchar(255) NOT NULL,
  `settings_port` varchar(255) NOT NULL,
  `settings_uname` varchar(255) NOT NULL,
  `settings_pwd` varchar(255) NOT NULL,
  `settings_encryption` varchar(255) NOT NULL,
  `settings_faddress` varchar(255) NOT NULL,
  `settings_fname` varchar(255) NOT NULL,
  `settings_added_on` datetime NOT NULL,
  `settings_added_by` int(11) NOT NULL,
  `settings_updated_on` datetime NOT NULL,
  `settings_updated_by` int(11) NOT NULL,
  `settings_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`settings_id`, `settings_title`, `settings_company`, `settings_email`, `settings_phone`, `settings_address`, `settings_logo`, `settings_wlogo`, `settings_favicon`, `settings_mailer`, `settings_host`, `settings_port`, `settings_uname`, `settings_pwd`, `settings_encryption`, `settings_faddress`, `settings_fname`, `settings_added_on`, `settings_added_by`, `settings_updated_on`, `settings_updated_by`, `settings_status`) VALUES
(1, 'SiteTrack Pro', 'SiteTrack Pro', 'codetrickz22@gmail.com', '9876543210', '2880 Broadway, \r\nNew York', 'ZqYPr1sor1MbdDkC4mvbyyLsDRWOFbBuge55IzQb.png', 'IlEZnSIQYtqdS8y5oh2oZz9mUxvGwenHYO8DWq5X.png', 'SZeEyOpVkzkovyyOpq02INoETlvY1J9exIFKc7kw.png', 'smtp', 'smtp.hostinger.com', '587', 'support@codetrickz.com', 'j]5k]/R8x?E', 'tls', 'support@codetrickz.com', 'SiteTrack Pro', '2026-06-30 16:41:18', 1, '2026-06-30 21:12:05', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `stock_id` int(11) NOT NULL,
  `stock_material` int(11) NOT NULL,
  `stock_qty` varchar(255) NOT NULL,
  `stock_added_on` datetime NOT NULL,
  `stock_added_by` int(11) NOT NULL,
  `stock_updated_on` datetime NOT NULL,
  `stock_updated_by` int(11) NOT NULL,
  `stock_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`stock_id`, `stock_material`, `stock_qty`, `stock_added_on`, `stock_added_by`, `stock_updated_on`, `stock_updated_by`, `stock_status`) VALUES
(1, 8, '13', '2026-08-02 16:29:18', 1, '2026-08-03 19:50:48', 1, 1),
(2, 1, '10', '2026-08-02 16:31:42', 1, '2026-08-02 16:31:42', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `subcontractors`
--

CREATE TABLE `subcontractors` (
  `subcontractor_id` int(11) NOT NULL,
  `subcontractor_name` varchar(255) NOT NULL,
  `subcontractor_email` varchar(255) NOT NULL,
  `subcontractor_phone` varchar(255) NOT NULL,
  `subcontractor_address` text NOT NULL,
  `subcontractor_added_on` datetime NOT NULL,
  `subcontractor_added_by` int(11) NOT NULL,
  `subcontractor_updated_on` datetime NOT NULL,
  `subcontractor_updated_by` int(11) NOT NULL,
  `subcontractor_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subcontractors`
--

INSERT INTO `subcontractors` (`subcontractor_id`, `subcontractor_name`, `subcontractor_email`, `subcontractor_phone`, `subcontractor_address`, `subcontractor_added_on`, `subcontractor_added_by`, `subcontractor_updated_on`, `subcontractor_updated_by`, `subcontractor_status`) VALUES
(1, 'M/s Patel Works', 'patelworks@gmail.com', '7845120030', 'Delhi, India', '2026-07-12 10:58:47', 1, '2026-07-12 11:01:01', 1, 1),
(2, 'M/s Singh Constructions', 'singhconstructions@gmail.com', '9645152310', 'Mumbai, India', '2026-07-12 10:59:29', 1, '2026-07-12 10:59:29', 1, 1),
(3, 'M/s Kumar & Co', 'kumar.co@gmail.com', '6845102545', 'Delhi, India', '2026-07-12 11:00:07', 1, '2026-07-12 11:00:07', 1, 1),
(4, 'M/s Reddy Infra', 'reddyinfra@gmail.com', '9845201256', 'Hydrabad, India', '2026-07-12 11:00:39', 1, '2026-07-12 11:00:39', 1, 1),
(5, 'V.Ramanjaneyulu', 'Ramanjaneyelu@gmail.com', '9912376662', 'Hyderabad', '2026-08-06 10:59:04', 1, '2026-08-06 11:06:54', 1, 1),
(6, 'MOHAMMED RIAZUDDIN QUADRI BHATI', 'riyazuddin@gmail.com', '9849933614', 'HYDERABAD', '2026-08-06 15:54:54', 1, '2026-08-06 15:54:54', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `subcontractor_ledger`
--

CREATE TABLE `subcontractor_ledger` (
  `sledger_id` int(11) NOT NULL,
  `sledger_subcontractor` int(11) NOT NULL,
  `sledger_project` int(11) NOT NULL,
  `sledger_date` date NOT NULL,
  `sledger_type` int(11) NOT NULL,
  `sledger_wpno` varchar(255) NOT NULL,
  `sledger_description` text NOT NULL,
  `sledger_total` varchar(255) NOT NULL,
  `sledger_invoice` varchar(255) NOT NULL,
  `sledger_paid` varchar(255) NOT NULL,
  `sledger_remarks` text DEFAULT NULL,
  `sledger_added_on` datetime NOT NULL,
  `sledger_added_by` int(11) NOT NULL,
  `sledger_updated_on` datetime NOT NULL,
  `sledger_updated_by` int(11) NOT NULL,
  `sledger_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subcontractor_ledger`
--

INSERT INTO `subcontractor_ledger` (`sledger_id`, `sledger_subcontractor`, `sledger_project`, `sledger_date`, `sledger_type`, `sledger_wpno`, `sledger_description`, `sledger_total`, `sledger_invoice`, `sledger_paid`, `sledger_remarks`, `sledger_added_on`, `sledger_added_by`, `sledger_updated_on`, `sledger_updated_by`, `sledger_status`) VALUES
(1, 1, 1, '2026-07-12', 4, 'ADV-2025-001', 'Mobilization advance - shuttering', '50000', '50000', '50000', 'Against WO-2025-004', '2026-07-12 23:58:49', 1, '2026-07-12 23:58:49', 1, 1),
(2, 4, 3, '2026-07-12', 1, 'WO-2025-003', 'Road formation Sector 4', '420000', '420000', '150000', 'Running bill pending', '2026-07-12 23:59:40', 1, '2026-07-12 23:59:40', 1, 1),
(3, 2, 2, '2026-07-12', 1, 'WO-2025-002', 'Brickwork South Wing', '180000', '180000', '180000', 'Fully paid', '2026-07-13 00:00:34', 1, '2026-07-13 00:00:34', 1, 1),
(4, 1, 1, '2026-07-13', 1, 'WO-2025-001', 'RCC works Block A - May', '320000', '320000', '200000', 'Balance due 25 May', '2026-07-13 00:01:29', 1, '2026-07-13 00:01:29', 1, 1),
(5, 4, 2, '2026-08-06', 2, 'AIP/WO/2026-27/01', 'RMC', '700000', '710000', '400000', 'PLEASE APPROVE BALANCE PAYMENT', '2026-08-06 11:20:36', 13, '2026-08-06 11:20:36', 13, 1),
(6, 5, 3, '2026-08-06', 4, '140', 'Waterproofing', '200000', '0', '50000', 'ADVANCE', '2026-08-06 15:48:36', 1, '2026-08-06 15:48:36', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `unit_id` int(10) UNSIGNED NOT NULL,
  `unit_name` varchar(255) NOT NULL,
  `unit_added_on` datetime NOT NULL,
  `unit_added_by` int(11) NOT NULL,
  `unit_updated_on` datetime NOT NULL,
  `unit_updated_by` int(11) NOT NULL,
  `unit_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`unit_id`, `unit_name`, `unit_added_on`, `unit_added_by`, `unit_updated_on`, `unit_updated_by`, `unit_status`) VALUES
(1, 'Cum', '2026-07-12 10:53:44', 1, '2026-07-12 10:53:44', 1, 1),
(2, 'Sqm', '2026-07-12 10:53:57', 1, '2026-07-12 10:53:57', 1, 1),
(3, 'Rmt', '2026-07-12 10:54:06', 1, '2026-07-12 10:54:06', 1, 1),
(4, 'MT', '2026-07-12 10:54:15', 1, '2026-07-12 10:54:15', 1, 1),
(5, 'Bag', '2026-07-12 10:54:23', 1, '2026-07-12 10:54:23', 1, 1),
(6, 'Nos', '2026-07-12 10:54:31', 1, '2026-07-12 10:54:31', 1, 1),
(7, 'LS', '2026-07-12 10:54:39', 1, '2026-07-12 10:54:39', 1, 1),
(8, 'Litre', '2026-07-12 10:54:50', 1, '2026-07-12 10:54:50', 1, 1),
(9, 'Kg', '2026-07-12 10:54:59', 1, '2026-07-12 10:54:59', 1, 1),
(10, 'mm', '2026-07-12 10:55:12', 1, '2026-07-12 10:55:26', 1, 1),
(11, 'inches', '2026-07-12 10:55:35', 1, '2026-07-12 10:55:35', 1, 1),
(12, 'feet', '2026-07-12 10:55:56', 1, '2026-07-12 10:55:56', 1, 1),
(13, 'sqft', '2026-07-12 10:56:10', 1, '2026-07-12 10:56:10', 1, 1),
(14, 'Ton', '2026-07-12 10:56:20', 1, '2026-07-12 11:01:27', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_role` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_lname` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_phone` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_vpassword` varchar(255) NOT NULL,
  `user_image` varchar(255) DEFAULT NULL,
  `user_added_on` datetime NOT NULL,
  `user_added_by` int(11) NOT NULL,
  `user_updated_on` datetime NOT NULL,
  `user_updated_by` int(11) NOT NULL,
  `user_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_role`, `user_name`, `user_lname`, `user_email`, `user_phone`, `user_password`, `user_vpassword`, `user_image`, `user_added_on`, `user_added_by`, `user_updated_on`, `user_updated_by`, `user_status`) VALUES
(1, 1, 'Admin', 'admin', 'codetrickz22@gmail.com', '9876543210', '$2y$10$IoKjHq5F1GXbpbYhh0DO0.UzVXT8j0ND/7krWBCFQv53reTqKzp2O', '', 'k5tfKVjWQbdHhsS4lUKHEJvcOYzl4251XI9vlsbp.jpg', '2026-06-30 18:11:06', 1, '2026-07-26 21:01:41', 1, 1),
(2, 2, 'Rajesh Kumar', 'rajesh', 'rajesh@gmail.com', '8565451520', '$2y$10$ZM2oQk/BzKukOgoHhz1FGuJI1bhBZzLZwVRM9v5mYL5xobtiH.8Ni', '', 'bvg1RsuX1TfpUoHMXDYnkxXnXSQnD09zbBhdcGCB.jpg', '2026-06-30 21:47:44', 1, '2026-07-26 20:52:11', 1, 1),
(3, 3, 'Ravi Kumar', 'ravi', 'ravi@gmail.com', '7545152350', '$2y$10$2F5zJ5qyzpnzbGXtgj2eTeERBKO7ynRGUfChNwRmeMTTNSmpF5l1G', '', 'dtELYhJMVycUk7xAhhZvpujV4u0MYAc0ET6mnyH1.jpg', '2026-07-07 18:38:26', 1, '2026-07-26 20:51:55', 1, 1),
(4, 4, 'Mohan Reddy', 'mohan', 'mohan@gmail.com', '8565154520', '$2y$10$dHW2ZydlE0Q6jGd1gWzMf.vVHi5V3cZuQpHZDcZVJxy1SXRviSryu', '', 'gZ03C1bVtbSt2bBIBZ3S1NtUKQ2JHlCHetNgyPKn.jpg', '2026-07-07 18:39:04', 1, '2026-07-26 20:51:44', 1, 1),
(5, 5, 'Priya Mehta', 'priya', 'priyamehta@gmail.com', '9601203256', '$2y$10$zF0ryATXf9U0pnFi99A4Ku/SIKGWR7A/5RyGo4i6Xbm8XmAKmdeWS', '', '2GxDYSaV9028OPoq09JMA7NS4svKiF675tXEva2Z.jpg', '2026-07-07 18:39:56', 1, '2026-07-26 20:51:33', 1, 1),
(6, 6, 'John', 'john', 'john@gmail.com', '7563001569', '$2y$10$65Yt1o6NdQA9rV64vx5sTu40sZ6kSANNI/MzvY3FUarvmKQSQYB8.', '', '65QnqGsyxhQgrtKWfxU7QA4EiZ1aWylQAsAQeXoB.jpg', '2026-07-07 18:40:46', 1, '2026-07-26 20:51:17', 1, 1),
(7, 7, 'Thilak', 'thilak', 'thilak@gmail.com', '9655001563', '$2y$10$52juhwGKpwzkkLY6hJeQ2uli2c6k4YW8GtZeAfs4kJm1MVKfan/v2', '', 'lvjdqqFZLDCDX26DmNDlUWvsyQBMhNxk6Rkh1xVH.jpg', '2026-07-07 18:41:39', 1, '2026-07-26 20:51:08', 1, 1),
(8, 7, 'Shiva', 'shiva', 'shiva@gmail.com', '9645102352', '$2y$10$.2PelEMt/qRyjKETdE4gzecwWwtCKyEmkNhtaC793KzKNjurPAHIe', '', 'itmUPH4CGDwMtJq4bqOEVInk1hjhf0cs68yb31HO.jpg', '2026-07-07 18:42:14', 1, '2026-07-26 20:50:57', 1, 1),
(9, 3, 'Anil Sharma', 'anil', 'anil@gmail.com', '9601452315', '$2y$10$s9N98S.y5vDQSCj6lJBDPegPaK0AUh56qfm/zE5KRd0mAQDcDuvIm', '', 'p2rFYcCiEUrrC9z0UlLorqrdssam8tRIdH1VoH8O.png', '2026-07-07 18:43:06', 1, '2026-07-26 20:50:48', 1, 1),
(10, 3, 'Yash', 'yash.1234', 'yash@gmail.com', '9645001536', '$2y$10$4Sw1wavxXLDsLAS.06LQ4.TvMvbo5cNqp8va45X/lyLlVdkLbPs7e', '', NULL, '2026-07-26 20:48:00', 1, '2026-07-26 20:53:13', 1, 1),
(11, 4, 'Bhavani', 'bhavani', 'Bhavani@gmail.com', '0000000000', '$2y$10$DeHQw0HdopE7lUUPLMY51efQ1NTLCqYdxVixyS8HhjvNEy/9vjUIu', '', NULL, '2026-08-05 10:32:26', 1, '2026-08-05 10:32:26', 1, 1),
(12, 7, 'Raju', 'Raju', 'Raju@gmail.com', '0000000000', '$2y$10$6DaEbtpY1pLDdRcni.5w2O/x3t7pA0VKv25TYnnDLuYsF0kqMWTTy', '', NULL, '2026-08-05 10:32:49', 1, '2026-08-05 10:32:49', 1, 1),
(13, 5, 'Amrutha', 'Amrutha', 'Amrutha@gmail.com', '00000000000', '$2y$10$VU4hBY1YF2rOQ9sGKPg.jeP.4Mc5pa6Wx9CF1XIxbr6yixZvGXZJy', '', NULL, '2026-08-05 10:33:36', 1, '2026-08-05 10:33:36', 1, 1),
(14, 7, 'Surendra', 'surendra', 'Surendra@gmail.com', '0000000000', '$2y$10$Rt2.ZOUbgK00MlfrqZD5UO.c/KfMKD7qHsWUlaRvGk/v1s/vHxq2a', '', NULL, '2026-08-05 10:34:09', 1, '2026-08-05 10:37:34', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `vendor_id` int(11) NOT NULL,
  `vendor_category` int(11) NOT NULL,
  `vendor_name` varchar(255) NOT NULL,
  `vendor_company` varchar(255) NOT NULL,
  `vendor_mobile` varchar(255) NOT NULL,
  `vendor_altno` varchar(255) DEFAULT NULL,
  `vendor_location` varchar(255) NOT NULL,
  `vendor_type` varchar(255) NOT NULL,
  `vendor_remarks` text DEFAULT NULL,
  `vendor_card` varchar(255) DEFAULT NULL,
  `vendor_added_on` datetime NOT NULL,
  `vendor_added_by` int(11) NOT NULL,
  `vendor_updated_on` datetime NOT NULL,
  `vendor_updated_by` int(11) NOT NULL,
  `vendor_status` int(11) NOT NULL,
  `vendor_trash` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`vendor_id`, `vendor_category`, `vendor_name`, `vendor_company`, `vendor_mobile`, `vendor_altno`, `vendor_location`, `vendor_type`, `vendor_remarks`, `vendor_card`, `vendor_added_on`, `vendor_added_by`, `vendor_updated_on`, `vendor_updated_by`, `vendor_status`, `vendor_trash`) VALUES
(1, 1, 'Rajesh Gupta', 'ACC Cements Distributor', '9876543210', '040-23456789', 'Hyderabad', 'OPC/PPC Cement all grades', NULL, 'imOxFXXmGXJ0ZSCgkvaXnwIp8U4RAW7eHziNHcm3.pdf', '2026-07-12 11:20:05', 1, '2026-07-12 11:31:38', 1, 1, 1),
(2, 2, 'Suresh Patel', 'SAIL Authorized Dealer', '9988776655', '9876543211', 'Bengaluru', 'TMT Fe500/Fe550 all dia', 'Test Remarks', NULL, '2026-07-12 11:21:28', 1, '2026-07-12 11:21:28', 1, 1, 1),
(3, 7, 'Mahesh Kumar', 'Asian Paints Retailer', '9123456780', NULL, 'Hyderabad', 'Interior/Exterior paints', NULL, NULL, '2026-07-12 11:23:17', 1, '2026-07-12 11:31:45', 1, 1, 1),
(4, 1, 'NAGARJUNA', 'NAGARJUNA', '9999999999', NULL, 'HYD', 'CEMENT', NULL, NULL, '2026-08-06 11:50:10', 1, '2026-08-06 11:50:10', 1, 1, 1),
(5, 11, 'SS GLASS', 'M/s. SA GLASS', '07893482534', NULL, 'GACHIBOWLI', 'GLASS', NULL, NULL, '2026-08-06 12:01:28', 1, '2026-08-06 12:01:28', 1, 1, 1),
(6, 10, 'Kalam', 'Kalam', '99999944', NULL, 'hyd', 'wood', NULL, NULL, '2026-08-06 12:03:07', 11, '2026-08-06 12:03:07', 11, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `vendor_ledger`
--

CREATE TABLE `vendor_ledger` (
  `vledger_id` int(11) NOT NULL,
  `vledger_vendor` int(11) NOT NULL,
  `vledger_project` int(11) NOT NULL,
  `vledger_date` date NOT NULL,
  `vledger_type` int(11) NOT NULL,
  `vledger_pono` varchar(255) NOT NULL,
  `vledger_description` text NOT NULL,
  `vledger_po_total` varchar(255) NOT NULL,
  `vledger_invoice` varchar(255) NOT NULL,
  `vledger_paid` varchar(255) NOT NULL,
  `vledger_remarks` text DEFAULT NULL,
  `vledger_added_on` datetime NOT NULL,
  `vledger_added_by` int(11) NOT NULL,
  `vledger_updated_on` datetime NOT NULL,
  `vledger_updated_by` int(11) NOT NULL,
  `vledger_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendor_ledger`
--

INSERT INTO `vendor_ledger` (`vledger_id`, `vledger_vendor`, `vledger_project`, `vledger_date`, `vledger_type`, `vledger_pono`, `vledger_description`, `vledger_po_total`, `vledger_invoice`, `vledger_paid`, `vledger_remarks`, `vledger_added_on`, `vledger_added_by`, `vledger_updated_on`, `vledger_updated_by`, `vledger_status`) VALUES
(1, 2, 2, '2026-07-13', 1, 'PO-MAT-002', 'TMT Fe500 - 8 MT', '464000', '468000', '300000', 'Balance pending', '2026-07-13 07:42:34', 1, '2026-07-13 07:42:34', 1, 1),
(2, 1, 1, '2026-07-13', 1, 'PO-MAT-001', 'Cement OPC 53 - 300 bags', '114000', '114000', '114000', 'Paid via NEFT', '2026-07-13 07:43:32', 1, '2026-07-13 07:43:32', 1, 1),
(3, 3, 1, '2026-07-13', 1, 'PO-MAT-003', 'Green Paint - 3 Litre', '48000', '48000', '0', 'Payment due', '2026-07-13 07:44:39', 1, '2026-07-13 07:44:39', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assign_materials`
--
ALTER TABLE `assign_materials`
  ADD PRIMARY KEY (`amaterial_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `daily_progress`
--
ALTER TABLE `daily_progress`
  ADD PRIMARY KEY (`dprogress_id`);

--
-- Indexes for table `daily_works`
--
ALTER TABLE `daily_works`
  ADD PRIMARY KEY (`dwork_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `indents`
--
ALTER TABLE `indents`
  ADD PRIMARY KEY (`indent_id`);

--
-- Indexes for table `indent_items`
--
ALTER TABLE `indent_items`
  ADD PRIMARY KEY (`iitem_id`);

--
-- Indexes for table `indent_quotations`
--
ALTER TABLE `indent_quotations`
  ADD PRIMARY KEY (`iquote_id`);

--
-- Indexes for table `issues`
--
ALTER TABLE `issues`
  ADD PRIMARY KEY (`issue_id`);

--
-- Indexes for table `issue_category`
--
ALTER TABLE `issue_category`
  ADD PRIMARY KEY (`icategory_id`);

--
-- Indexes for table `labour_trackers`
--
ALTER TABLE `labour_trackers`
  ADD PRIMARY KEY (`ltracker_id`);

--
-- Indexes for table `labour_workers`
--
ALTER TABLE `labour_workers`
  ADD PRIMARY KEY (`lworker_id`);

--
-- Indexes for table `main_modules`
--
ALTER TABLE `main_modules`
  ADD PRIMARY KEY (`mmodule_id`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`material_id`);

--
-- Indexes for table `material_category`
--
ALTER TABLE `material_category`
  ADD PRIMARY KEY (`mcategory_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`module_id`);

--
-- Indexes for table `module_permissions`
--
ALTER TABLE `module_permissions`
  ADD PRIMARY KEY (`mpermission_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_delivery`
--
ALTER TABLE `order_delivery`
  ADD PRIMARY KEY (`odelivery_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`project_id`);

--
-- Indexes for table `project_boqs`
--
ALTER TABLE `project_boqs`
  ADD PRIMARY KEY (`pboq_id`);

--
-- Indexes for table `return_materials`
--
ALTER TABLE `return_materials`
  ADD PRIMARY KEY (`rmaterial_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`rpermission_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`settings_id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`stock_id`);

--
-- Indexes for table `subcontractors`
--
ALTER TABLE `subcontractors`
  ADD PRIMARY KEY (`subcontractor_id`);

--
-- Indexes for table `subcontractor_ledger`
--
ALTER TABLE `subcontractor_ledger`
  ADD PRIMARY KEY (`sledger_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`unit_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`vendor_id`);

--
-- Indexes for table `vendor_ledger`
--
ALTER TABLE `vendor_ledger`
  ADD PRIMARY KEY (`vledger_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assign_materials`
--
ALTER TABLE `assign_materials`
  MODIFY `amaterial_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `daily_progress`
--
ALTER TABLE `daily_progress`
  MODIFY `dprogress_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `daily_works`
--
ALTER TABLE `daily_works`
  MODIFY `dwork_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `indents`
--
ALTER TABLE `indents`
  MODIFY `indent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `indent_items`
--
ALTER TABLE `indent_items`
  MODIFY `iitem_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `indent_quotations`
--
ALTER TABLE `indent_quotations`
  MODIFY `iquote_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `issues`
--
ALTER TABLE `issues`
  MODIFY `issue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `issue_category`
--
ALTER TABLE `issue_category`
  MODIFY `icategory_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `labour_trackers`
--
ALTER TABLE `labour_trackers`
  MODIFY `ltracker_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `labour_workers`
--
ALTER TABLE `labour_workers`
  MODIFY `lworker_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `main_modules`
--
ALTER TABLE `main_modules`
  MODIFY `mmodule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `material_category`
--
ALTER TABLE `material_category`
  MODIFY `mcategory_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `module_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `module_permissions`
--
ALTER TABLE `module_permissions`
  MODIFY `mpermission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_delivery`
--
ALTER TABLE `order_delivery`
  MODIFY `odelivery_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `project_boqs`
--
ALTER TABLE `project_boqs`
  MODIFY `pboq_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `return_materials`
--
ALTER TABLE `return_materials`
  MODIFY `rmaterial_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `rpermission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2181;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `settings_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subcontractors`
--
ALTER TABLE `subcontractors`
  MODIFY `subcontractor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subcontractor_ledger`
--
ALTER TABLE `subcontractor_ledger`
  MODIFY `sledger_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `unit_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `vendor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vendor_ledger`
--
ALTER TABLE `vendor_ledger`
  MODIFY `vledger_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
