-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql201.infinityfree.com
-- Generation Time: Jul 22, 2025 at 11:37 PM
-- Server version: 11.4.7-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_39092594_flystudio`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` varchar(10) NOT NULL,
  `id_user` varchar(10) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `position` enum('leader','co-leader','founder') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `id_user`, `description`, `created_at`, `position`) VALUES
('FLY0001ADM', 'FLY0001USR', 'Leader fly studio yang humble :) hehhehe', '2025-02-24 04:25:40', 'leader'),
('FLY0002ADM', 'FLY0007USR', 'Ya', '2025-05-30 18:28:53', 'founder'),
('FLY0003ADM', 'FLY0008USR', 'Ya', '2025-05-30 18:29:20', 'co-leader');

-- --------------------------------------------------------

--
-- Table structure for table `chat_requests`
--

CREATE TABLE `chat_requests` (
  `id_chat_request` varchar(10) NOT NULL,
  `id_requester` varchar(10) DEFAULT NULL,
  `id_receiver` varchar(10) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_requests`
--

INSERT INTO `chat_requests` (`id_chat_request`, `id_requester`, `id_receiver`, `status`, `created_at`) VALUES
('682ffd1e91', 'FLY0003USR', NULL, 'pending', '2025-05-22 23:44:14'),
('FLY0001CRS', 'FLY0004USR', 'FLY0001USR', 'approved', '2025-01-14 06:41:49'),
('FLY0002CRS', 'FLY0002USR', 'FLY0001USR', 'approved', '2025-01-14 06:48:17'),
('FLY0003CRS', 'FLY0002USR', 'FLY0001USR', 'approved', '2025-01-14 06:53:42'),
('FLY0004CRS', 'FLY0004USR', 'FLY0001USR', 'approved', '2025-01-14 06:57:34'),
('FLY0005CRS', 'FLY0004USR', 'FLY0001USR', 'approved', '2025-01-16 00:56:43'),
('FLY0006CRS', 'FLY0002USR', 'FLY0003USR', 'approved', '2025-03-08 08:30:00'),
('FLY0007CRS', 'FLY0001USR', 'FLY0003USR', 'approved', '2025-05-21 14:53:41'),
('FLY0008CRS', 'FLY0001USR', 'FLY0005USR', 'approved', '2025-05-21 21:28:18'),
('FLY0009CRS', 'FLY0006USR', 'FLY0003USR', 'approved', '2025-05-23 00:00:23'),
('FLY0010CRS', 'FLY0006USR', 'FLY0002USR', 'approved', '2025-05-28 01:09:37'),
('FLY0011CRS', 'FLY0001USR', 'FLY0006USR', 'approved', '2025-06-04 20:48:33'),
('FLY0012CRS', 'FLY0005USR', 'FLY0002USR', 'pending', '2025-06-05 02:44:48'),
('FLY0013CRS', 'FLY0016USR', 'FLY0005USR', 'approved', '2025-06-11 07:06:23'),
('FLY0014CRS', 'FLY0015USR', 'FLY0004USR', 'approved', '2025-06-16 05:05:39'),
('FLY0015CRS', 'FLY0018USR', 'FLY0003USR', 'pending', '2025-06-24 06:16:15'),
('FLY0016CRS', 'FLY0018USR', 'FLY0005USR', 'approved', '2025-06-24 06:16:39'),
('FLY0017CRS', 'FLY0019USR', 'FLY0005USR', 'approved', '2025-06-24 06:30:08');

-- --------------------------------------------------------

--
-- Table structure for table `chat_rooms`
--

CREATE TABLE `chat_rooms` (
  `id_room` varchar(10) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` enum('private','group') DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `icon` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_rooms`
--

INSERT INTO `chat_rooms` (`id_room`, `name`, `type`, `created_at`, `icon`) VALUES
('682e377f4c', NULL, 'private', '2025-05-22 03:28:47', NULL),
('683000fd53', NULL, 'private', '2025-05-23 12:00:45', NULL),
('6836a8e629', NULL, 'private', '2025-05-28 13:10:46', NULL),
('68408c3d3e', NULL, 'private', '2025-06-04 11:11:09', NULL),
('684900dfe2', NULL, 'private', '2025-06-10 21:06:55', NULL),
('684f7c34d8', NULL, 'private', '2025-06-15 19:06:45', NULL),
('685a18a21e', NULL, 'private', '2025-06-23 20:16:50', NULL),
('685a1bc800', NULL, 'private', '2025-06-23 20:30:16', NULL),
('FLY0001CRR', 'Mep Cuy', 'group', '2025-01-16 19:47:53', 'group_1747116548.png'),
('FLY0002CRR', NULL, 'private', '2025-01-16 18:07:34', NULL),
('FLY0003CRR', NULL, 'private', '2025-03-08 21:34:40', NULL),
('FLY0004CRR', NULL, 'private', '2025-01-16 18:07:34', NULL),
('FLY0005CRR', 'Fly Commuity', 'group', '2025-03-11 09:38:26', 'group_1747114578.jpg'),
('FLY0006CRR', 'Test3', 'group', '2025-05-21 21:39:26', 'group_1747841270.jpg'),
('FLY0007CRR', NULL, 'private', '2025-06-04 18:43:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chat_room_members`
--

CREATE TABLE `chat_room_members` (
  `id_chat_room` varchar(10) NOT NULL,
  `id_user` varchar(10) NOT NULL,
  `status` varchar(24) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_room_members`
--

INSERT INTO `chat_room_members` (`id_chat_room`, `id_user`, `status`) VALUES
('682e377f4c', 'FLY0001USR', ''),
('682e377f4c', 'FLY0003USR', ''),
('683000fd53', 'FLY0003USR', ''),
('683000fd53', 'FLY0006USR', ''),
('6836a8e629', 'FLY0002USR', ''),
('6836a8e629', 'FLY0006USR', ''),
('68408c3d3e', 'FLY0001USR', ''),
('68408c3d3e', 'FLY0005USR', ''),
('684900dfe2', 'FLY0005USR', ''),
('684900dfe2', 'FLY0016USR', ''),
('684f7c34d8', 'FLY0004USR', ''),
('684f7c34d8', 'FLY0015USR', ''),
('685a18a21e', 'FLY0005USR', ''),
('685a18a21e', 'FLY0018USR', ''),
('685a1bc800', 'FLY0005USR', ''),
('685a1bc800', 'FLY0019USR', ''),
('FLY0001CRR', 'FLY0001USR', 'admin'),
('FLY0001CRR', 'FLY0003USR', 'admin'),
('FLY0001CRR', 'FLY0004USR', 'member'),
('FLY0001CRR', 'FLY0005USR', 'admin'),
('FLY0002CRR', 'FLY0001USR', ''),
('FLY0002CRR', 'FLY0004USR', ''),
('FLY0003CRR', 'FLY0002USR', ''),
('FLY0003CRR', 'FLY0003USR', ''),
('FLY0004CRR', 'FLY0001USR', ''),
('FLY0004CRR', 'FLY0002USR', ''),
('FLY0005CRR', 'FLY0001USR', 'admin'),
('FLY0005CRR', 'FLY0003USR', 'member'),
('FLY0005CRR', 'FLY0005USR', 'member'),
('FLY0005CRR', 'FLY0006USR', 'member'),
('FLY0005CRR', 'FLY0015USR', 'member'),
('FLY0006CRR', 'FLY0001USR', 'admin'),
('FLY0006CRR', 'FLY0002USR', 'member'),
('FLY0006CRR', 'FLY0005USR', 'member'),
('FLY0006CRR', 'FLY0006USR', 'member'),
('FLY0007CRR', 'FLY0001USR', ''),
('FLY0007CRR', 'FLY0006USR', '');

-- --------------------------------------------------------

--
-- Table structure for table `collaboration_comment`
--

CREATE TABLE `collaboration_comment` (
  `id_comment` int(11) NOT NULL,
  `id_file` varchar(10) NOT NULL,
  `id_user` varchar(10) NOT NULL,
  `comment_text` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `id_parent` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `collaboration_comment`
--

INSERT INTO `collaboration_comment` (`id_comment`, `id_file`, `id_user`, `comment_text`, `created_at`, `id_parent`) VALUES
(1, 'FLY0002MPF', 'FLY0001USR', 'yo', '2025-01-28 14:08:05', NULL),
(2, 'FLY0002MPF', 'FLY0001USR', 'ya', '2025-01-28 14:23:51', 1),
(3, 'FLY0002MPF', 'FLY0001USR', 'nambawan', '2025-01-28 14:42:20', NULL),
(4, 'FLY0002MPF', 'FLY0001USR', 'yy', '2025-01-28 14:45:22', 3),
(6, 'FLY0001MPF', 'FLY0001USR', 'gak jelas flashdatanya', '2025-04-21 15:52:34', NULL),
(7, 'FLY0001MPF', 'FLY0001USR', 'wih', '2025-04-22 03:21:52', 6),
(10, 'FLY0001MPF', 'FLY0001USR', '1', '2025-04-22 04:10:48', NULL),
(11, 'FLY0002MPF', 'FLY0001USR', 'test', '2025-05-17 20:27:02', NULL),
(15, 'FLY0003MPF', 'FLY0001USR', 'gg', '2025-05-20 14:18:45', NULL),
(16, 'FLY0001MPF', 'FLY0006USR', '2', '2025-05-24 03:52:20', 10),
(17, 'FLY0001MPF', 'FLY0005USR', '3', '2025-05-24 03:52:47', 10),
(18, 'FLY0003MPF', 'FLY0001USR', 'yoi', '2025-06-04 13:47:49', 15);

-- --------------------------------------------------------

--
-- Table structure for table `collaboration_files`
--

CREATE TABLE `collaboration_files` (
  `id_file` varchar(10) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `view_count` int(11) DEFAULT 0,
  `upload_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `collaboration_files`
--

INSERT INTO `collaboration_files` (`id_file`, `file_name`, `thumbnail`, `title`, `description`, `view_count`, `upload_at`) VALUES
('FLY0001MPF', 'media_6792491681a9f.mp4', 'thumb_1747577929.jpeg', 'gughuu', 'uguibuiybiy', 132, '2025-01-23 14:50:14'),
('FLY0002MPF', 'media_67924a53490a7.mp4', '', 'buisdas', 'asdsvuyas disa', 40, '2025-01-23 14:55:31'),
('FLY0003MPF', 'media_682c70780d76c.mp4', 'thumb_1747743500.jpg', 'AMV Royal Levisper', 'babababababababba', 22, '2025-05-20 14:07:21');

-- --------------------------------------------------------

--
-- Table structure for table `collaboration_like`
--

CREATE TABLE `collaboration_like` (
  `id_like` int(11) NOT NULL,
  `id_file` varchar(10) NOT NULL,
  `id_user` varchar(10) NOT NULL,
  `liked_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `collaboration_like`
--

INSERT INTO `collaboration_like` (`id_like`, `id_file`, `id_user`, `liked_at`) VALUES
(4, 'FLY0002MPF', 'FLY0005USR', '2025-04-18 21:27:45'),
(5, 'FLY0002MPF', 'FLY0001USR', '2025-05-18 01:17:55'),
(6, 'FLY0001MPF', 'FLY0001USR', '2025-05-24 08:49:22'),
(7, 'FLY0001MPF', 'FLY0005USR', '2025-05-24 08:49:43'),
(8, 'FLY0001MPF', 'FLY0006USR', '2025-05-24 08:49:49'),
(9, 'FLY0003MPF', 'FLY0001USR', '2025-06-04 10:47:39'),
(11, 'FLY0003MPF', 'FLY0005USR', '2025-06-04 11:11:29'),
(12, 'FLY0002MPF', 'FLY0006USR', '2025-06-04 18:46:47');

-- --------------------------------------------------------

--
-- Table structure for table `collaboration_participant`
--

CREATE TABLE `collaboration_participant` (
  `id_collaboration` varchar(10) NOT NULL,
  `id_content` varchar(10) NOT NULL,
  `id_user` varchar(10) NOT NULL,
  `part_label` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `collaboration_participant`
--

INSERT INTO `collaboration_participant` (`id_collaboration`, `id_content`, `id_user`, `part_label`) VALUES
('FLY0001MPP', 'FLY0002MPF', 'FLY0003USR', 2),
('FLY0002MPP', 'FLY0002MPF', 'FLY0004USR', 3),
('FLY0003MPP', 'FLY0002MPF', 'FLY0002USR', 1),
('FLY0004MPP', 'FLY0001MPF', 'FLY0005USR', 1),
('FLY0005MPP', 'FLY0001MPF', 'FLY0003USR', 2),
('FLY0006MPP', 'FLY0002MPF', 'FLY0001USR', 4),
('FLY0007MPP', 'FLY0001MPF', 'FLY0001USR', 3),
('FLY0012MPP', 'FLY0003MPF', 'FLY0003USR', 1),
('FLY0013MPP', 'FLY0003MPF', 'FLY0005USR', 2),
('FLY0014MPP', 'FLY0003MPF', 'FLY0004USR', 3),
('FLY0015MPP', 'FLY0003MPF', 'FLY0006USR', 4);

-- --------------------------------------------------------

--
-- Table structure for table `creator`
--

CREATE TABLE `creator` (
  `id_team` varchar(10) NOT NULL,
  `id_user` varchar(10) DEFAULT NULL,
  `team_name` varchar(24) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `creator`
--

INSERT INTO `creator` (`id_team`, `id_user`, `team_name`, `description`, `created_at`) VALUES
('FLY0001CTR', 'FLY0003USR', 'Fly Studio', 'Ayangnya yazuvi .^.', '2025-01-07 05:06:32'),
('FLY0002CTR', 'FLY0005USR', 'Fly Studio', 'Tasya partnership', '2025-04-13 13:56:40'),
('FLY0003CTR', 'FLY0004USR', 'Fly Studio', 'New creator profile', '2025-04-13 23:40:57'),
('FLY0004CTR', 'FLY0002USR', 'Fly Studio', 'In your dream\r\n', '2025-04-14 00:23:32'),
('FLY0005CTR', 'FLY0011USR', 'Fly Studio', 'New creator profile', '2025-06-05 07:22:57'),
('FLY0006CTR', 'FLY0019USR', 'Fly Studio', 'New creator profile', '2025-06-24 07:01:01');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id_event` varchar(10) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `banner` varchar(256) NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `end_date` timestamp NULL DEFAULT NULL,
  `max_participants` int(11) NOT NULL DEFAULT 0,
  `id_category` int(11) DEFAULT NULL,
  `id_scope` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id_event`, `event_name`, `description`, `banner`, `start_date`, `end_date`, `max_participants`, `id_category`, `id_scope`, `created_at`) VALUES
('EV67837b6f', 'Mep yoasobi', 'uayayayya', '', '2025-01-12 08:39:28', '2025-04-17 18:22:00', 15, 3, 1, '2025-01-12 02:21:03'),
('EV67f717bf', 'Recruitment Vol 1', 'All style priority', 'banner_1745933644.jpg', '2025-05-27 16:58:31', '2025-05-15 18:01:00', 0, 4, 2, '2025-04-09 19:58:39'),
('EV6818c1d6', 'Mep Koiiro', 'Mep bang ', 'banner_1746534090.jpg', '2025-05-27 16:58:33', '2025-06-27 05:00:00', 10, 3, 3, '2025-05-05 08:49:10'),
('EV681c48ed', 'Battle Royal', 'asdasdsad', 'banner_1747714586.jpg', '2025-05-27 16:58:33', '2025-05-30 05:00:00', 6, 1, 2, '2025-05-08 01:02:21'),
('EV682d61f0', 'Recruitment Vol 2', 'Rawfx only', 'banner_1748441462.jpg', '2025-05-27 16:58:00', '2025-05-31 05:00:00', 0, 4, 2, '2025-05-21 00:17:36'),
('EV68411940', 'Recruitment Vol 3', 'No desc', '', '2025-06-04 19:00:00', '2025-06-20 19:00:00', 0, 4, 2, '2025-06-05 07:12:48'),
('EV685a229e', 'Recruitment Vol 4', '111', '', '2025-06-13 18:01:00', '2025-07-24 08:01:00', 0, 4, 2, '2025-06-24 06:59:26');

-- --------------------------------------------------------

--
-- Table structure for table `event_categories`
--

CREATE TABLE `event_categories` (
  `id_category` int(11) NOT NULL,
  `category_name` enum('battle','collab','mep','recruitment') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_categories`
--

INSERT INTO `event_categories` (`id_category`, `category_name`) VALUES
(1, 'battle'),
(2, 'collab'),
(3, 'mep'),
(4, 'recruitment');

-- --------------------------------------------------------

--
-- Table structure for table `event_files`
--

CREATE TABLE `event_files` (
  `id_event_file` varchar(10) NOT NULL,
  `id_event` varchar(10) DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_url` varchar(255) NOT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `file_size` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_files`
--

INSERT INTO `event_files` (`id_event_file`, `id_event`, `file_name`, `file_url`, `file_type`, `uploaded_at`, `file_size`) VALUES
('FLY0001EFL', 'EV67837b6f', 'Shut_Away_in_Summer.png', 'http://localhost/fly/./assets/uploads/FileEvent/Shut_Away_in_Summer.png', 'image/png', '2025-01-12 13:14:01', 499);

-- --------------------------------------------------------

--
-- Table structure for table `event_participants`
--

CREATE TABLE `event_participants` (
  `id_event` varchar(10) NOT NULL,
  `id_user` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_participants`
--

INSERT INTO `event_participants` (`id_event`, `id_user`) VALUES
('EV67837b6f', 'FLY0001USR'),
('EV6818c1d6', 'FLY0001USR'),
('EV681c48ed', 'FLY0001USR'),
('EV67837b6f', 'FLY0002USR'),
('EV67837b6f', 'FLY0003USR'),
('EV6818c1d6', 'FLY0003USR'),
('EV67837b6f', 'FLY0004USR'),
('EV67837b6f', 'FLY0005USR'),
('EV681c48ed', 'FLY0005USR'),
('EV681c48ed', 'FLY0006USR');

-- --------------------------------------------------------

--
-- Table structure for table `event_scope`
--

CREATE TABLE `event_scope` (
  `id_scope` int(11) NOT NULL,
  `scope_name` enum('intern','public','all') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_scope`
--

INSERT INTO `event_scope` (`id_scope`, `scope_name`) VALUES
(1, 'intern'),
(2, 'public'),
(3, 'all');

-- --------------------------------------------------------

--
-- Table structure for table `follow`
--

CREATE TABLE `follow` (
  `id_follow` int(11) NOT NULL,
  `follower_id` varchar(10) DEFAULT NULL,
  `followed_id` varchar(10) DEFAULT NULL,
  `follow_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `follow`
--

INSERT INTO `follow` (`id_follow`, `follower_id`, `followed_id`, `follow_date`) VALUES
(1, 'FLY0004USR', 'FLY0003USR', '2025-02-12 04:56:09'),
(6, 'FLY0002USR', 'FLY0003USR', '2025-03-08 09:10:31'),
(7, 'FLY0004USR', 'FLY0005USR', '2025-04-13 09:50:50'),
(8, 'FLY0002USR', 'FLY0005USR', '2025-04-14 00:41:01'),
(9, 'FLY0002USR', 'FLY0004USR', '2025-04-14 00:41:29'),
(13, 'FLY0001USR', 'FLY0005USR', '2025-05-19 07:58:21'),
(15, 'FLY0001USR', 'FLY0004USR', '2025-05-21 14:17:15'),
(16, 'FLY0003USR', 'FLY0004USR', '2025-05-22 23:43:39'),
(17, 'FLY0006USR', 'FLY0003USR', '2025-05-22 23:43:54'),
(18, 'FLY0001USR', 'FLY0003USR', '2025-06-04 20:48:59'),
(19, 'FLY0005USR', 'FLY0002USR', '2025-06-04 21:17:19'),
(20, 'FLY0001USR', 'FLY0011USR', '2025-06-05 07:36:06'),
(21, 'FLY0015USR', 'FLY0004USR', '2025-06-16 05:05:32');

-- --------------------------------------------------------

--
-- Table structure for table `komentar`
--

CREATE TABLE `komentar` (
  `id_comment` int(11) NOT NULL,
  `id_content` varchar(10) NOT NULL,
  `id_user` varchar(10) NOT NULL,
  `id_parent` int(11) DEFAULT NULL,
  `comment_text` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `komentar`
--

INSERT INTO `komentar` (`id_comment`, `id_content`, `id_user`, `id_parent`, `comment_text`, `created_at`) VALUES
(5, 'FLY0001CTN', 'FLY0003USR', NULL, 'ngeri sepuh\r\n', '2025-01-20 14:39:24'),
(6, 'FLY0001CTN', 'FLY0003USR', NULL, 'bukan sepuh', '2025-01-20 14:41:30'),
(7, 'FLY0001CTN', 'FLY0003USR', 5, 'a', '2025-01-20 14:47:44'),
(8, 'FLY0002CTN', 'FLY0003USR', NULL, 'widih', '2025-01-21 01:50:14'),
(9, 'FLY0002CTN', 'FLY0003USR', 8, 'rill coy', '2025-01-21 01:50:29'),
(10, 'FLY0002CTN', 'FLY0004USR', 8, 'ngeri puh\r\n', '2025-03-06 14:00:31'),
(11, 'FLY0002CTN', 'FLY0003USR', NULL, 'abang jago', '2025-03-10 05:41:34'),
(12, 'FLY0006CTN', 'FLY0005USR', NULL, 'akhirnya bisa upload\r\n', '2025-04-11 16:49:50'),
(13, 'FLY0006CTN', 'FLY0003USR', 12, 'wah selamat beb', '2025-04-11 16:59:03'),
(15, 'FLY0001CTN', 'FLY0005USR', 6, 'yang bener aja beb :)\r\n', '2025-04-27 14:17:04'),
(16, 'FLY0001CTN', 'FLY0005USR', NULL, 'halo beb', '2025-04-27 14:18:35'),
(17, 'FLY0001CTN', 'FLY0005USR', 16, 'semangat terus', '2025-04-27 14:19:03'),
(18, 'FLY0005CTN', 'FLY0001USR', NULL, 'first\r\n', '2025-05-13 13:20:55'),
(22, 'FLY0001CTN', 'FLY0001USR', 16, 'Bucin mulu', '2025-05-17 06:08:36'),
(25, 'FLY0006CTN', 'FLY0001USR', NULL, 'Rill sepuh ini mah', '2025-05-17 18:57:07'),
(27, 'FLY0008CTN', 'FLY0001USR', NULL, 'p', '2025-05-18 12:51:14'),
(30, 'FLY0006CTN', 'FLY0005USR', 25, 'paan dah :v', '2025-05-23 16:12:31'),
(31, 'FLY0010CTN', 'FLY0001USR', NULL, 'MengGG', '2025-06-04 19:41:40');

-- --------------------------------------------------------

--
-- Table structure for table `konten`
--

CREATE TABLE `konten` (
  `id_content` varchar(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `id_uploader` varchar(10) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `file_type` varchar(16) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `view_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `konten`
--

INSERT INTO `konten` (`id_content`, `title`, `description`, `id_uploader`, `thumbnail`, `file_type`, `file_name`, `file_url`, `created_at`, `updated_at`, `view_count`) VALUES
('FLY0001CTN', 'test2', 'asajsdoas', 'FLY0003USR', '', 'Image', 'Coruscate_DNA.jpeg', 'http://localhost/fly/./assets/uploads/Content/Coruscate_DNA.jpeg', '2025-01-17 05:26:08', '2025-06-09 22:41:45', 287),
('FLY0002CTN', 'test5', '7tyftyftyfyf', 'FLY0003USR', 'banner.jpg', 'Video', 'No_loop_!!.mp4', 'http://localhost/fly/./assets/uploads/Content/No_loop_!!.mp4', '2025-01-21 01:49:31', '2025-06-09 22:38:00', 70),
('FLY0003CTN', 'MMV', 'MMV Alia Kakurenbo', 'FLY0003USR', '', 'Video', 'mmv.mp4', 'http://localhost/fly/assets/uploads/Content/mmv.mp4', '2025-03-08 18:03:45', '2025-05-30 21:59:34', 4),
('FLY0004CTN', 'GFX UI', 'Character Usada Pekora', 'FLY0003USR', '', 'Image', 'jpg(1).jpg', 'http://localhost/fly/assets/uploads/Content/jpg(1).jpg', '2025-03-09 03:38:57', '2025-05-17 23:30:08', 3),
('FLY0005CTN', 'gambar2', 'hashajshakjs', 'FLY0003USR', '', 'Image', 'Tied_to_the_Skies.jpeg', 'http://localhost/fly/assets/uploads/Content/Tied_to_the_Skies.jpeg', '2025-03-10 05:42:41', '2025-05-13 18:20:58', 12),
('FLY0006CTN', '[AMV] Vierra - Kesepian', 'HBD to me\r\n\"Berharap bisa ngevent dan kumpul lagi\"\r\n\r\nRm/ac : Xrillz dan Dirt wp\r\nSong : Vierra - Kesepian\r\nAnime : Tamako market & Plastic Memories\r\nSw : AM 3.4.0 & CC 5.5.1\r\nCoser : @haruki_yuu\r\n', 'FLY0005USR', '', 'Video', 'test1.mp4', 'http://localhost/fly/assets/uploads/Content/test1.mp4', '2025-04-11 16:44:39', '2025-06-04 16:43:07', 29),
('FLY0007CTN', 'Yaa', 'kan udah tadi', 'FLY0001USR', '', 'Video', '205034435_222143132918124_8006115342876278401_n1.mp4', 'http://localhost/fly/assets/uploads/Content/205034435_222143132918124_8006115342876278401_n1.mp4', '2025-05-05 17:21:25', '2025-06-05 04:30:50', 85),
('FLY0008CTN', 'Yayaa', 'Dimari\r\nKemari\r\nMari', 'FLY0001USR', '', 'Video', 'Fashion_Week_-_AMV_UI___Aesthetic_Alight_Motion.mp4', 'http://localhost/fly/assets/uploads/Content/Fashion_Week_-_AMV_UI___Aesthetic_Alight_Motion.mp4', '2025-05-15 19:49:59', '2025-06-04 16:40:56', 23),
('FLY0009CTN', 'AMV Candy', 'style lakik nih boss senggol dong :v', 'FLY0001USR', 'HD-wallpaper-sword-art-online-sword-art-online-ii-yuuki-konno1.jpg', 'Video', 'Umaru_Chan_-_Candy_Style_AMV_(Free_PF_Read_Desc).mp4', 'http://localhost/fly/assets/uploads/Content/Umaru_Chan_-_Candy_Style_AMV_(Free_PF_Read_Desc).mp4', '2025-05-16 07:45:04', '2025-05-18 19:08:58', 36),
('FLY0010CTN', 'Ngetest', 'yyyyyyyyyyyyyyyy', 'FLY0005USR', '', 'Video', '_hapuskan_stiap_luka_c_-_@dest_rxxy__amv_amvanime_edit_anime_animeedits.mp4', 'http://localhost/fly/assets/uploads/Content/_hapuskan_stiap_luka_c_-_@dest_rxxy__amv_amvanime_edit_anime_animeedits.mp4', '2025-05-23 16:34:55', '2025-06-23 18:43:08', 8),
('FLY0011CTN', 'HBD tester', 'yayayaya', 'FLY0005USR', '', 'Video', 'test.mp4', 'https://flystudioamv.ct.ws/assets/uploads/Content/test.mp4', '2025-06-23 22:01:18', '2025-06-23 19:02:47', 2);

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id_like` int(11) NOT NULL,
  `id_content` varchar(10) NOT NULL,
  `id_user` varchar(10) NOT NULL,
  `liked_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id_like`, `id_content`, `id_user`, `liked_at`) VALUES
(4, 'FLY0001CTN', 'FLY0003USR', '2025-01-18 21:40:09'),
(5, 'FLY0002CTN', 'FLY0003USR', '2025-01-21 07:50:00'),
(7, 'FLY0002CTN', 'FLY0004USR', '2025-03-06 19:59:59'),
(8, 'FLY0001CTN', 'FLY0002USR', '2025-03-08 21:04:19'),
(9, 'FLY0003CTN', 'FLY0003USR', '2025-03-09 00:08:01'),
(10, 'FLY0004CTN', 'FLY0003USR', '2025-03-10 11:43:29'),
(11, 'FLY0006CTN', 'FLY0005USR', '2025-04-11 21:50:40'),
(12, 'FLY0006CTN', 'FLY0003USR', '2025-04-11 21:56:47'),
(15, 'FLY0005CTN', 'FLY0001USR', '2025-05-10 21:43:30'),
(43, 'FLY0001CTN', 'FLY0001USR', '2025-05-16 14:46:15'),
(46, 'FLY0007CTN', 'FLY0001USR', '2025-05-17 07:38:19'),
(48, 'FLY0006CTN', 'FLY0001USR', '2025-05-17 23:29:49'),
(50, 'FLY0008CTN', 'FLY0001USR', '2025-05-18 18:00:20'),
(51, 'FLY0006CTN', 'FLY0006USR', '2025-05-23 21:08:16'),
(52, 'FLY0002CTN', 'FLY0001USR', '2025-06-04 10:46:36'),
(53, 'FLY0010CTN', 'FLY0001USR', '2025-06-04 16:41:26'),
(54, 'FLY0011CTN', 'FLY0005USR', '2025-06-23 19:02:46');

-- --------------------------------------------------------

--
-- Table structure for table `medsos`
--

CREATE TABLE `medsos` (
  `id_medsos` varchar(10) NOT NULL,
  `id_user` varchar(10) DEFAULT NULL,
  `platform` varchar(50) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medsos`
--

INSERT INTO `medsos` (`id_medsos`, `id_user`, `platform`, `url`) VALUES
('FLY0001MDS', 'FLY0003USR', 'YouTube', 'https://www.youtube.com/@Son1xEdits'),
('FLY0002MDS', 'FLY0003USR', 'Instagram', 'https://www.instagram.com/fly.y4z_4k3/'),
('FLY0003MDS', 'FLY0005USR', 'Instagram', 'https://www.instagram.com/fly.y4z_4k3/'),
('FLY0004MDS', 'FLY0005USR', 'Facebook', 'https://www.facebook.com/enju.aihara.5836/'),
('FLY0005MDS', 'FLY0001USR', 'Instagram', 'https://www.instagram.com/vinsce.mv_/'),
('FLY0006MDS', 'FLY0001USR', 'Facebook', 'https://www.facebook.com/Vinsce/');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id_messages` int(11) NOT NULL,
  `id_chat_room` varchar(10) DEFAULT NULL,
  `id_user` varchar(10) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id_messages`, `id_chat_room`, `id_user`, `message`, `created_at`) VALUES
(8, 'FLY0004CRR', 'FLY0001USR', 'yo', '2025-01-15 00:19:07'),
(9, 'FLY0004CRR', 'FLY0002USR', 'halo min', '2025-01-15 00:25:17'),
(10, 'FLY0004CRR', 'FLY0002USR', 'kenalin Sinta ', '2025-01-15 00:26:16'),
(11, 'FLY0002CRR', 'FLY0001USR', 'hai', '2025-01-16 01:15:47'),
(12, 'FLY0002CRR', 'FLY0004USR', 'hai juga', '2025-01-16 01:17:35'),
(13, 'FLY0001CRR', 'FLY0001USR', 'info2', '2025-01-16 07:05:00'),
(14, 'FLY0001CRR', 'FLY0003USR', 'ya ada apa?', '2025-01-16 09:02:12'),
(15, 'FLY0001CRR', 'FLY0004USR', 'halo', '2025-01-16 20:30:14'),
(16, 'FLY0002CRR', 'FLY0001USR', 'oi', '2025-02-17 21:14:08'),
(17, 'FLY0001CRR', 'FLY0003USR', 'gas mep yok', '2025-02-17 23:42:13'),
(18, 'FLY0001CRR', 'FLY0001USR', 'oiiiiiiiiiii', '2025-02-20 06:48:18'),
(19, 'FLY0001CRR', 'FLY0001USR', 'anjay bisa coy', '2025-02-20 06:48:42'),
(20, 'FLY0001CRR', 'FLY0003USR', 'hai ayank', '2025-03-02 01:22:42'),
(21, 'FLY0003CRR', 'FLY0003USR', 'halo ada apa', '2025-03-08 08:35:09'),
(22, 'FLY0003CRR', 'FLY0002USR', 'Bisa all style kah kak?', '2025-03-08 08:45:48'),
(23, 'FLY0003CRR', 'FLY0003USR', 'bisa', '2025-03-08 10:10:59'),
(24, 'FLY0003CRR', 'FLY0002USR', 'oi', '2025-03-09 22:37:36'),
(25, 'FLY0005CRR', 'FLY0005USR', 'wi', '2025-04-20 09:28:07'),
(26, 'FLY0005CRR', 'FLY0001USR', 'malas menanggapi', '2025-05-10 10:26:49'),
(27, 'FLY0005CRR', 'FLY0001USR', '123', '2025-05-18 03:47:09'),
(28, 'FLY0005CRR', 'FLY0001USR', 'a', '2025-05-18 03:47:23'),
(29, 'FLY0005CRR', 'FLY0001USR', 'b', '2025-05-18 03:47:32'),
(30, 'FLY0005CRR', 'FLY0001USR', 'c', '2025-05-18 03:47:42'),
(31, 'FLY0006CRR', 'FLY0001USR', 'woi', '2025-05-21 10:28:12'),
(32, 'FLY0003CRR', 'FLY0003USR', 'paan:v', '2025-05-21 15:33:25'),
(33, 'FLY0005CRR', 'FLY0003USR', 'brisik anying', '2025-05-21 15:38:37'),
(34, '683000fd53', 'FLY0003USR', 'test', '2025-05-23 00:01:09'),
(35, '683000fd53', 'FLY0006USR', 'ya', '2025-05-23 00:01:52'),
(36, '6836a8e629', 'FLY0006USR', 'pagi', '2025-05-28 01:11:48'),
(37, '6836a8e629', 'FLY0006USR', 'pesen logo klinik', '2025-05-28 01:12:53'),
(38, '6836a8e629', 'FLY0002USR', 'logo kyk gimana', '2025-05-28 01:13:19'),
(39, '6836a8e629', 'FLY0006USR', 'yg simple warna putih biru', '2025-05-28 01:13:48'),
(40, '6836a8e629', 'FLY0002USR', 'okey budget berapqn kak', '2025-05-28 01:14:31'),
(41, '6836a8e629', 'FLY0006USR', '200 rb aja', '2025-05-28 01:14:52'),
(42, '6836a8e629', 'FLY0002USR', 'okey gas order kak', '2025-05-28 01:15:35'),
(43, '684900dfe2', 'FLY0016USR', 'Halo, mau pesan desain logo , dlm waktu 1 hari', '2025-06-11 07:07:46'),
(44, '684900dfe2', 'FLY0005USR', 'Logo kayak gimana kak?', '2025-06-11 07:08:15'),
(45, '684900dfe2', 'FLY0016USR', 'Logo inisial nama, hrg brp?', '2025-06-11 07:09:00'),
(46, '684900dfe2', 'FLY0005USR', 'Antara 25k - 100k tergantung detail kak', '2025-06-11 07:09:42'),
(47, '684900dfe2', 'FLY0016USR', 'Okay deal', '2025-06-11 07:10:10'),
(48, 'FLY0005CRR', 'FLY0006USR', 'test bang', '2025-06-15 16:51:40'),
(49, 'FLY0005CRR', 'FLY0005USR', 'yo cba', '2025-06-15 16:52:00'),
(50, 'FLY0005CRR', 'FLY0005USR', 'njr bisa coy', '2025-06-15 16:52:22'),
(51, 'FLY0005CRR', 'FLY0001USR', 'Apanya', '2025-06-15 17:06:08'),
(52, 'FLY0005CRR', 'FLY0001USR', 'Oi apa woy', '2025-06-15 17:06:33'),
(53, 'FLY0005CRR', 'FLY0006USR', 'ndak min cmn chatnya', '2025-06-15 17:08:56'),
(54, 'FLY0005CRR', 'FLY0005USR', 'otsu', '2025-06-15 18:03:49'),
(55, 'FLY0005CRR', 'FLY0006USR', 'otsu juga bag', '2025-06-15 18:04:07'),
(56, 'FLY0005CRR', 'FLY0006USR', 'otsu juga bang', '2025-06-15 18:04:16'),
(57, 'FLY0005CRR', 'FLY0006USR', 'otsu juga bang', '2025-06-15 18:04:16'),
(58, '684f7c34d8', 'FLY0015USR', 'P Bakso', '2025-06-16 05:06:53'),
(59, '684f7c34d8', 'FLY0004USR', 'Op', '2025-06-16 05:07:01'),
(60, '684f7c34d8', 'FLY0015USR', 'testerr', '2025-06-16 05:07:23'),
(61, 'FLY0005CRR', 'FLY0001USR', 'Test', '2025-06-16 05:09:15'),
(62, 'FLY0005CRR', 'FLY0015USR', 'Hallo', '2025-06-16 05:09:23'),
(63, '683000fd53', 'FLY0006USR', 'gpp', '2025-06-17 03:53:36'),
(64, '683000fd53', 'FLY0003USR', 'apanya?', '2025-06-17 04:03:19'),
(65, '683000fd53', 'FLY0003USR', 'oiii', '2025-06-17 05:26:50'),
(66, '683000fd53', 'FLY0006USR', 'gakpapa bng cmn test doang', '2025-06-17 05:40:29'),
(67, '683000fd53', 'FLY0003USR', 'yang bener?', '2025-06-17 05:40:51'),
(68, '682e377f4c', 'FLY0003USR', 'oiii min', '2025-06-17 05:41:28'),
(69, '683000fd53', 'FLY0006USR', 'yoi bng', '2025-06-17 06:27:46'),
(70, '683000fd53', 'FLY0003USR', 'oalah okey bang', '2025-06-17 06:41:11'),
(71, 'FLY0005CRR', 'FLY0003USR', 'yoo', '2025-06-17 07:21:16'),
(72, '683000fd53', 'FLY0006USR', 'hehe:)', '2025-06-17 07:32:22'),
(73, '683000fd53', 'FLY0003USR', 'lah', '2025-06-17 07:35:09'),
(74, '685a18a21e', 'FLY0018USR', 'test', '2025-06-24 06:27:35'),
(75, '685a1bc800', 'FLY0019USR', 'tolong buatkan logo untuk toko fashion untuk produk pakaian genZ', '2025-06-24 06:31:37'),
(76, '685a1bc800', 'FLY0005USR', 'baik untuk price nya bisa dari 15k - 100k', '2025-06-24 06:35:13'),
(77, '685a1bc800', 'FLY0019USR', 'yang 95k budgetnya', '2025-06-24 06:36:04');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id_payment` varchar(10) NOT NULL,
  `id_transaction` varchar(10) NOT NULL,
  `id_user` varchar(10) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `payment_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id_payment`, `id_transaction`, `id_user`, `amount`, `payment_proof`, `payment_status`, `payment_date`) VALUES
('FLY0001PYM', 'FLY0001TRS', 'FLY0002USR', '100000.00', 'assets/uploads/Payment/payment_1741402247_FLY0002USR.jpeg', 'approved', '2025-03-07 20:50:47'),
('FLY0002PYM', 'FLY0001TRS', 'FLY0002USR', '200000.00', 'assets/uploads/Payment/payment_1741406421_FLY0002USR.jpeg', 'approved', '2025-03-07 22:00:21'),
('FLY0003PYM', 'FLY0002TRS', 'FLY0001USR', '200000.00', 'assets/uploads/Payment/payment_1745461321_FLY0001USR.jpg', 'approved', '2025-04-23 21:22:01'),
('FLY0004PYM', 'FLY0002TRS', 'FLY0001USR', '150000.00', 'assets/uploads/Payment/payment_1745497146_FLY0001USR.jpg', 'approved', '2025-04-24 07:19:06'),
('FLY0005PYM', 'FLY0002TRS', 'FLY0001USR', '150000.00', 'assets/uploads/Payment/payment_1745497631_FLY0001USR.jpg', 'approved', '2025-04-24 07:27:12'),
('FLY0006PYM', 'FLY0003TRS', 'FLY0001USR', '300000.00', 'assets/uploads/Payment/payment_1747050195_FLY0001USR.jpg', 'rejected', '2025-05-12 06:43:16'),
('FLY0007PYM', 'FLY0004TRS', 'FLY0005USR', '200000.00', 'assets/uploads/Payment/payment_1747295536_FLY0005USR.jpg', 'approved', '2025-05-15 02:52:16'),
('FLY0008PYM', 'FLY0005TRS', 'FLY0006USR', '200000.00', 'assets/uploads/Payment/payment_1748116368_FLY0006USR.jpg', 'approved', '2025-05-24 14:52:49'),
('FLY0009PYM', 'FLY0005TRS', 'FLY0006USR', '100000.00', 'assets/uploads/Payment/payment_1748116425_FLY0006USR.png', 'approved', '2025-05-24 14:53:46'),
('FLY0010PYM', 'FLY0006TRS', 'FLY0006USR', '100000.00', 'assets/uploads/Payment/payment_1748411502_FLY0006USR.png', 'approved', '2025-05-28 00:51:43'),
('FLY0011PYM', 'FLY0006TRS', 'FLY0006USR', '100000.00', 'assets/uploads/Payment/payment_1748411712_FLY0006USR.png', 'approved', '2025-05-28 00:55:12'),
('FLY0012PYM', 'FLY0007TRS', 'FLY0006USR', '200000.00', 'assets/uploads/Payment/payment_1748413134_FLY0006USR.png', 'approved', '2025-05-28 01:18:54'),
('FLY0013PYM', 'FLY0009TRS', 'FLY0016USR', '25000.00', 'assets/uploads/Payment/payment_1749615628_FLY0016USR.jpg', 'approved', '2025-06-11 07:20:28'),
('FLY0014PYM', 'FLY0010TRS', 'FLY0015USR', '5000.00', 'assets/uploads/Payment/payment_1750040008_FLY0015USR.PNG', 'approved', '2025-06-16 05:13:28'),
('FLY0015PYM', 'FLY0010TRS', 'FLY0015USR', '400000.00', 'assets/uploads/Payment/payment_1750040050_FLY0015USR.PNG', 'approved', '2025-06-16 05:14:10'),
('FLY0016PYM', 'FLY0010TRS', 'FLY0015USR', '95000.00', 'assets/uploads/Payment/payment_1750040071_FLY0015USR.PNG', 'approved', '2025-06-16 05:14:31'),
('FLY0017PYM', 'FLY0012TRS', 'FLY0019USR', '95000.00', 'assets/uploads/Payment/payment_1750736409_FLY0019USR.png', 'approved', '2025-06-24 06:40:09');

-- --------------------------------------------------------

--
-- Table structure for table `recruitment_approvals`
--

CREATE TABLE `recruitment_approvals` (
  `id_recruit` varchar(10) NOT NULL,
  `id_user` varchar(10) DEFAULT NULL,
  `id_event` varchar(10) NOT NULL,
  `id_admin` varchar(10) DEFAULT NULL,
  `work_url` varchar(255) NOT NULL,
  `reason_text` text NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT NULL,
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `decision_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recruitment_approvals`
--

INSERT INTO `recruitment_approvals` (`id_recruit`, `id_user`, `id_event`, `id_admin`, `work_url`, `reason_text`, `status`, `applied_at`, `decision_at`) VALUES
('FLY0001REC', 'FLY0005USR', 'EV67f717bf', 'FLY0001USR', 'https://www.instagram.com/p/CdTHVn9gC1q/', 'biar deket ama ayang\r\n', 'approved', '2025-04-10 12:48:05', '2025-04-11 15:16:06'),
('FLY0002REC', 'FLY0004USR', 'EV67f717bf', 'FLY0001USR', 'https://www.instagram.com/p/DGHl53yTbBl/', 'biar jadi elitos', 'approved', '2025-04-13 10:35:17', '2025-04-14 06:40:56'),
('FLY0003REC', 'FLY0002USR', 'EV67f717bf', 'FLY0001USR', 'https://www.instagram.com/p/CZ_rNuHBlM_/', 'ajarin dong puh sepuh', 'approved', '2025-04-14 00:15:33', '2025-04-14 07:23:31'),
('FLY0004REC', 'FLY0006USR', 'EV682d61f0', 'FLY0001USR', 'https://www.instagram.com/p/CZ_rNuHBlM_/', 'nyoba doang\r\n', 'rejected', '2025-05-24 15:37:37', '2025-05-25 03:47:33'),
('FLY0005REC', 'FLY0011USR', 'EV68411940', 'FLY0001USR', 'https://www.instagram.com/reel/DKbvUibhXQI/?igsh=MTY0NnRyam1jaWV3Mw==', 'ingin menjadi elit', 'approved', '2025-06-05 07:15:23', '2025-06-05 00:22:57'),
('FLY0006REC', 'FLY0019USR', 'EV685a229e', 'FLY0001USR', 'https://www.instagram.com/p/DGHl53yTbBl/', 'ya', 'approved', '2025-06-24 07:00:40', '2025-06-24 00:01:01');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id_transaction` varchar(10) NOT NULL,
  `id_orderer` varchar(10) DEFAULT NULL,
  `id_worker` varchar(10) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `total_paid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('pending','partially_paid','paid','canceled') NOT NULL DEFAULT 'pending',
  `revision_count` int(11) DEFAULT 0,
  `max_revision` int(11) DEFAULT 4,
  `order_file_url` varchar(255) DEFAULT NULL,
  `order_status` enum('pending','in_progress','completed','cancelled') DEFAULT 'pending',
  `password` varchar(24) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id_transaction`, `id_orderer`, `id_worker`, `total_price`, `total_paid`, `payment_status`, `revision_count`, `max_revision`, `order_file_url`, `order_status`, `password`, `created_at`, `updated_at`) VALUES
('FLY0001TRS', 'FLY0002USR', 'FLY0003USR', '300000.00', '300000.00', 'pending', 0, 4, 'https://drive.google.com/drive/folders/1BJug_GLiEc6Ls3K0un5KdW36WFKF_WDU', 'completed', 'sayangaku', '2025-03-01 07:09:10', '2025-04-23 03:11:17'),
('FLY0002TRS', 'FLY0001USR', 'FLY0005USR', '500000.00', '500000.00', 'pending', 0, 4, 'https://drive.google.com/drive/folders/1BJug_GLiEc6Ls3K0un5KdW36WFKF_WDU?usp=drive_link', 'completed', 'tetew123', '2025-04-23 08:13:25', '2025-04-24 13:17:30'),
('FLY0003TRS', 'FLY0001USR', 'FLY0004USR', '300000.00', '0.00', 'pending', 0, 4, '---', 'in_progress', '---', '2025-04-29 00:21:27', '2025-04-29 05:24:36'),
('FLY0004TRS', 'FLY0005USR', 'FLY0001USR', '200000.00', '200000.00', 'pending', 0, 4, 'https://drive.google.com/drive/folders/1BJug_GLiEc6Ls3K0un5KdW36WFKF_WDU?usp=drive_link', 'completed', 'testtest', '2025-05-15 02:32:17', '2025-05-24 16:36:35'),
('FLY0005TRS', 'FLY0006USR', 'FLY0001USR', '300000.00', '300000.00', 'pending', 0, 4, 'https://drive.google.com/drive/folders/1BJug_GLiEc6Ls3K0un5KdW36WFKF_WDU?usp=drive_link', 'completed', '1231231223', '2025-05-24 14:22:05', '2025-05-24 19:56:16'),
('FLY0006TRS', 'FLY0006USR', 'FLY0001USR', '200000.00', '200000.00', 'pending', 0, 4, 'https://drive.google.com/drive/folders/1BJug_GLiEc6Ls3K0un5KdW36WFKF_WDU?usp=drive_link', 'completed', '123123', '2025-05-28 00:49:18', '2025-05-28 05:56:03'),
('FLY0007TRS', 'FLY0006USR', 'FLY0002USR', '200000.00', '200000.00', 'pending', 0, 4, '---', 'in_progress', '11111', '2025-05-28 01:16:47', '2025-05-28 06:20:42'),
('FLY0008TRS', 'FLY0013USR', 'FLY0001USR', '0.00', '0.00', 'pending', 0, 4, NULL, 'pending', '', '2025-06-07 15:11:47', '2025-06-07 12:11:47'),
('FLY0009TRS', 'FLY0016USR', 'FLY0005USR', '25000.00', '25000.00', 'pending', 0, 4, 'https://drive.google.com/file/d/1PkYhDnOvq7acGHELtU3kvNigCsADYwDa/view?usp=drivesdk', 'completed', 'logo123', '2025-06-11 07:10:23', '2025-06-11 04:21:00'),
('FLY0010TRS', 'FLY0015USR', 'FLY0004USR', '500000.00', '500000.00', 'pending', 0, 4, 'https://drive.google.com/file/d/1PkYhDnOvq7acGHELtU3kvNigCsADYwDa/view?usp=drivesdk', 'completed', 'fhfhhjg', '2025-06-16 05:11:17', '2025-06-16 02:14:52'),
('FLY0011TRS', 'FLY0018USR', 'FLY0005USR', '50000.00', '0.00', 'pending', 0, 4, '-----', 'in_progress', '11111', '2025-06-24 06:18:41', '2025-06-24 03:19:25'),
('FLY0012TRS', 'FLY0019USR', 'FLY0005USR', '95000.00', '95000.00', 'pending', 0, 4, 'https://drive.google.com/drive/folders/1BJug_GLiEc6Ls3K0un5KdW36WFKF_WDU?usp=drive_link', 'completed', '111111', '2025-06-24 06:36:25', '2025-06-24 03:42:10');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` varchar(10) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(12) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `role` enum('user','creator','admin') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `email`, `password`, `password_hash`, `name`, `profile_picture`, `role`, `created_at`) VALUES
('FLY0001USR', 'vinsce@gmail.com', 'vinsce123', '$2y$10$Q0ob/pkG8qybcQrdOGThbOrNvzR5iSR600r6g.lE4OwxFO8hLcH9u', 'Vinsce', '1b74d2ed98dbf728b33ef10d6c7347b4.jpeg', 'admin', '2025-01-11 06:46:53'),
('FLY0002USR', 'sinta@example.com', 'sinta123', '$2y$10$daSUGKn3hDUSA0BAAVm0xOzltxYR7Ao0w.iy2AztIeLPcE54rQD7O', 'Sinta', 'b2bf74908cf10c0e0da88ad9957e5a9c.jpeg', 'creator', '2025-01-14 02:01:03'),
('FLY0003USR', 'tasya@example.com', 'tasya123', '$2y$10$rIm/2OvQUvAxHcEFCL2ate0xvgXaD8h1IS4Fctnue/McAZOSOvLh6', 'Tasya', 'ca67a2f2b9e5afefd9f0d77f7d324a27.jpeg', 'creator', '2025-01-10 22:21:11'),
('FLY0004USR', 'carissa@example.com', 'carissa123', '$2y$10$7oUAL7M69aniLkZoRY60gezZfOYRgjYKH9cNpKLltIJr70M6t8Kfq', 'Carissa', '4294b921c8fdc613545b1babdd37ad90.jpeg', 'creator', '2025-01-12 03:23:08'),
('FLY0005USR', 'yazuvi@example.com', 'yazuvi123', '$2y$10$uLrfIWkWY/aE0UlxnSV2d.O7Slf2pq7SiFlFGFiKf5yGL6nqXrGhW', 'Yazuvi', '7c12e7083123d56b6630b989863ef73e.jpeg', 'creator', '2025-03-09 05:42:18'),
('FLY0006USR', 'kayla@example.com', 'kayla123', '$2y$10$RzcQmOk6igOpudF8YMNPoOXcJThb/cyy1fvJOx/2cAiNF5yP117RS', 'Kayla', '8f6398b89b25d9a034c84055f24f4689.png', 'user', '2025-02-23 22:26:58'),
('FLY0007USR', 'baskom@gmail.com', 'baskom123', '$2y$10$1VEHe0FAa0du2bF3mLakd.lBS3XLM9efBsVNiojXjNowkILtd9Weq', 'Baskom', NULL, 'admin', '2025-05-30 13:23:57'),
('FLY0008USR', 'levi@gmail.com', 'levi1234', '$2y$10$mhG1rT8ulr0hgemyJVtyYO8zg8hanTbS44JSzDZH31FV9yaQSwc..', 'Levi', NULL, 'admin', '2025-05-30 13:25:01'),
('FLY0009USR', 'doni@example.com', 'doni1234', '$2y$10$Sz4zAWC0DiJbDl7DsNwTlOM31jsQsbklpnDYZh6HhYP2yb0Lq2re.', 'Doni', NULL, 'user', '2025-06-05 04:55:29'),
('FLY0010USR', 'yehezkielt120@gmail.com', 'yehezkiel123', '$2y$10$TtBmszRG4dJjDTVR7edP1uJnNTegzUF/pg0RA5iZFTtaoXUHLur2e', 'Yehezkielz', NULL, 'user', '2025-06-05 06:53:37'),
('FLY0011USR', 'justrann25@gmail.com', 'yrn2525', '$2y$10$u81rvPRgCPQRT45dtn/Fj.I1GmKBmPCTb7jNMiz9mZWrTQqDFC5he', 'Rann', '6369f9f52d2934b46814e98faffabc14.png', 'creator', '2025-06-05 07:07:31'),
('FLY0012USR', 'irishbella2701@gmail.com', 'YukinaMinato', '$2y$10$Jj796hLAIBZaYe4nLZ/D/eNylNyCywX/laN4sw1YJJ20OEkuXsAMS', 'Samna', NULL, 'user', '2025-06-05 14:28:43'),
('FLY0013USR', 'bebas@gmail.com', '1234', '$2y$10$hVuJdYYdD.37V6lpfncEd.sjHefV01hR6u1e6vG/3amO43rbxB4pq', 'LightFury', NULL, 'user', '2025-06-07 15:04:00'),
('FLY0014USR', 'email@gmail.com', 'email', '$2y$10$mLCl6la5tfnsW95vUBuvsOHamA29zbW4caMlqMHApxhoVsyIcBz6e', 'Daniel', NULL, 'user', '2025-06-10 08:42:53'),
('FLY0015USR', 'email@email.com', 'email', '$2y$10$.T7ppn3mmHSjwNeZWmlCVOUMUv/zGR7aqURnz4a4vesFkcz83hzWq', 'Daniel', NULL, 'user', '2025-06-10 08:43:16'),
('FLY0016USR', 'santiwinarsih10@gmail.com', 'santi', '$2y$10$v2y/yfrUNYDjiUxNINCNwui4gOCsqS4BvT27N4SEqIky9cIrE5py2', 'Santi', '5374c84612c244f32ecb4f89e9fd5828.jpg', 'user', '2025-06-11 07:00:44'),
('FLY0017USR', 'vanoxnox123@gmail.com', 'hisyamvanox1', '$2y$10$maJuEebKkLzkYm0Xwy9X2ucIAB7JA2EG/aIsvP.Ab.3avbus4hiqe', 'Vanox', NULL, 'user', '2025-06-11 19:20:09'),
('FLY0018USR', 'dina@example.com', 'dina123', '$2y$10$gdr98r4taKC1SWb4SfwiaeVc74aPObDjwxh83ktyvt1QZYJifbB3C', 'dina', NULL, 'user', '2025-06-24 06:13:12'),
('FLY0019USR', 'retno@example.com', 'retno123', '$2y$10$y8/HqEKXTGz3LBZrv/X47OrnTwmzuxxiMtkgEJ.e7JGHGOSzzu62G', 'Retno', NULL, 'creator', '2025-06-24 06:29:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD KEY `adm_id_user` (`id_user`);

--
-- Indexes for table `chat_requests`
--
ALTER TABLE `chat_requests`
  ADD PRIMARY KEY (`id_chat_request`),
  ADD KEY `cr_id_receiver` (`id_receiver`),
  ADD KEY `cr_id_requester` (`id_requester`);

--
-- Indexes for table `chat_rooms`
--
ALTER TABLE `chat_rooms`
  ADD PRIMARY KEY (`id_room`);

--
-- Indexes for table `chat_room_members`
--
ALTER TABLE `chat_room_members`
  ADD PRIMARY KEY (`id_chat_room`,`id_user`),
  ADD KEY `crm_id_user` (`id_user`);

--
-- Indexes for table `collaboration_comment`
--
ALTER TABLE `collaboration_comment`
  ADD PRIMARY KEY (`id_comment`),
  ADD KEY `cc_id_file` (`id_file`),
  ADD KEY `cc_id_user` (`id_user`),
  ADD KEY `cc_id_parent` (`id_parent`);

--
-- Indexes for table `collaboration_files`
--
ALTER TABLE `collaboration_files`
  ADD PRIMARY KEY (`id_file`);

--
-- Indexes for table `collaboration_like`
--
ALTER TABLE `collaboration_like`
  ADD PRIMARY KEY (`id_like`),
  ADD UNIQUE KEY `id_file` (`id_file`,`id_user`),
  ADD KEY `cl_id_user` (`id_user`);

--
-- Indexes for table `collaboration_participant`
--
ALTER TABLE `collaboration_participant`
  ADD PRIMARY KEY (`id_collaboration`),
  ADD KEY `cp_id_content` (`id_content`),
  ADD KEY `cp_id_user` (`id_user`);

--
-- Indexes for table `creator`
--
ALTER TABLE `creator`
  ADD PRIMARY KEY (`id_team`),
  ADD KEY `cre_id_user` (`id_user`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id_event`),
  ADD KEY `id_category` (`id_category`),
  ADD KEY `id_scope` (`id_scope`);

--
-- Indexes for table `event_categories`
--
ALTER TABLE `event_categories`
  ADD PRIMARY KEY (`id_category`);

--
-- Indexes for table `event_files`
--
ALTER TABLE `event_files`
  ADD PRIMARY KEY (`id_event_file`),
  ADD KEY `eve_id_event` (`id_event`);

--
-- Indexes for table `event_participants`
--
ALTER TABLE `event_participants`
  ADD PRIMARY KEY (`id_event`,`id_user`),
  ADD KEY `ep_id_user` (`id_user`);

--
-- Indexes for table `event_scope`
--
ALTER TABLE `event_scope`
  ADD PRIMARY KEY (`id_scope`);

--
-- Indexes for table `follow`
--
ALTER TABLE `follow`
  ADD PRIMARY KEY (`id_follow`),
  ADD UNIQUE KEY `follower_id` (`follower_id`,`followed_id`),
  ADD KEY `flw_followed_id` (`followed_id`);

--
-- Indexes for table `komentar`
--
ALTER TABLE `komentar`
  ADD PRIMARY KEY (`id_comment`),
  ADD KEY `kmn_id_content` (`id_content`),
  ADD KEY `kmn_id_user` (`id_user`),
  ADD KEY `kmn_id_parent` (`id_parent`);

--
-- Indexes for table `konten`
--
ALTER TABLE `konten`
  ADD PRIMARY KEY (`id_content`),
  ADD KEY `ktn_id_uploader` (`id_uploader`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id_like`),
  ADD UNIQUE KEY `id_content` (`id_content`,`id_user`),
  ADD KEY `lik_id_user` (`id_user`);

--
-- Indexes for table `medsos`
--
ALTER TABLE `medsos`
  ADD PRIMARY KEY (`id_medsos`),
  ADD KEY `mds_id_user` (`id_user`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id_messages`),
  ADD KEY `msg_id_chat_room` (`id_chat_room`),
  ADD KEY `msg_id_user` (`id_user`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id_payment`),
  ADD KEY `id_transaction` (`id_transaction`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `recruitment_approvals`
--
ALTER TABLE `recruitment_approvals`
  ADD PRIMARY KEY (`id_recruit`),
  ADD KEY `ra_id_user` (`id_user`),
  ADD KEY `ra_id_admin` (`id_admin`),
  ADD KEY `ra_id_event` (`id_event`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id_transaction`),
  ADD KEY `trs_id_orderer` (`id_orderer`),
  ADD KEY `trs_id_worker` (`id_worker`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `collaboration_comment`
--
ALTER TABLE `collaboration_comment`
  MODIFY `id_comment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `collaboration_like`
--
ALTER TABLE `collaboration_like`
  MODIFY `id_like` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `event_categories`
--
ALTER TABLE `event_categories`
  MODIFY `id_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `event_scope`
--
ALTER TABLE `event_scope`
  MODIFY `id_scope` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `follow`
--
ALTER TABLE `follow`
  MODIFY `id_follow` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `komentar`
--
ALTER TABLE `komentar`
  MODIFY `id_comment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id_like` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id_messages` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `adm_id_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `chat_requests`
--
ALTER TABLE `chat_requests`
  ADD CONSTRAINT `cr_id_receiver` FOREIGN KEY (`id_receiver`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cr_id_requester` FOREIGN KEY (`id_requester`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `chat_room_members`
--
ALTER TABLE `chat_room_members`
  ADD CONSTRAINT `crm_id_chat_room` FOREIGN KEY (`id_chat_room`) REFERENCES `chat_rooms` (`id_room`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `crm_id_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `collaboration_comment`
--
ALTER TABLE `collaboration_comment`
  ADD CONSTRAINT `cc_id_file` FOREIGN KEY (`id_file`) REFERENCES `collaboration_files` (`id_file`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cc_id_parent` FOREIGN KEY (`id_parent`) REFERENCES `collaboration_comment` (`id_comment`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cc_id_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `collaboration_like`
--
ALTER TABLE `collaboration_like`
  ADD CONSTRAINT `cl_id_file` FOREIGN KEY (`id_file`) REFERENCES `collaboration_files` (`id_file`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cl_id_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `collaboration_participant`
--
ALTER TABLE `collaboration_participant`
  ADD CONSTRAINT `cp_id_content` FOREIGN KEY (`id_content`) REFERENCES `collaboration_files` (`id_file`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cp_id_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `creator`
--
ALTER TABLE `creator`
  ADD CONSTRAINT `cre_id_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`id_category`) REFERENCES `event_categories` (`id_category`),
  ADD CONSTRAINT `events_ibfk_2` FOREIGN KEY (`id_scope`) REFERENCES `event_scope` (`id_scope`);

--
-- Constraints for table `event_files`
--
ALTER TABLE `event_files`
  ADD CONSTRAINT `eve_id_event` FOREIGN KEY (`id_event`) REFERENCES `events` (`id_event`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_participants`
--
ALTER TABLE `event_participants`
  ADD CONSTRAINT `ep_id_event` FOREIGN KEY (`id_event`) REFERENCES `events` (`id_event`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ep_id_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `follow`
--
ALTER TABLE `follow`
  ADD CONSTRAINT `flw_followed_id` FOREIGN KEY (`followed_id`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `flw_follower_id` FOREIGN KEY (`follower_id`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `komentar`
--
ALTER TABLE `komentar`
  ADD CONSTRAINT `kmn_id_content` FOREIGN KEY (`id_content`) REFERENCES `konten` (`id_content`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kmn_id_parent` FOREIGN KEY (`id_parent`) REFERENCES `komentar` (`id_comment`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kmn_id_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `konten`
--
ALTER TABLE `konten`
  ADD CONSTRAINT `ktn_id_uploader` FOREIGN KEY (`id_uploader`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `lik_id_content` FOREIGN KEY (`id_content`) REFERENCES `konten` (`id_content`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lik_id_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `medsos`
--
ALTER TABLE `medsos`
  ADD CONSTRAINT `mds_id_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `msg_id_chat_room` FOREIGN KEY (`id_chat_room`) REFERENCES `chat_rooms` (`id_room`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `msg_id_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`id_transaction`) REFERENCES `transactions` (`id_transaction`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `recruitment_approvals`
--
ALTER TABLE `recruitment_approvals`
  ADD CONSTRAINT `ra_id_admin` FOREIGN KEY (`id_admin`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ra_id_event` FOREIGN KEY (`id_event`) REFERENCES `events` (`id_event`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ra_id_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `trs_id_orderer` FOREIGN KEY (`id_orderer`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trs_id_worker` FOREIGN KEY (`id_worker`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
