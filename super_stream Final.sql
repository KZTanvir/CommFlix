-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 28, 2023 at 06:24 AM
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
-- Database: `super_stream`
--

-- --------------------------------------------------------

--
-- Table structure for table `cms_category`
--

CREATE TABLE `cms_category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_chat`
--

CREATE TABLE `cms_chat` (
  `m_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_comments`
--

CREATE TABLE `cms_comments` (
  `id` int(11) NOT NULL,
  `mid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `comment_text` text DEFAULT NULL,
  `comment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_movies`
--

CREATE TABLE `cms_movies` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `movie_file` text NOT NULL,
  `movie_cover` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `status` enum('published','upcoming','archived','') NOT NULL DEFAULT 'published',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_user`
--

CREATE TABLE `cms_user` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `profile_link` text DEFAULT NULL,
  `password` varchar(50) NOT NULL,
  `type` int(11) NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT 0,
  `confirmation_code` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cms_user`
--

INSERT INTO `cms_user` (`id`, `first_name`, `last_name`, `email`, `profile_link`, `password`, `type`, `deleted`, `confirmation_code`) VALUES
(1, 'Kamruzzman', 'Tanvir', 'admin@kamruzzaman.tech', 'https://img.freepik.com/premium-vector/city-running-flat-collage-composition-abstract-image-people-doing-athletics-running-vector-illustration_1284-79341.jpg?t=st=1697049357~exp=1697049957~hmac=7327f817651f87cbd74ab89ba82b2fbdd8e0921e64ecb80953f33fe2c4da6978.webp', '21232f297a57a5a743894a0e4a801fc3', 1, 0, 'gd7cNhsk');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cms_category`
--
ALTER TABLE `cms_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_chat`
--
ALTER TABLE `cms_chat`
  ADD PRIMARY KEY (`m_id`);

--
-- Indexes for table `cms_comments`
--
ALTER TABLE `cms_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_movies`
--
ALTER TABLE `cms_movies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_user`
--
ALTER TABLE `cms_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cms_category`
--
ALTER TABLE `cms_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cms_chat`
--
ALTER TABLE `cms_chat`
  MODIFY `m_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cms_comments`
--
ALTER TABLE `cms_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cms_movies`
--
ALTER TABLE `cms_movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cms_user`
--
ALTER TABLE `cms_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
