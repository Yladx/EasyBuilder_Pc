-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2024 at 09:52 PM
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
-- Database: `easybuilderpc`
--

--
-- Dumping data for table `advertisements`
--

INSERT INTO `advertisements` (`id`, `label`, `caption`, `access_link`, `brand`, `type`, `advertise`, `src`, `created_at`, `updated_at`, `sort`) VALUES
(1, 'AMD Ryzen™ 7000 Series Processors', 'AMD Ryzen™ 7000 Series processors provide creators with the amazing performance they need to get more done.', 'https://www.amd.com/en/partner/articles/amd-ryzen-7000-series-desktop-processors.html', 'AMD', 'video', 1, 'ads/ad_674aafa5c9b37.mp4', '2024-11-29 22:24:38', '2024-11-29 22:24:38', 0),
(2, 'Introducing 13th Gen Intel Core Processors for Desktop | Intel', 'Ascend leaderboards, create worlds, and crunch numbers—all at once. Get next-generation performance for everything you do with the 13th Gen Intel® Core™ processors.', 'https://intel.ly/3IX1bN2', 'Intel', 'video', 0, 'ads/ad_674ab291c4ace.mp4', '2024-11-29 22:37:05', '2024-11-30 02:27:00', 0);

--
-- Dumping data for table `computer_cases`
--

