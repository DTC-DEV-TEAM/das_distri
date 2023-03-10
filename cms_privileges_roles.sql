-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2023 at 08:38 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dtc_digits_das`
--

-- --------------------------------------------------------

--
-- Table structure for table `cms_privileges_roles`
--

CREATE TABLE `cms_privileges_roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `is_visible` tinyint(1) DEFAULT NULL,
  `is_create` tinyint(1) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT NULL,
  `is_edit` tinyint(1) DEFAULT NULL,
  `is_delete` tinyint(1) DEFAULT NULL,
  `id_cms_privileges` int(11) DEFAULT NULL,
  `id_cms_moduls` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_privileges_roles`
--

INSERT INTO `cms_privileges_roles` (`id`, `is_visible`, `is_create`, `is_read`, `is_edit`, `is_delete`, `id_cms_privileges`, `id_cms_moduls`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 1, 1, 4, NULL, NULL),
(2, 1, 1, 1, 1, 1, 1, 12, NULL, NULL),
(3, 1, 1, 1, 1, 1, 1, 13, NULL, NULL),
(4, 1, 1, 1, 1, 1, 1, 14, NULL, NULL),
(5, 1, 1, 1, 1, 1, 1, 15, NULL, NULL),
(6, 1, 1, 1, 1, 1, 1, 16, NULL, NULL),
(7, 1, 1, 1, 1, 1, 1, 17, NULL, NULL),
(8, 1, 1, 1, 1, 1, 1, 18, NULL, NULL),
(11, 1, 1, 1, 1, 1, 1, 19, NULL, NULL),
(23, 1, 1, 1, 1, 1, 1, 20, NULL, NULL),
(24, 1, 1, 1, 1, 1, 1, 21, NULL, NULL),
(51, 1, 1, 1, 1, 1, 1, 22, NULL, NULL),
(52, 1, 1, 1, 1, 1, 1, 23, NULL, NULL),
(53, 1, 1, 1, 1, 1, 1, 24, NULL, NULL),
(130, 1, 1, 1, 1, 1, 1, 25, NULL, NULL),
(131, 1, 1, 1, 1, 1, 1, 26, NULL, NULL),
(132, 1, 1, 1, 1, 1, 1, 27, NULL, NULL),
(133, 1, 1, 1, 1, 1, 1, 28, NULL, NULL),
(134, 1, 1, 1, 1, 1, 1, 29, NULL, NULL),
(135, 1, 1, 1, 1, 1, 1, 30, NULL, NULL),
(184, 1, 1, 1, 1, 1, 1, 31, NULL, NULL),
(197, 1, 1, 1, 1, 1, 1, 32, NULL, NULL),
(198, 1, 1, 1, 1, 1, 1, 33, NULL, NULL),
(278, 1, 1, 1, 1, 1, 1, 39, NULL, NULL),
(315, 1, 1, 1, 1, 1, 1, 40, NULL, NULL),
(320, 1, 1, 1, 1, 1, 1, 41, NULL, NULL),
(321, 1, 1, 1, 1, 1, 1, 42, NULL, NULL),
(322, 1, 1, 1, 1, 1, 1, 43, NULL, NULL),
(323, 1, 1, 1, 1, 1, 1, 44, NULL, NULL),
(324, 1, 1, 1, 1, 1, 1, 45, NULL, NULL),
(330, 1, 1, 1, 1, 1, 1, 46, NULL, NULL),
(331, 1, 1, 1, 1, 1, 1, 47, NULL, NULL),
(352, 1, 1, 1, 1, 1, 1, 48, NULL, NULL),
(417, 1, 1, 1, 1, 1, 1, 49, NULL, NULL),
(418, 1, 1, 1, 1, 1, 1, 50, NULL, NULL),
(433, 1, 1, 1, 1, 1, 1, 51, NULL, NULL),
(434, 1, 1, 1, 1, 1, 1, 52, NULL, NULL),
(435, 1, 1, 1, 1, 1, 1, 53, NULL, NULL),
(436, 1, 1, 1, 1, 1, 1, 54, NULL, NULL),
(437, 1, 1, 1, 1, 1, 1, 55, NULL, NULL),
(438, 1, 1, 1, 1, 1, 1, 56, NULL, NULL),
(501, 1, 1, 1, 1, 1, 1, 57, NULL, NULL),
(502, 1, 1, 1, 1, 1, 1, 58, NULL, NULL),
(515, 1, 1, 1, 1, 1, 1, 59, NULL, NULL),
(516, 1, 1, 1, 1, 1, 1, 60, NULL, NULL),
(571, 1, 1, 1, 1, 1, 1, 61, NULL, NULL),
(572, 1, 1, 1, 1, 1, 1, 62, NULL, NULL),
(573, 1, 1, 1, 1, 1, 1, 63, NULL, NULL),
(574, 1, 1, 1, 1, 1, 1, 64, NULL, NULL),
(575, 1, 1, 1, 1, 1, 1, 65, NULL, NULL),
(576, 1, 1, 1, 1, 1, 1, 66, NULL, NULL),
(577, 1, 1, 1, 1, 1, 1, 67, NULL, NULL),
(578, 1, 1, 1, 1, 1, 1, 68, NULL, NULL),
(592, 1, 1, 1, 1, 1, 1, 69, NULL, NULL),
(593, 1, 1, 1, 1, 1, 1, 70, NULL, NULL),
(594, 1, 1, 1, 1, 1, 1, 71, NULL, NULL),
(595, 1, 1, 1, 1, 1, 1, 72, NULL, NULL),
(596, 1, 1, 1, 1, 1, 1, 73, NULL, NULL),
(597, 1, 1, 1, 1, 1, 1, 74, NULL, NULL),
(598, 1, 1, 1, 1, 1, 1, 75, NULL, NULL),
(599, 1, 1, 1, 1, 1, 1, 76, NULL, NULL),
(616, 1, 1, 1, 1, 1, 1, 77, NULL, NULL),
(622, 1, 1, 1, 1, 1, 1, 78, NULL, NULL),
(627, 1, 1, 1, 1, 1, 1, 79, NULL, NULL),
(628, 1, 1, 1, 1, 1, 1, 80, NULL, NULL),
(631, 1, 1, 1, 1, 1, 1, 81, NULL, NULL),
(635, 1, 0, 1, 1, 0, 6, 76, NULL, NULL),
(636, 1, 0, 1, 0, 0, 6, 71, NULL, NULL),
(637, 1, 1, 1, 1, 1, 1, 82, NULL, NULL),
(642, 1, 1, 1, 1, 1, 1, 83, NULL, NULL),
(643, 1, 1, 1, 1, 1, 1, 84, NULL, NULL),
(644, 1, 1, 1, 1, 1, 1, 85, NULL, NULL),
(648, 1, 1, 1, 1, 1, 1, 86, NULL, NULL),
(668, 1, 0, 1, 1, 0, 5, 67, NULL, NULL),
(669, 1, 0, 1, 1, 0, 5, 74, NULL, NULL),
(670, 1, 0, 1, 1, 0, 5, 64, NULL, NULL),
(671, 1, 0, 1, 1, 0, 5, 71, NULL, NULL),
(685, 1, 1, 1, 1, 1, 1, 87, NULL, NULL),
(692, 1, 1, 1, 1, 1, 1, 88, NULL, NULL),
(693, 1, 1, 1, 1, 1, 1, 89, NULL, NULL),
(707, 1, 1, 1, 1, 1, 1, 90, NULL, NULL),
(708, 1, 1, 1, 1, 1, 1, 91, NULL, NULL),
(747, 1, 0, 1, 1, 0, 3, 64, NULL, NULL),
(748, 1, 0, 1, 1, 0, 3, 85, NULL, NULL),
(749, 1, 0, 1, 1, 0, 3, 63, NULL, NULL),
(756, 1, 1, 1, 1, 1, 1, 92, NULL, NULL),
(757, 1, 1, 1, 1, 1, 1, 93, NULL, NULL),
(758, 1, 1, 1, 1, 1, 1, 94, NULL, NULL),
(761, 1, 1, 1, 1, 1, 1, 95, NULL, NULL),
(770, 1, 1, 1, 1, 1, 1, 96, NULL, NULL),
(779, 1, 1, 1, 1, 1, 1, 97, NULL, NULL),
(780, 1, 0, 1, 1, 0, 7, 64, NULL, NULL),
(781, 1, 0, 1, 1, 0, 7, 71, NULL, NULL),
(782, 1, 0, 1, 1, 0, 7, 93, NULL, NULL),
(783, 1, 0, 1, 1, 0, 7, 63, NULL, NULL),
(784, 1, 0, 1, 1, 0, 7, 95, NULL, NULL),
(785, 1, 0, 1, 1, 0, 7, 69, NULL, NULL),
(786, 1, 0, 1, 1, 0, 7, 97, NULL, NULL),
(787, 1, 0, 1, 1, 0, 7, 90, NULL, NULL),
(788, 1, 0, 1, 1, 0, 7, 91, NULL, NULL),
(789, 1, 1, 1, 1, 1, 1, 98, NULL, NULL),
(793, 1, 1, 1, 1, 1, 1, 99, NULL, NULL),
(800, 1, 1, 1, 1, 1, 1, 100, NULL, NULL),
(801, 1, 0, 1, 1, 0, 8, 76, NULL, NULL),
(802, 1, 0, 1, 1, 0, 8, 71, NULL, NULL),
(803, 1, 0, 1, 1, 0, 8, 93, NULL, NULL),
(804, 1, 0, 1, 1, 0, 8, 98, NULL, NULL),
(805, 1, 0, 1, 1, 0, 8, 81, NULL, NULL),
(806, 1, 0, 1, 1, 0, 8, 100, NULL, NULL),
(807, 1, 1, 1, 1, 1, 1, 101, NULL, NULL),
(833, 1, 1, 1, 1, 1, 1, 102, NULL, NULL),
(849, 1, 0, 1, 1, 0, 11, 74, NULL, NULL),
(850, 1, 0, 1, 1, 0, 11, 64, NULL, NULL),
(851, 1, 0, 1, 1, 0, 11, 71, NULL, NULL),
(854, 1, 1, 1, 1, 1, 1, 103, NULL, NULL),
(865, 1, 1, 1, 1, 1, 1, 104, NULL, NULL),
(904, 1, 1, 1, 1, 1, 1, 105, NULL, NULL),
(909, 1, 0, 1, 1, 0, 17, 64, NULL, NULL),
(910, 1, 0, 1, 1, 0, 17, 71, NULL, NULL),
(911, 1, 0, 1, 1, 0, 17, 93, NULL, NULL),
(912, 1, 0, 1, 1, 0, 2, 64, NULL, NULL),
(913, 1, 0, 1, 1, 0, 2, 71, NULL, NULL),
(914, 1, 0, 1, 1, 0, 2, 93, NULL, NULL),
(915, 1, 0, 1, 1, 0, 2, 85, NULL, NULL),
(916, 1, 0, 1, 1, 0, 2, 105, NULL, NULL),
(917, 1, 0, 1, 1, 0, 2, 63, NULL, NULL),
(918, 1, 0, 1, 0, 0, 16, 64, NULL, NULL),
(919, 1, 0, 1, 0, 0, 16, 71, NULL, NULL),
(981, 1, 0, 1, 1, 0, 15, 93, NULL, NULL),
(982, 1, 0, 1, 1, 0, 15, 95, NULL, NULL),
(983, 1, 0, 1, 1, 0, 15, 94, NULL, NULL),
(984, 1, 0, 1, 1, 0, 14, 93, NULL, NULL),
(985, 1, 0, 1, 1, 0, 14, 95, NULL, NULL),
(986, 1, 0, 1, 1, 0, 14, 94, NULL, NULL),
(987, 1, 0, 1, 1, 0, 4, 66, NULL, NULL),
(988, 1, 0, 1, 1, 0, 4, 73, NULL, NULL),
(989, 1, 0, 1, 1, 0, 4, 68, NULL, NULL),
(990, 1, 0, 1, 1, 0, 4, 75, NULL, NULL),
(991, 1, 0, 1, 1, 0, 4, 64, NULL, NULL),
(992, 1, 0, 1, 1, 0, 4, 71, NULL, NULL),
(993, 1, 0, 1, 1, 0, 4, 93, NULL, NULL),
(994, 1, 0, 1, 1, 0, 4, 96, NULL, NULL),
(995, 1, 0, 1, 1, 0, 4, 102, NULL, NULL),
(996, 1, 0, 1, 1, 0, 4, 88, NULL, NULL),
(997, 1, 0, 1, 1, 0, 4, 104, NULL, NULL),
(998, 1, 0, 1, 1, 0, 4, 89, NULL, NULL),
(999, 1, 0, 1, 1, 0, 4, 101, NULL, NULL),
(1034, 1, 0, 1, 1, 0, 13, 82, NULL, NULL),
(1035, 1, 0, 1, 1, 0, 13, 78, NULL, NULL),
(1036, 1, 0, 1, 1, 0, 13, 76, NULL, NULL),
(1037, 1, 0, 1, 1, 0, 13, 64, NULL, NULL),
(1038, 1, 0, 1, 1, 0, 13, 71, NULL, NULL),
(1039, 1, 0, 1, 1, 0, 13, 85, NULL, NULL),
(1040, 1, 0, 1, 1, 0, 13, 98, NULL, NULL),
(1041, 1, 0, 1, 1, 0, 13, 102, NULL, NULL),
(1042, 1, 0, 1, 1, 0, 13, 95, NULL, NULL),
(1043, 1, 0, 1, 1, 0, 13, 4, NULL, NULL),
(1044, 1, 0, 1, 1, 0, 12, 76, NULL, NULL),
(1045, 1, 0, 1, 1, 0, 12, 66, NULL, NULL),
(1046, 1, 0, 1, 1, 0, 12, 73, NULL, NULL),
(1047, 1, 0, 1, 1, 0, 12, 68, NULL, NULL),
(1048, 1, 0, 1, 1, 0, 12, 75, NULL, NULL),
(1049, 1, 0, 1, 1, 0, 12, 64, NULL, NULL),
(1050, 1, 0, 1, 1, 0, 12, 71, NULL, NULL),
(1051, 1, 0, 1, 1, 0, 12, 93, NULL, NULL),
(1052, 1, 0, 1, 1, 0, 12, 85, NULL, NULL),
(1053, 1, 0, 1, 1, 0, 12, 98, NULL, NULL),
(1054, 1, 0, 1, 1, 0, 12, 96, NULL, NULL),
(1055, 1, 0, 1, 1, 0, 12, 102, NULL, NULL),
(1056, 1, 0, 1, 1, 0, 12, 88, NULL, NULL),
(1057, 1, 0, 1, 1, 0, 12, 104, NULL, NULL),
(1058, 1, 0, 1, 1, 0, 12, 89, NULL, NULL),
(1059, 1, 0, 1, 1, 0, 12, 101, NULL, NULL),
(1060, 1, 0, 1, 1, 0, 10, 82, NULL, NULL),
(1061, 1, 0, 1, 1, 0, 10, 78, NULL, NULL),
(1062, 1, 0, 1, 1, 0, 10, 76, NULL, NULL),
(1063, 1, 0, 1, 1, 0, 10, 64, NULL, NULL),
(1064, 1, 0, 1, 1, 0, 10, 71, NULL, NULL),
(1065, 1, 0, 1, 1, 0, 10, 93, NULL, NULL),
(1066, 1, 0, 1, 1, 0, 10, 85, NULL, NULL),
(1067, 1, 0, 1, 1, 0, 10, 98, NULL, NULL),
(1068, 1, 0, 1, 1, 0, 10, 102, NULL, NULL),
(1069, 1, 0, 1, 1, 0, 10, 95, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cms_privileges_roles`
--
ALTER TABLE `cms_privileges_roles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cms_privileges_roles`
--
ALTER TABLE `cms_privileges_roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1070;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
