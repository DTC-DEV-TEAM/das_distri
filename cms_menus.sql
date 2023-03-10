-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2023 at 08:41 AM
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
-- Table structure for table `cms_menus`
--

CREATE TABLE `cms_menus` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'url',
  `path` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_dashboard` tinyint(1) NOT NULL DEFAULT 0,
  `id_cms_privileges` int(11) DEFAULT NULL,
  `sorting` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_menus`
--

INSERT INTO `cms_menus` (`id`, `name`, `type`, `path`, `color`, `icon`, `parent_id`, `is_active`, `is_dashboard`, `id_cms_privileges`, `sorting`, `created_at`, `updated_at`) VALUES
(2, 'Purchase Location', 'Route', 'AdminChannelsControllerGetIndex', 'normal', 'fa fa-star-o', 5, 1, 0, 1, 3, '2019-05-15 01:13:03', '2021-01-22 07:18:09'),
(3, 'Customer Location', 'Route', 'AdminStoresControllerGetIndex', 'normal', 'fa fa-star-o', 5, 1, 0, 1, 10, '2019-05-15 01:15:45', '2021-01-22 07:52:28'),
(5, 'Submaster Module', 'URL', 'SubmasterModule', 'normal', 'fa fa-circle', 0, 1, 0, 1, 16, '2019-05-15 01:29:12', '2021-01-06 08:57:35'),
(6, 'IMFS', 'Route', 'AdminItemsControllerGetIndex', 'normal', 'fa fa-circle-o', 0, 1, 0, 1, 15, '2019-05-15 01:31:02', '2021-01-06 09:00:40'),
(27, 'Level Status', 'Route', 'AdminLevelStatusesControllerGetIndex', NULL, 'fa fa-star-o', 5, 1, 0, 1, 5, '2019-06-19 07:03:50', NULL),
(59, 'Items Included', 'Route', 'AdminItemsIncludedControllerGetIndex', NULL, 'fa fa-star-o', 5, 1, 0, 1, 4, '2019-07-29 07:29:18', NULL),
(63, 'Store Logo', 'Route', 'AdminStoreLogoControllerGetIndex', NULL, 'fa fa-star-o', 5, 1, 0, 1, 9, '2019-08-16 10:02:31', NULL),
(92, 'Mode Of Payment', 'Route', 'AdminModeOfPaymentControllerGetIndex', NULL, 'fa fa-star-o', 5, 1, 0, 1, 6, '2020-12-22 08:22:24', NULL),
(93, 'Problem Details', 'Route', 'AdminSrofProblemDetailsControllerGetIndex', NULL, 'fa fa-star-o', 5, 1, 0, 1, 7, '2020-12-22 10:20:31', NULL),
(94, 'To Verify ECOMM', 'Route', 'AdminReturnsHeaderControllerGetIndex', 'normal', 'fa fa-circle-o', 158, 1, 0, 1, 1, '2020-12-23 09:14:12', '2023-03-10 01:00:06'),
(95, 'Returns History', 'Route', 'AdminReturnsHistoryControllerGetIndex', 'normal', 'fa fa-circle-o', 158, 1, 0, 1, 13, '2021-01-04 06:46:36', '2022-07-06 05:30:26'),
(97, 'To Diagnose ECOMM', 'Route', 'AdminReturnsDiagnosingControllerGetIndex', 'normal', 'fa fa-circle-o', 158, 1, 0, 1, 6, '2021-01-04 09:29:45', '2021-09-16 06:26:12'),
(98, 'To CRF ECOMM', 'Route', 'AdminReturnsCrfControllerGetIndex', 'normal', 'fa fa-circle-o', 158, 1, 0, 1, 7, '2021-01-04 13:28:30', '2021-02-04 12:26:12'),
(99, 'To SOR ECOMM', 'Route', 'AdminReturnsSorControllerGetIndex', 'normal', 'fa fa-circle-o', 158, 1, 0, 1, 8, '2021-01-05 10:51:43', '2021-09-16 06:27:56'),
(100, 'Dashboard', 'Statistic', 'statistic_builder/show/aftersalesdashboard', 'normal', 'fa fa-tachometer', 0, 1, 1, 2, 6, '2021-01-07 06:22:28', '2021-01-07 06:34:17'),
(101, 'Dashboard', 'Statistic', 'statistic_builder/show/ecommopsdashboard', 'normal', 'fa fa-tachometer', 0, 1, 1, 1, 7, '2021-01-07 06:39:12', '2022-12-20 03:17:14'),
(103, 'Dashboard', 'Statistic', 'statistic_builder/show/rmadashboard', 'normal', 'fa fa-tachometer', 0, 1, 1, 4, 8, '2021-01-07 06:45:24', '2021-01-07 06:46:15'),
(104, 'Dashboard', 'Statistic', 'statistic_builder/show/accountingdashboard', 'normal', 'fa fa-tachometer', 0, 1, 1, 5, 3, '2021-01-07 06:48:20', '2021-01-07 06:49:12'),
(106, 'Dashboard', 'Statistic', 'statistic_builder/show/sdmdashboard', 'normal', 'fa fa-tachometer', 0, 1, 1, 6, 4, '2021-01-07 06:54:50', '2021-01-07 06:55:59'),
(107, 'To Schedule Return RTL', 'Route', 'AdminReturnsRetailSchedulingControllerGetIndex', 'normal', 'fa fa-circle-o', 159, 1, 0, 1, 3, '2021-01-08 06:36:31', '2021-01-18 08:46:29'),
(110, 'Returns History RTL', 'Route', 'AdminRetailReturnHistoryControllerGetIndex', 'normal', 'fa fa-circle-o', 159, 1, 0, 1, 12, '2021-01-11 13:05:34', '2022-01-28 03:02:45'),
(112, 'To Diagnose RTL', 'Route', 'AdminRetailReturnDiagnosingControllerGetIndex', 'normal', 'fa fa-circle-o', 159, 1, 0, 1, 4, '2021-01-11 13:14:11', '2021-09-16 06:26:38'),
(113, 'To CRF RTL', 'Route', 'AdminRetailReturnCrfControllerGetIndex', 'normal', 'fa fa-circle-o', 159, 1, 0, 1, 6, '2021-01-11 14:51:27', '2021-02-04 12:26:34'),
(114, 'To SOR RTL', 'Route', 'AdminRetailReturnSorControllerGetIndex', 'normal', 'fa fa-circle-o', 159, 1, 0, 1, 7, '2021-01-11 15:39:04', '2021-09-16 06:28:14'),
(115, 'To Close RTL', 'Route', 'AdminRetailReturnClosingControllerGetIndex', 'normal', 'fa fa-circle-o', 159, 1, 0, 1, 10, '2021-01-12 03:26:51', '2021-09-20 11:26:46'),
(116, 'Returns History RTL', 'Route', 'AdminRetailReturnHistoryControllerGetIndex', 'normal', 'fa fa-circle-o', 159, 1, 0, 1, 13, '2021-01-12 15:05:17', '2022-07-06 05:28:55'),
(118, 'Dashboard', 'Statistic', 'statistic_builder/show/logisticsdashboard', 'normal', 'fa fa-circle-o', 0, 1, 1, 7, 9, '2021-01-13 02:52:27', '2021-01-13 02:54:07'),
(121, 'Returns History ECOMM', 'Route', 'AdminReturnsHistoryControllerGetIndex', 'normal', 'fa fa-circle-o', 158, 1, 0, 1, 14, '2021-01-15 00:08:10', '2022-07-06 05:30:51'),
(122, 'To Schedule Return ECOMM', 'Module', 'scheduling', 'normal', 'fa fa-circle-o', 158, 1, 0, 1, 5, '2021-01-18 08:49:35', '2021-01-18 08:57:30'),
(123, 'To Verify RTL', 'Route', 'AdminRetailForVerificationControllerGetIndex', 'normal', 'fa fa-circle-o', 159, 1, 0, 1, 1, '2021-01-21 01:05:25', '2023-03-10 01:00:24'),
(124, 'Store Name Front End', 'Route', 'AdminStoresFrontendControllerGetIndex', 'normal', 'fa fa-star-o', 5, 1, 0, 1, 11, '2021-01-21 10:28:23', '2021-01-21 10:29:53'),
(125, 'To Create CRF RTL', 'Route', 'AdminToCreateCrfControllerGetIndex', 'normal', 'fa fa-circle-o', 159, 1, 0, 1, 11, '2021-01-27 23:28:07', '2021-09-20 11:19:41'),
(126, 'Dashboard', 'Statistic', 'statistic_builder/show/super-admin-dashboard', 'normal', 'fa fa-tachometer', 0, 1, 1, 10, 1, '2021-01-29 05:52:19', '2022-12-20 03:18:04'),
(127, 'To Receive', 'Route', 'AdminForReceivingReturnsControllerGetIndex', 'normal', 'fa fa-circle-o', 158, 1, 0, 1, 2, '2021-01-31 22:51:19', '2021-02-24 09:18:49'),
(128, 'Claimed Status', 'Route', 'AdminStatusClaimedControllerGetIndex', 'normal', 'fa fa-star-o', 5, 1, 0, 1, 1, '2021-02-02 00:33:47', '2021-02-02 00:37:57'),
(129, 'Ship Back Status', 'Route', 'AdminStatusShipBackControllerGetIndex', 'normal', 'fa fa-star-o', 5, 1, 0, 1, 8, '2021-02-02 00:34:28', '2021-02-02 00:38:13'),
(130, 'To Close', 'Route', 'AdminReturnsToCloseControllerGetIndex', 'normal', 'fa fa-circle-o', 158, 1, 0, 1, 12, '2021-02-04 12:00:22', '2021-03-05 04:30:56'),
(131, 'To Close', 'Module', 'retail_return_crf', 'normal', 'fa fa-circle-o', 159, 1, 0, 1, 8, '2021-02-04 13:16:32', '2022-01-28 03:02:19'),
(132, 'Dashboard', 'Statistic', 'statistic_builder/show/inventory-control', 'normal', 'fa fa-tachometer', 0, 1, 1, 11, 5, '2021-02-04 13:52:41', NULL),
(133, 'Diagnose Warranty Status', 'Route', 'AdminDiagnoseWarrantyControllerGetIndex', 'normal', 'fa fa-star-o', 5, 1, 0, 1, 2, '2021-02-05 07:08:24', '2021-02-05 07:08:59'),
(134, 'Dashboard', 'Statistic', 'statistic_builder/show/service-center', 'normal', 'fa fa-tachometer', 0, 1, 1, 12, 10, '2021-02-09 23:42:05', NULL),
(135, 'To Receive ECOMM', 'Route', 'AdminToReceiveEcommControllerGetIndex', 'normal', 'fa fa-circle-o', 158, 1, 0, 1, 3, '2021-02-15 23:08:34', '2022-06-15 15:15:09'),
(136, 'To Receive RTL', 'Route', 'AdminToReceiveRetailControllerGetIndex', 'normal', 'fa fa-circle-o', 159, 1, 0, 1, 2, '2021-02-15 23:09:22', '2022-06-15 15:15:22'),
(137, 'To Ship Back ECOMM', 'Route', 'AdminToShipBackEcommControllerGetIndex', 'normal', 'fa fa-circle-o', 158, 1, 0, 1, 11, '2021-02-23 04:23:37', '2021-02-23 04:26:28'),
(138, 'To Ship Back RTL', 'Route', 'AdminToShipBackRtlControllerGetIndex', 'normal', 'fa fa-circle-o', 159, 1, 0, 1, 5, '2021-02-23 04:25:06', '2021-02-23 04:26:44'),
(139, 'To Close RTL', 'Route', 'AdminRetailReturnClosingControllerGetIndex', 'normal', 'fa fa-circle-o', 159, 1, 0, 1, 9, '2021-02-23 07:47:50', '2023-03-10 01:04:35'),
(140, 'To Close ECOMM', 'Route', 'AdminReturnsToCloseControllerGetIndex', 'normal', 'fa fa-circle-o', 158, 1, 0, 1, 9, '2021-02-23 07:50:35', '2021-02-24 09:19:34'),
(141, 'Dashboard', 'Statistic', 'statistic_builder/show/store-dashboard', 'normal', 'fa fa-circle-o', 0, 1, 1, 13, 11, '2021-02-25 23:03:42', NULL),
(143, 'Returns History DISTRI', 'Route', 'AdminDistriReturnHistoryControllerGetIndex', 'normal', 'fa fa-circle-o', 157, 1, 0, 1, 9, '2021-09-15 18:47:43', '2023-03-10 01:35:09'),
(144, 'To Verify DISTRI', 'Route', 'AdminDistriToVerifyControllerGetIndex', 'normal', 'fa fa-circle-o', 157, 1, 0, 1, 1, '2021-09-16 05:29:26', '2023-03-10 02:21:04'),
(145, 'To Schedule Return DISTRI', 'Route', 'AdminReturnsDistriSchedulingControllerGetIndex', 'normal', 'fa fa-circle-o', 157, 1, 0, 1, 3, '2021-09-16 09:01:53', '2023-03-10 02:17:39'),
(146, 'To Diagnose DISTRI', 'Route', 'AdminDistriReturnDiagnosingControllerGetIndex', 'normal', 'fa fa-circle-o', 157, 1, 0, 1, 4, '2021-09-16 10:39:04', '2023-03-10 01:29:50'),
(147, 'To Ship Back DISTRI', 'Route', 'AdminToShipBackDistriControllerGetIndex', 'normal', 'fa fa-circle-o', 157, 1, 0, 1, 5, '2021-09-20 07:29:24', '2023-03-10 01:30:29'),
(148, 'To Close DISTRI', 'Route', 'AdminToCloseDistriControllerGetIndex', 'normal', 'fa fa-circle-o', 157, 1, 0, 1, 8, '2021-09-20 08:56:40', '2023-03-10 02:58:13'),
(150, 'To Create CRF DISTRI', 'Route', 'AdminCrfToCreateDistriControllerGetIndex', 'normal', 'fa fa-circle-o', 157, 1, 0, 1, 6, '2021-09-20 12:00:08', '2023-03-10 01:31:23'),
(151, 'To SOR DISTRI', 'Route', 'AdminToSorDistriControllerGetIndex', 'normal', 'fa fa-circle-o', 157, 1, 0, 1, 7, '2021-09-22 16:56:16', '2023-03-10 01:31:47'),
(152, 'To Receive DISTRI', 'Route', 'AdminToReceiveDistriControllerGetIndex', 'normal', 'fa fa-circle-o', 157, 1, 0, 1, 2, '2021-09-28 04:08:23', '2023-03-10 02:16:44'),
(153, 'Dashboard', 'Statistic', 'statistic_builder/show/audit-dashboard', 'normal', 'fa fa-tachometer', 0, 1, 1, 16, 2, '2022-06-09 05:19:48', NULL),
(155, 'To Receive ECOMM', 'Route', 'AdminEcommToReceiveControllerGetIndex', 'normal', 'fa fa-circle-o', 158, 1, 0, 1, 4, '2022-06-15 15:08:04', '2022-06-15 15:13:07'),
(156, 'To Create CRF', 'Route', 'AdminToCreateCrfEcommControllerGetIndex', 'normal', 'fa fa-circle-o', 158, 1, 0, 1, 10, '2022-06-27 00:01:02', '2022-06-27 00:04:16'),
(157, 'DISTRI', 'URL', '#', 'normal', 'fa fa-th-list', 0, 1, 0, 1, 14, '2023-03-10 00:41:04', '2023-03-10 01:26:58'),
(158, 'ECOMM', 'URL', '#', 'normal', 'fa fa-th-list', 0, 1, 0, 1, 12, '2023-03-10 00:52:39', '2023-03-10 01:21:23'),
(159, 'RTL', 'URL', '#', 'normal', 'fa fa-th-list', 0, 1, 0, 1, 13, '2023-03-10 00:59:26', '2023-03-10 01:19:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cms_menus`
--
ALTER TABLE `cms_menus`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cms_menus`
--
ALTER TABLE `cms_menus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
