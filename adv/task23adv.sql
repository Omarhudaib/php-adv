-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 23, 2024 at 11:26 AM
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
-- Database: `task23adv`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(50) NOT NULL DEFAULT 'User',
  `mobile` varchar(10) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `role`, `mobile`, `image_path`) VALUES
(3, 'yu', 'omarhdab54321@gmail.com', '$2y$10$m/jGSg6YdXygxovpPM8DXeWNx/BWy4u0ey8.qO5b6/uO0Gjtca6aO', '2024-07-22 18:34:57', 'User', '6844653463', 'uploads/1721722614_keyboard.jpg'),
(4, 'sxDSVZ sacvv', 'o@gmail.com', '$2y$10$7oTaz7QBlR83T99fUphpVefxzEYvqTdN4Kq1pCBQHlJ4SM5MXkzBu', '2024-07-22 19:40:18', 'User', '3612541635', 'uploads/1721721631_iphone.jpg'),
(5, 'Super Admin  jkkl oo', 'admin@example.com', 'hashed_password', '2024-07-22 20:01:03', 'User', '0785184299', 'uploads/1721726530_sfgdzxhfjcgkvhlbj;.png'),
(11, 'rrrrrr', 'uu@gmail.com', '$2y$10$FQcU1MUXH1pTPxUi9B9DXesHXjPZKqL.sM5mgbjXCfP9LO7FQh0D.', '2024-07-22 20:16:38', 'Admin', '58165161', 'uploads/1721722688_Screenshot 2024-05-20 132719.png'),
(12, 'sxDSVZ ddf sacvv ghjjk', 'abosbeah685@gmail.com', '$2y$10$Qjd2YEL7pZKMML8mRdWQNOeM8okgXJ9E7zrgxdJMF4OTqq9wiY8Ba', '2024-07-23 07:04:07', 'Admin', '0785184299', 'uploads/1721722712_Screenshot 2024-05-23 094314.png'),
(13, 'ukhdvkusd,zgvb', 'uwe@ttcom', '$2y$10$mTqVzzl7x4fhJ/Vo6rPO7ugCBw.PeXsChIIq4C3HFp45B.8aa/4Am', '2024-07-23 07:27:25', 'User', '644653453', 'uploads/1721722730_Screenshot 2024-06-23 115014.png'),
(14, 'ttt', 'omarhd21@gmail.com', '$2y$10$Vw/nmD1VvrrvyONpivMtb.hnh6qTXTHpdVOb.L2sMtyj.LcQKfKk2', '2024-07-23 08:39:07', 'User', '666', 'uploads/1721723947_Screenshot 2024-05-23 175523.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
