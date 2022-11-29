-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2022 at 11:23 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `udemy_notes`
--

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `title`, `description`, `created`) VALUES
(11, 'Robić robić!', 'Robić robić!         ', '2022-11-15 10:18:32'),
(12, 'Lista zakupów', 'Kupić\\r\\n- papier toaletowy\\r\\n- mydło', '2022-11-15 10:30:20'),
(13, 'Do nauki', 'PHP', '2022-11-15 18:02:01'),
(17, 'praca na <b>środę</b>', '<script>alert(\"Uruchomiłem skrypt :( !!!\")</script>', '2022-11-16 11:42:24'),
(18, 'Książki do przeczytania', 'Potop\\r\\nPan Wołodyjowski\\r\\nOgniem i mieczem', '2022-11-18 11:22:56'),
(20, 'Zajęcia na poniedziałek', 'Dzień wolny - yes', '2022-11-18 11:22:56'),
(21, 'Prace na wtorek', 'pracowity dzień - oj bardzo', '2022-11-18 11:22:56'),
(22, 'sprawy na wieczór', 'kupić coś do jedzenia', '2022-11-18 11:22:56'),
(23, 'test', 'testowy', '2022-11-18 11:22:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