INSERT INTO `computer_cases` (`id`, `name`, `form_factor`, `color`, `psu_wattage`, `side_panel_material`, `external_volume`, `internal_35_bays`, `gpu_length_limit`, `psu_form_factor`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Fractal Design Meshify 2 Compact', 'ATX', 'Black', NULL, 'Tempered Glass', 41.80, 2, 360, 'ATX', 'component_images/computer_cases/computer_cases-1.png', NULL, '2024-11-29 22:45:04'),
(2, 'NZXT H510', 'ATX', 'Matte White', NULL, 'Tempered Glass', 33.40, 2, 305, 'ATX', 'component_images/computer_cases/computer_cases-2.png', NULL, '2024-11-29 22:46:42'),
(3, 'Corsair 4000D Airflow', 'ATX', 'Black', NULL, 'Tempered Glass', 42.50, 2, 360, 'ATX', 'component_images/computer_cases/computer_cases-3.png', NULL, '2024-11-29 22:48:39'),
(4, 'Lian Li Lancool II Mesh', 'ATX', 'Black', NULL, 'Tempered Glass', 45.20, 3, 420, 'ATX', 'component_images/computer_cases/computer_cases-4.png', NULL, '2024-11-29 22:52:09'),
(5, 'Phanteks Eclipse P400A', 'ATX', 'Black', NULL, 'Tempered Glass', 39.50, 2, 320, 'ATX', 'component_images/computer_cases/computer_cases-5.png', NULL, '2024-11-29 22:54:11'),
(6, 'Cooler Master NR600', 'ATX', 'Black', NULL, 'Mesh', 37.20, 2, 330, 'ATX', 'component_images/computer_cases/computer_cases-6.png', NULL, '2024-11-29 23:06:12'),
(7, 'be quiet! Pure Base 500DX', 'ATX', 'Black', NULL, 'Tempered Glass', 44.50, 2, 380, 'ATX', 'component_images/computer_cases/computer_cases-7.png', NULL, '2024-11-29 22:57:34'),
(8, 'Thermaltake View 51 RGB', 'Full Tower', 'Black', NULL, 'Tempered Glass', 62.50, 4, 480, 'ATX', 'component_images/computer_cases/computer_cases-8.png', NULL, '2024-11-29 22:59:32'),
(9, 'Fractal Design Define 7 XL', 'Full Tower', 'Black', NULL, 'Tempered Glass', 70.20, 6, 420, 'ATX', 'component_images/computer_cases/computer_cases-9.png', NULL, '2024-11-29 23:00:48'),
(10, 'NZXT H710i', 'ATX', 'Matte Black', NULL, 'Tempered Glass', 45.80, 3, 360, 'ATX', 'component_images/computer_cases/computer_cases-10.png', NULL, '2024-11-29 23:02:39');

--
-- Dumping data for table `cpus`
--

INSERT INTO `cpus` (`id`, `name`, `socket`, `core_count`, `core_clock`, `boost_clock`, `tdp`, `graphics`, `smt`, `image`, `created_at`, `updated_at`) VALUES
(1, 'AMD Ryzen 7 5800X', 'AM4', 8, 3.80, 4.70, 105, NULL, 1, 'component_images/cpus/cpus-1.png', NULL, '2024-11-30 12:15:53'),
(2, 'Intel Core i7-12700K', 'LGA1700', 12, 3.60, 5.00, 125, 'Intel UHD Graphics 770', 1, 'component_images/cpus/cpus-2.png', NULL, '2024-11-30 12:16:06'),
(3, 'AMD Ryzen 5 5600X', 'AM4', 6, 3.70, 4.60, 65, NULL, 1, 'component_images/cpus/cpus-3.jpg', NULL, '2024-11-30 12:16:24'),
(4, 'Intel Core i5-12600K', 'LGA1700', 10, 3.70, 4.90, 125, 'Intel UHD Graphics 770', 1, 'component_images/cpus/cpus-4.png', NULL, '2024-11-30 12:16:36'),
(5, 'AMD Ryzen 9 5950X', 'AM4', 16, 3.40, 4.90, 105, NULL, 1, 'component_images/cpus/cpus-5.png', NULL, '2024-11-30 12:16:48'),
(6, 'Intel Core i9-12900K', 'LGA1700', 16, 3.20, 5.20, 125, 'Intel UHD Graphics 770', 1, 'component_images/cpus/cpus-6.png', NULL, '2024-11-30 12:19:16'),
(7, 'AMD Ryzen 5 3600X', 'AM4', 6, 3.80, 4.40, 95, NULL, 1, 'component_images/cpus/cpus-7.png', NULL, '2024-11-30 12:17:05'),
(8, 'Intel Core i3-12100F', 'LGA1700', 4, 3.30, 4.30, 58, NULL, 1, 'component_images/cpus/cpus-8.jpg', NULL, '2024-11-30 12:17:24'),
(9, 'AMD Ryzen 7 3700X', 'AM4', 8, 3.60, 4.40, 65, NULL, 1, 'component_images/cpus/cpus-9.png', NULL, '2024-11-30 12:18:55'),
(10, 'Intel Core i5-11600K', 'LGA1200', 6, 3.90, 4.90, 125, 'Intel UHD Graphics 750', 1, 'component_images/cpus/cpus-10.png', NULL, '2024-11-30 12:19:05');

-- --------------------------------------------------------

--
-- Table structure for table `gpus`
--

CREATE TABLE `gpus` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT 'Unique identifier for the GPU',
  `name` varchar(255) NOT NULL COMMENT 'Name of the graphics card',
  `chipset` varchar(50) NOT NULL COMMENT 'GPU chipset manufacturer and model',
  `memory` int(11) NOT NULL COMMENT 'Graphics memory size (GB)',
  `core_clock` decimal(5,2) NOT NULL COMMENT 'Base GPU core clock speed (MHz)',
  `boost_clock` decimal(5,2) DEFAULT NULL COMMENT 'Maximum GPU boost clock speed (MHz)',
  `pcie_slots_required` int(11) NOT NULL COMMENT 'Number of PCIe slots the GPU occupies',
  `color` varchar(50) DEFAULT NULL COMMENT 'Color of the graphics card',
  `length` int(11) DEFAULT NULL COMMENT 'Physical length of the GPU (mm)',
  `tdp` int(11) NOT NULL COMMENT 'Thermal Design Power of the GPU (Watts)',
  `image` varchar(255) DEFAULT NULL COMMENT 'Path or URL to GPU image',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gpus`
--

INSERT INTO `gpus` (`id`, `name`, `chipset`, `memory`, `core_clock`, `boost_clock`, `pcie_slots_required`, `color`, `length`, `tdp`, `image`, `created_at`, `updated_at`) VALUES
(1, 'NVIDIA GeForce RTX 3070 Ti', 'NVIDIA GA104', 8, 1.58, 1.77, 2, 'Silver', 285, 290, 'component_images/gpus/gpus-1.png', NULL, '2024-11-30 12:13:17'),
(2, 'AMD Radeon RX 6800 XT', 'AMD Navi 21', 16, 1.82, 2.25, 2, 'Black', 267, 300, 'component_images/gpus/gpus-2.png', NULL, '2024-11-30 12:15:03'),
(3, 'NVIDIA GeForce RTX 3060 Ti', 'NVIDIA GA104', 8, 1.67, 1.77, 2, 'Silver', 242, 200, 'component_images/gpus/gpus-3.png', NULL, '2024-11-30 12:13:50'),
(4, 'AMD Radeon RX 6700 XT', 'AMD Navi 22', 12, 2.32, 2.58, 2, 'Black', 267, 230, 'component_images/gpus/gpus-4.png', NULL, '2024-11-30 12:13:58'),
(5, 'NVIDIA GeForce RTX 3080', 'NVIDIA GA102', 10, 1.44, 1.71, 2, 'Silver', 285, 320, 'component_images/gpus/gpus-5.png', NULL, '2024-11-30 12:15:15'),
(6, 'AMD Radeon RX 6600 XT', 'AMD Navi 23', 8, 1.96, 2.36, 2, 'Black', 235, 160, 'component_images/gpus/gpus-6.png', NULL, '2024-11-30 12:14:08'),
(7, 'NVIDIA GeForce RTX 3050', 'NVIDIA GA106', 8, 1.55, 1.78, 2, 'Silver', 235, 130, 'component_images/gpus/gpus-7.png', NULL, '2024-11-30 12:14:18'),
(8, 'AMD Radeon RX 6500 XT', 'AMD Navi 24', 4, 2.22, 2.82, 1, 'Black', 200, 107, 'component_images/gpus/gpus-8.png', NULL, '2024-11-30 12:14:31'),
(9, 'NVIDIA GeForce RTX 3090', 'NVIDIA GA102', 24, 1.40, 1.70, 3, 'Silver', 313, 350, 'component_images/gpus/gpus-9.png', NULL, '2024-11-30 12:14:45'),
(10, 'AMD Radeon RX 6950 XT', 'AMD Navi 21', 16, 1.72, 2.31, 2, 'Black', 267, 335, 'component_images/gpus/gpus-10.png', NULL, '2024-11-30 12:14:54');

--
-- Dumping data for table `learning_modules`
--

INSERT INTO `learning_modules` (`id`, `tag`, `title`, `description`, `Information`, `video_src`, `created_at`, `updated_at`) VALUES
(1, 'PC BUILDING', 'How to Install a Motherboard', 'Step-by-step guide to properly installing a motherboard in your PC case.', '<h3>Installing the Motherboard</h3>\r\n<p>Installing the motherboard is a critical step that requires precision and care. Begin by gently lowering the motherboard into the case, ensuring the IO Shield is properly aligned if it was installed separately. Confirm that the screw holes on the motherboard align with the standoffs in the case. Once everything is properly positioned, use the appropriate screws to secure the motherboard firmly in place, but avoid overtightening to prevent damage. Completing this step provides a stable foundation for the rest of the components.</p>\r\n<hr>\r\n<h3>Steps and Tips for Installing a Motherboard</h3>\r\n<h4>Preparing the Case</h4>\r\n<ol>\r\n<li>\r\n<p><strong>Check Motherboard Standoffs</strong>:</p>\r\n<ul>\r\n<li>Ensure all the standoffs are installed in the case.</li>\r\n<li>Align every hole in the motherboard with the pre-installed standoffs to prevent damage.</li>\r\n</ul>\r\n</li>\r\n<li>\r\n<p><strong>Install the IO Shield</strong>:</p>\r\n<ul>\r\n<li>Most motherboards come with an IO Shield that is not pre-installed.</li>\r\n<li>Place and secure the IO Shield in the case before proceeding with other components.</li>\r\n<li>Skip this step if your motherboard has a pre-installed IO Shield.</li>\r\n</ul>\r\n</li>\r\n</ol>\r\n<h4>Installing the Motherboard</h4>\r\n<ol start=\"3\">\r\n<li>\r\n<p><strong>Position the Motherboard</strong>:</p>\r\n<ul>\r\n<li>Gently lower the motherboard into the case.</li>\r\n<li>Align the IO Shield (if applicable) and the screw holes on the motherboard with the standoffs in the case.</li>\r\n</ul>\r\n</li>\r\n<li>\r\n<p><strong>Secure the Motherboard</strong>:</p>\r\n<ul>\r\n<li>Use screws to firmly attach the motherboard to the standoffs.</li>\r\n</ul>\r\n</li>\r\n</ol>\r\n<p>&nbsp;</p>', 'videos/ykOI8MWU5nW5c6iwuMFQZUdStiGNbCPnIJt1DheV.mp4', '2024-11-29 23:25:59', '2024-11-29 23:52:49'),
(2, 'PC BUILDING', 'How to Install a CPU', 'Comprehensive tutorial on safely installing a processor in your motherboard.', '<h3>Installing a CPU</h3>\r\n<p>Installing a CPU is a straightforward process if you follow these essential steps carefully. Begin by locating the LGA 1200 socket on your motherboard. Open the protective plate using the lever, but keep the bracket in place. Align the CPU chip with the notches on the socket, ensuring the triangle on the CPU matches the triangle on the motherboard\'s socket corner. Gently place the CPU into the socket without applying pressure. Once positioned, secure it by locking the lever in place. Although you might hear slight noises as the pins adjust, this is completely normal. Completing this step sets the stage for the rest of your build.</p>\r\n<h3>&nbsp;</h3>\r\n<h3>Steps and Tips for Installing a CPU</h3>\r\n<h4>&nbsp;</h4>\r\n<h4>Preparing the Motherboard</h4>\r\n<ol>\r\n<li><strong>Retain the Protective Plate</strong>:\r\n<ul>\r\n<li>The plate protects the delicate pins in the socket.</li>\r\n<li>Do not discard it; keep it in case you need to RMA your motherboard.</li>\r\n</ul>\r\n</li>\r\n</ol>\r\n<h4>Installing the CPU</h4>\r\n<ol start=\"2\">\r\n<li>\r\n<p><strong>Open the Socket</strong>:</p>\r\n<ul>\r\n<li>Use the lever to lift the protective plate.</li>\r\n<li>Leave the bracket on to guide CPU installation.</li>\r\n</ul>\r\n</li>\r\n<li>\r\n<p><strong>Align the CPU</strong>:</p>\r\n<ul>\r\n<li>Identify the notches on the sides of the CPU and the triangle on the bottom-left corner.</li>\r\n<li>Match these with the corresponding markers on the socket.</li>\r\n<li>Gently place the CPU into the socket without applying pressure.</li>\r\n</ul>\r\n</li>\r\n<li>\r\n<p><strong>Lock the CPU in Place</strong>:</p>\r\n<ul>\r\n<li>Secure the CPU by lowering and locking the lever.</li>\r\n<li>You may hear pin adjustments as the lever locks; this is normal.</li>\r\n</ul>\r\n</li>\r\n</ol>\r\n<h4>Finalizing the Installation</h4>\r\n<ol start=\"5\">\r\n<li>\r\n<p><strong>Remove the Bracket</strong>:</p>\r\n<ul>\r\n<li>Once the CPU is locked, the bracket will pop out automatically.</li>\r\n<li>This indicates the CPU is securely installed.</li>\r\n</ul>\r\n</li>\r\n<li>\r\n<p><strong>Continue with the Build</strong>:</p>\r\n<ul>\r\n<li>After installing the CPU, proceed to install other components onto the motherboard.</li>\r\n</ul>\r\n</li>\r\n</ol>', 'videos/ZH4BxSUVUih9O1jvmQg7w5r3tsURPWoMhfVy3t5Z.mp4', '2024-11-29 23:25:59', '2024-11-29 23:56:13'),
(3, 'PC BUILDING', 'How to Install RAM Modules', 'Complete guide to correctly installing memory modules in your computer.', '<h3>Installing RAM Modules</h3>\r\n<p>Installing or reseating RAM is a straightforward task that requires careful handling to ensure optimal system performance and avoid damaging components. Begin by shutting down your system and unplugging the power cable. Discharge any static electricity by pressing the power button after unplugging and touching the metal part of the case. This step ensures the safe handling of sensitive components. RAM sticks should be aligned with the slot key to prevent incorrect installation. Once aligned, apply even pressure until the RAM clicks into place. Regular maintenance, such as cleaning slots with compressed air, can enhance system reliability.</p>\r\n<hr>\r\n<h3>Steps and Tips for Installing RAM</h3>\r\n<h4>Preparing the System</h4>\r\n<ol>\r\n<li>\r\n<p><strong>Power Down and Discharge</strong>:</p>\r\n<ul>\r\n<li>Shut off the system and unplug the power cable.</li>\r\n<li>Press the power button to discharge residual electricity.</li>\r\n<li>Touch the metal part of the case to neutralize static electricity.</li>\r\n</ul>\r\n</li>\r\n<li>\r\n<p><strong>Remove Existing RAM</strong>:</p>\r\n<ul>\r\n<li>Unclip the sides of the RAM slot.</li>\r\n<li>Gently pull the RAM stick straight up to remove it.</li>\r\n<li>Repeat for all installed RAM sticks if necessary.</li>\r\n</ul>\r\n</li>\r\n</ol>\r\n<h4>Installing the RAM</h4>\r\n<ol start=\"3\">\r\n<li>\r\n<p><strong>Align the RAM Stick</strong>:</p>\r\n<ul>\r\n<li>Identify the key (notch) in the middle of the RAM stick.</li>\r\n<li>Match it with the slot\'s key to ensure correct orientation.</li>\r\n</ul>\r\n</li>\r\n<li>\r\n<p><strong>Insert the RAM</strong>:</p>\r\n<ul>\r\n<li>Gently place the RAM stick into the slot.</li>\r\n<li>Apply even pressure on both ends until it clicks into place.</li>\r\n</ul>\r\n</li>\r\n<li>\r\n<p><strong>Secure the RAM</strong>:</p>\r\n<ul>\r\n<li>Ensure the clips on the sides of the slot automatically lock onto the RAM stick.</li>\r\n</ul>\r\n</li>\r\n</ol>\r\n<h4>Post-Installation Checks</h4>\r\n<ol start=\"6\">\r\n<li>\r\n<p><strong>Verify Installation in BIOS</strong>:</p>\r\n<ul>\r\n<li>Access the BIOS to confirm the RAM is detected.</li>\r\n<li>Adjust memory timings if necessary for optimal performance.</li>\r\n</ul>\r\n</li>\r\n<li>\r\n<p><strong>Check RAM in the OS</strong>:</p>\r\n<ul>\r\n<li>Use your operating system to verify the total RAM recognized.</li>\r\n</ul>\r\n</li>\r\n<li>\r\n<p><strong>Perform Maintenance</strong>:</p>\r\n<ul>\r\n<li>Use compressed air to clean the slots before inserting RAM to remove debris or dust.</li>\r\n</ul>\r\n</li>\r\n</ol>\r\n<p>&nbsp;</p>', 'videos/CGV0rmKUkL9BdzVVSxzp6iMBSIqLvDZHw4gx064M.mp4', '2024-11-29 23:25:59', '2024-11-30 00:00:38'),
(4, 'PC BUILDING', 'How to Install a Power Supply Unit', 'Comprehensive instructions for mounting and connecting a power supply in your PC case.', 'Step-by-step process of PSU installation, cable management, and proper connections.', NULL, '2024-11-29 23:25:59', '2024-11-29 23:25:59'),
(5, 'PC BUILDING', 'How to Install PC Case Fans', 'Detailed guide to installing and optimizing airflow with case fans.', 'Techniques for mounting case fans, understanding airflow direction, and creating optimal cooling configuration.', NULL, '2024-11-29 23:25:59', '2024-11-29 23:25:59'),
(6, 'TROUBLESHOOTING AND OS INSTALLATION', 'Windows Installation Guide', 'Step-by-step guide to installing Windows operating system on your new PC.', 'Creating bootable USB, BIOS settings, installation process, and initial setup.', NULL, '2024-11-29 23:25:59', '2024-11-29 23:25:59'),
(7, 'TROUBLESHOOTING AND OS INSTALLATION', 'Common PC Troubleshooting Techniques', 'Learn how to diagnose and resolve common PC hardware and software issues.', 'Troubleshooting boot problems, blue screens, driver issues, and performance optimization.', NULL, '2024-11-29 23:25:59', '2024-11-29 23:25:59'),
(8, 'TROUBLESHOOTING AND OS INSTALLATION', 'Driver Management and Updates', 'Master the art of managing and updating device drivers for optimal system performance.', 'Finding, installing, and updating drivers for various hardware components.', NULL, '2024-11-29 23:25:59', '2024-11-29 23:25:59');

--
-- Dumping data for table `motherboards`
--

INSERT INTO `motherboards` (`id`, `name`, `socket`, `form_factor`, `max_memory`, `memory_slots`, `ram_slots`, `storage_interface`, `sata_connectors`, `pcie_slots`, `ram_generation`, `color`, `image`, `tdp`, `created_at`, `updated_at`) VALUES
(1, 'ASUS ROG Strix B550-F Gaming', 'AM4', 'ATX', 128, 4, 4, 'PCIe 4.0, SATA III', 6, 3, 'DDR4', 'Black', 'component_images/motherboards/motherboards-1.png', 65, NULL, '2024-11-29 23:10:18'),
(2, 'MSI MPG Z690 Carbon WiFi', 'LGA1700', 'ATX', 128, 4, 4, 'PCIe 5.0, SATA III', 6, 4, 'DDR5', 'Black', 'component_images/motherboards/motherboards-2.png', 75, NULL, '2024-11-29 23:11:00'),
(3, 'Gigabyte B660M DS3H AX DDR4', 'LGA1700', 'Micro-ATX', 128, 4, 4, 'PCIe 4.0, SATA III', 4, 2, 'DDR4', 'Blue', 'component_images/motherboards/motherboards-3.png', 55, NULL, '2024-11-29 23:11:49'),
(4, 'ASRock X570 Phantom Gaming 4', 'AM4', 'ATX', 128, 4, 4, 'PCIe 4.0, SATA III', 6, 3, 'DDR4', 'Black', 'component_images/motherboards/motherboards-4.png', 65, NULL, '2024-11-29 23:13:00'),
(5, 'ASUS Prime B660M-A DDR4', 'LGA1700', 'Micro-ATX', 128, 4, 4, 'PCIe 4.0, SATA III', 4, 2, 'DDR4', 'White', 'component_images/motherboards/motherboards-5.jpg', 55, NULL, '2024-11-29 23:13:20'),
(6, 'MSI MAG B550 TOMAHAWK', 'AM4', 'ATX', 128, 4, 4, 'PCIe 4.0, SATA III', 6, 3, 'DDR4', 'Black', 'component_images/motherboards/motherboards-6.png', 65, NULL, '2024-11-29 23:14:05'),
(7, 'Gigabyte Z690 AORUS Elite AX DDR4', 'LGA1700', 'ATX', 128, 4, 4, 'PCIe 5.0, SATA III', 6, 4, 'DDR4', 'Black', 'component_images/motherboards/motherboards-7.png', 75, NULL, '2024-11-29 23:15:30'),
(8, 'ASRock B550M PRO4', 'AM4', 'Micro-ATX', 128, 4, 4, 'PCIe 4.0, SATA III', 4, 2, 'DDR4', 'Black', 'component_images/motherboards/motherboards-8.png', 55, NULL, '2024-11-29 23:16:20'),
(9, 'ASUS TUF Gaming B550M-PLUS', 'AM4', 'Micro-ATX', 128, 4, 4, 'PCIe 4.0, SATA III', 4, 2, 'DDR4', 'Black', 'component_images/motherboards/motherboards-9.png', 55, NULL, '2024-11-29 23:16:28'),
(10, 'MSI PRO Z690-A DDR4', 'LGA1700', 'ATX', 128, 4, 4, 'PCIe 5.0, SATA III', 6, 3, 'DDR4', 'Black', 'component_images/motherboards/motherboards-10.png', 75, NULL, '2024-11-29 23:17:36');

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('virtuciogerardmichael17@gmail.com', '$2y$12$9ApY0MuYXhC2g8xYJtTaaO4lzZB1ddvaO5ql2bo0JmSPQF3OTFAyO', '2024-11-30 11:15:36');

--
-- Dumping data for table `power_supplies`
--

INSERT INTO `power_supplies` (`id`, `name`, `type`, `efficiency`, `wattage`, `modular`, `color`, `max_tdp`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Corsair RM850x (2021)', 'ATX', '80+ Gold', 850, 'Full', 'Black', 750, 'component_images/power_supplies/power_supplies-1.png', NULL, '2024-11-30 12:19:43'),
(2, 'EVGA SuperNOVA 750 G5', 'ATX', '80+ Gold', 750, 'Full', 'Black', 650, 'component_images/power_supplies/power_supplies-2.png', NULL, '2024-11-30 12:19:57'),
(3, 'Seasonic FOCUS GX-750', 'ATX', '80+ Gold', 750, 'Full', 'Black', 700, 'component_images/power_supplies/power_supplies-3.jpg', NULL, '2024-11-30 12:20:33'),
(4, 'Corsair RM1000x (2021)', 'ATX', '80+ Gold', 1000, 'Full', 'Black', 900, 'component_images/power_supplies/power_supplies-4.jpg', NULL, '2024-11-30 12:20:42'),
(5, 'be quiet! Dark Power Pro 12', 'ATX', '80+ Titanium', 1200, 'Full', 'Black', 1000, 'component_images/power_supplies/power_supplies-5.png', NULL, '2024-11-30 12:20:57'),
(6, 'NZXT C850', 'ATX', '80+ Gold', 850, 'Full', 'White', 750, 'component_images/power_supplies/power_supplies-6.jpg', NULL, '2024-11-30 12:21:05'),
(7, 'Thermaltake Toughpower GF1 750W', 'ATX', '80+ Gold', 750, 'Full', 'Black', 650, 'component_images/power_supplies/power_supplies-7.png', NULL, '2024-11-30 12:21:38'),
(8, 'Cooler Master V850 Gold', 'ATX', '80+ Gold', 850, 'Full', 'Black', 750, 'component_images/power_supplies/power_supplies-8.png', NULL, '2024-11-30 12:21:18'),
(9, 'ASUS ROG Thor 850W Platinum', 'ATX', '80+ Platinum', 850, 'Full', 'Black', 800, 'component_images/power_supplies/power_supplies-9.png', NULL, '2024-11-30 12:21:26');

--
-- Dumping data for table `rams`
--

INSERT INTO `rams` (`id`, `name`, `speed_ddr_version`, `speed_mhz`, `modules`, `module_size`, `ram_generation`, `color`, `first_word_latency`, `cas_latency`, `tdp`, `image`, `created_at`, `updated_at`) VALUES
(1, 'G.Skill Ripjaws V 32GB (2x16GB) DDR4-3600', 4, 3600, 2, 16, 'DDR4', 'Black', 16.00, 16.00, 5, 'component_images/rams/rams-1.jpg', NULL, '2024-11-30 12:09:05'),
(2, 'Corsair Vengeance RGB Pro SL 32GB (2x16GB) DDR5-5600', 5, 5600, 2, 16, 'DDR5', 'White', 36.00, 36.00, 6, 'component_images/rams/rams-2.jpg', NULL, '2024-11-30 12:09:55'),
(3, 'Crucial Ballistix 16GB (2x8GB) DDR4-3200', 4, 3200, 2, 8, 'DDR4', 'Black', 16.00, 16.00, 4, 'component_images/rams/rams-3.png', NULL, '2024-11-30 12:10:10'),
(4, 'Team T-Force Vulcan Z 32GB (2x16GB) DDR4-3600', 4, 3600, 2, 16, 'DDR4', 'Red', 18.00, 18.00, 5, 'component_images/rams/rams-4.png', NULL, '2024-11-30 12:10:29'),
(5, 'Corsair Dominator Platinum RGB 64GB (4x16GB) DDR5-6000', 5, 6000, 4, 16, 'DDR5', 'Black', 36.00, 36.00, 8, 'component_images/rams/rams-5.png', NULL, '2024-11-30 12:10:38'),
(6, 'Kingston FURY Beast 16GB (2x8GB) DDR4-3200', 4, 3200, 2, 8, 'DDR4', 'Black', 16.00, 16.00, 4, 'component_images/rams/rams-6.png', NULL, '2024-11-30 12:10:54'),
(7, 'G.Skill Trident Z5 RGB 32GB (2x16GB) DDR5-5600', 5, 5600, 2, 16, 'DDR5', 'Silver', 36.00, 36.00, 6, 'component_images/rams/rams-7.png', NULL, '2024-11-30 12:10:46'),
(8, 'Patriot Viper Steel 32GB (2x16GB) DDR4-3200', 4, 3200, 2, 16, 'DDR4', 'Gray', 16.00, 16.00, 5, 'component_images/rams/rams-8.jpg', NULL, '2024-11-30 12:12:51'),
(9, 'TEAMGROUP T-Create Expert 64GB (2x32GB) DDR4-3600', 4, 3600, 2, 32, 'DDR4', 'White', 18.00, 18.00, 6, 'component_images/rams/rams-9.png', NULL, '2024-11-30 12:12:26'),
(10, 'Corsair Vengeance LPX 16GB (2x8GB) DDR4-2666', 4, 2666, 2, 8, 'DDR4', 'Black', 16.00, 16.00, 3, 'component_images/rams/rams-10.jpg', NULL, '2024-11-30 12:12:38');

--
-- Dumping data for table `storages`
--

INSERT INTO `storages` (`id`, `name`, `storage_type`, `capacity`, `drive_type`, `cache`, `form_factor`, `interface`, `tdp`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Samsung 980 PRO 1TB PCIe 4.0 NVMe SSD', 'SSD', 1000, 'NVMe', 1024, 'M.2', 'PCIe 4.0', 5, 'component_images/storages/storages-1.png', NULL, '2024-11-30 12:25:02'),
(2, 'Western Digital Blue SN570 1TB NVMe SSD', 'SSD', 1000, 'NVMe', 512, 'M.2', 'PCIe 3.0', 3, 'component_images/storages/storages-2.png', NULL, '2024-11-30 12:24:18'),
(3, 'Crucial P5 Plus 2TB PCIe 4.0 NVMe SSD', 'SSD', 2000, 'NVMe', 2048, 'M.2', 'PCIe 4.0', 6, 'component_images/storages/storages-3.png', NULL, '2024-11-30 12:24:26'),
(4, 'Seagate FireCuda 530 500GB NVMe SSD', 'SSD', 500, 'NVMe', 512, 'M.2', 'PCIe 4.0', 4, 'component_images/storages/storages-4.png', NULL, '2024-11-30 12:24:40'),
(5, 'Samsung 870 EVO 1TB SATA SSD', 'SSD', 1000, 'SATA', 512, '2.5', 'SATA III', 3, 'component_images/storages/storages-5.png', NULL, '2024-11-30 12:24:53'),
(6, 'WD Black SN850 2TB PCIe 4.0 NVMe SSD', 'SSD', 2000, 'NVMe', 2048, 'M.2', 'PCIe 4.0', 7, 'component_images/storages/storages-6.png', NULL, '2024-11-30 12:25:56'),
(7, 'Crucial MX500 2TB SATA SSD', 'SSD', 2000, 'SATA', 1024, '2.5', 'SATA III', 3, 'component_images/storages/storages-7.png', NULL, '2024-11-30 12:25:43'),
(8, 'Sabrent Rocket 4 Plus 1TB PCIe 4.0 NVMe SSD', 'SSD', 1000, 'NVMe', 1024, 'M.2', 'PCIe 4.0', 5, 'component_images/storages/storages-8.jpg', NULL, '2024-11-30 12:25:27'),
(9, 'Toshiba P300 4TB 7200RPM HDD', 'HDD', 4000, 'SATA', 128, '3.5', 'SATA III', 8, 'component_images/storages/storages-9.png', NULL, '2024-11-30 12:25:10'),
(10, 'Seagate BarraCuda 2TB 7200RPM HDD', 'HDD', 2000, 'SATA', 256, '3.5', 'SATA III', 6, 'component_images/storages/storages-10.png', NULL, '2024-11-30 12:25:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gpus`
--
ALTER TABLE `gpus`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gpus`
--
ALTER TABLE `gpus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the GPU', AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
