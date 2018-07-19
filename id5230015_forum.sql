-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 19, 2018 at 06:48 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id5230015_forum`
--

-- --------------------------------------------------------

--
-- Table structure for table `threads`
--

CREATE TABLE `threads` (
  `thread_id` int(11) NOT NULL,
  `subject` varchar(9999) COLLATE utf8_unicode_ci NOT NULL,
  `content` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `creator` varchar(9999) COLLATE utf8_unicode_ci NOT NULL,
  `views` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `profile_pic` varchar(9999) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'images/default_user.png',
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `replies` int(11) NOT NULL DEFAULT '0',
  `score` int(11) NOT NULL DEFAULT '0',
  `topics` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `profile_pic`, `username`, `password`, `replies`, `score`, `topics`) VALUES
(1, 'images/FVOmXrK.jpg', 'Debashish', '$2a$09$0PW8gtxNOSLjnp1ldZFok.RkA8z5JWRXEPt30CCt2Lrwu3VoWk7gq', 0, 0, 0),
(2, 'images/default_user.png', 'Zanark', '$2a$09$fFMRssUOQMmmXbFwkFn4DeVorE05sRWREUv6sYFbdMDwP.w1hnQcq', 0, 0, 0),
(3, 'images/FVOmXrK.jpg', 'admin', '$2a$09$q8usQ7ACQWLoLpemTZ7WVe/OOBWJGSghaqI3zGfvesizJ.jP5Igvu', 0, 0, 0),
(4, 'images/armenian_beauty_tavush_hayk_photography_2-wallpaper-1366x768.jpg', 'Satwik', '$2a$09$PMXuC9uYko7v7PXidN9qnO9ZmSwWB.PJWEv5R6dXtQj1lttfkfzZa', 0, 0, 0),
(5, 'images/default_user.png', 'Debashish', '$2a$09$aBJ3UC7qvM8UE9rX5gS6HOKTxU0k.8.gG/FLOfDMmpM2Z0nq/ffHa', 0, 0, 0),
(6, 'images/default_user.png', 'rupesh', '$2a$09$gUL5UhqhFw1ccVQAp5Q8Ou/CM89Hz5qfICjSp5wKRy2iAzPkubbSK', 0, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `threads`
--
ALTER TABLE `threads`
  ADD PRIMARY KEY (`thread_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `threads`
--
ALTER TABLE `threads`
  MODIFY `thread_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
