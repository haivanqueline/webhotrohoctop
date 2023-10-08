-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2023 at 11:04 AM
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
-- Database: `webhoctap`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookmark`
--

CREATE TABLE `bookmark` (
  `user_id` varchar(20) NOT NULL,
  `danhsach_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookmark`
--

INSERT INTO `bookmark` (`user_id`, `danhsach_id`) VALUES
('Zm479KVgxdvbHGNheysv', '07rcPygOKzj3oc6LDaGt');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` varchar(20) NOT NULL,
  `content_id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `giasu_id` varchar(20) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `content_id`, `user_id`, `giasu_id`, `comment`, `date`) VALUES
('kAcaVguMtGs7uYQRhgZ2', 'u5vQ1Q3fiVfED57mCpY0', 'Zm479KVgxdvbHGNheysv', 'iG3K71X8L3BXlhIfy1bM', 'Hair depj trai', '2023-10-08'),
('aHEN8Ib7SrcDGwgZluOm', 'EbJOH3kjARQIUO2Hqlav', 'Zm479KVgxdvbHGNheysv', 'iG3K71X8L3BXlhIfy1bM', '2', '2023-10-08');

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `id` varchar(20) NOT NULL,
  `giasu_id` varchar(20) NOT NULL,
  `danhsach_id` varchar(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `video` varchar(255) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `giasu_id`, `danhsach_id`, `title`, `desc`, `video`, `thumbnail`, `date`, `status`) VALUES
('jIE28XvTc6S2t6nUdBID', 'iG3K71X8L3BXlhIfy1bM', '07rcPygOKzj3oc6LDaGt', 'Toán lớp 1 phần 1', '1', 'dhS4oVs6FKpkS9Kaq7U9.mp4', 'wvUasYdOTdBJD8lGWmi2.jpg', '2023-10-04', 'Hoạt động'),
('EbJOH3kjARQIUO2Hqlav', 'iG3K71X8L3BXlhIfy1bM', 'M3QabURjGmbQVZQBJYxP', 'Toán lớp 2 phần 1', '2', 'HFuLjT4yMT7dlVe2vfu7.mov', 'duWMDtxC4SGvQsUv1gLy.jpg', '2023-10-04', 'Hoạt động'),
('u5vQ1Q3fiVfED57mCpY0', 'iG3K71X8L3BXlhIfy1bM', '0EmjRdczcFj2ETzf7r5e', 'English part 1', 'Tachahao', 'i6J96v9g66m1iEDNm5t9.mp4', 'bASQy6uWWRMNWh9cUXoH.jpg', '2023-10-04', 'Hoạt động');

-- --------------------------------------------------------

--
-- Table structure for table `danhsach`
--

CREATE TABLE `danhsach` (
  `id` varchar(20) NOT NULL,
  `giasu_id` varchar(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `danhsach`
--

INSERT INTO `danhsach` (`id`, `giasu_id`, `title`, `desc`, `thumbnail`, `date`, `status`) VALUES
('07rcPygOKzj3oc6LDaGt', 'iG3K71X8L3BXlhIfy1bM', 'Toán lớp 1', '1', '9gj9j1fJ6OE1H5gSbXCc.jpg', '2023-10-03', 'Hoạt động'),
('M3QabURjGmbQVZQBJYxP', 'iG3K71X8L3BXlhIfy1bM', 'Toán lớp 2', '2', 'fyzgbuTHl8Vpx5O8oFGt.jpg', '2023-10-03', 'Hoạt động'),
('0EmjRdczcFj2ETzf7r5e', 'iG3K71X8L3BXlhIfy1bM', 'English', 'hello', 'E1UCeQgp3TgWG4VRxu3S.jpg', '2023-10-04', 'Hoạt động');

-- --------------------------------------------------------

--
-- Table structure for table `giasu`
--

CREATE TABLE `giasu` (
  `id` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `job` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `giasu`
--

INSERT INTO `giasu` (`id`, `name`, `job`, `email`, `password`, `image`) VALUES
('iG3K71X8L3BXlhIfy1bM', 'Hải siêu đẹp trai', 'Lập trình viên', 'a@a.a', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'yUF7IBT1BKRIR1RgFXZ2.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `lienhe`
--

CREATE TABLE `lienhe` (
  `name` varchar(55) NOT NULL,
  `email` varchar(55) NOT NULL,
  `number` int(10) NOT NULL,
  `mess` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lienhe`
--

INSERT INTO `lienhe` (`name`, `email`, `number`, `mess`) VALUES
('Huỳnh Huy Hải', 'asddada@das.cc', 2147483647, 'yo');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `user_id` varchar(20) NOT NULL,
  `giasu_id` varchar(20) NOT NULL,
  `content_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`user_id`, `giasu_id`, `content_id`) VALUES
('Zm479KVgxdvbHGNheysv', 'iG3K71X8L3BXlhIfy1bM', 'u5vQ1Q3fiVfED57mCpY0'),
('lkcNeF66SMcFZC2JnZUq', 'iG3K71X8L3BXlhIfy1bM', 'jIE28XvTc6S2t6nUdBID');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `image`) VALUES
('Zm479KVgxdvbHGNheysv', 'hải', 'dadaasa@dsa.cx', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '3QzIsbjQhfobH4s7RLvw.jpg'),
('lkcNeF66SMcFZC2JnZUq', 'Huỳnh Huy Hải', 'asaassassasasaassas@s.s', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'UHWgSbxfK2WeATo6QSbg.jpg');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
