-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2023 at 12:19 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library-guard`
--

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE `author` (
  `id` int(11) NOT NULL,
  `firstname` varchar(75) NOT NULL,
  `lastname` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `author`
--

INSERT INTO `author` (`id`, `firstname`, `lastname`) VALUES
(24, 'Nicholas', 'Hayes'),
(25, 'Owen', 'Hopkins'),
(26, 'Vikram', 'Bhatt'),
(27, 'Michael', 'Wehmeyer'),
(28, 'Gabi', 'Baramki'),
(29, 'Joe', 'Armstrong'),
(30, 'Paola', 'Lombardi'),
(31, 'Jo', 'Teng'),
(32, 'David', 'Hesselgrave'),
(33, 'Byron', 'Earhart'),
(34, 'Voltaire', ''),
(35, 'Narangoa', 'Li'),
(36, 'Shevchenko', ''),
(37, 'Belyj', '');

-- --------------------------------------------------------

--
-- Table structure for table `bookcase`
--

CREATE TABLE `bookcase` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `capacity` int(20) NOT NULL,
  `description` text NOT NULL,
  `no_of_shelf` int(11) NOT NULL DEFAULT 4,
  `shelf_capacity` int(11) NOT NULL DEFAULT 3,
  `user_id` int(11) DEFAULT NULL,
  `archive` tinyint(1) NOT NULL DEFAULT 0,
  `timestamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookcase`
--

INSERT INTO `bookcase` (`id`, `name`, `capacity`, `description`, `no_of_shelf`, `shelf_capacity`, `user_id`, `archive`, `timestamp`) VALUES
(52, 'Project Management Books', 6, 'Bookcase for PM Books', 4, 3, 3, 0, '2022-09-17 00:41:11'),
(53, 'Web Development Books', 6, 'Bookcase for Web Development books', 4, 3, 3, 0, '2022-09-17 00:41:35');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `author_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL,
  `isbn` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `bookcase_id` int(11) DEFAULT NULL,
  `shelf_no` int(11) DEFAULT NULL,
  `heap` tinyint(1) NOT NULL,
  `timestamp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author_id`, `genre_id`, `isbn`, `price`, `bookcase_id`, `shelf_no`, `heap`, `timestamp`) VALUES
(47, 'Frank Lloyd Wrights Forgotten House', 24, 16, '9780299331832', 52.00, 52, 2, 0, '2022-09-16 16:20:16'),
(48, 'Multiform : Architecture in an Age of Transition', 25, 16, '9781119717683', 35.50, 52, 2, 0, '2022-09-17 00:21:43'),
(49, 'Blueprint for a Hack : Leveraging Informal Building Practices', 26, 16, '9781638409014', 20.00, 52, 2, 0, '2022-09-17 00:22:05'),
(50, 'Promoting Self-Determination in Students with Developmental Disabilities', 27, 5, '9781593857394', 105.00, 52, 2, 0, '2022-09-17 00:23:44'),
(51, 'Turtle Hypodermic of Sickenpods : Liberal Studies in the Corporate Age', 25, 16, '9780773568785', 36.00, 53, 2, 0, '2022-09-17 00:24:06'),
(52, 'Peaceful Resistance : Building a Palestinian University Under Occupation', 28, 5, '9780745329321', 55.00, 53, 2, 0, '2022-09-17 00:24:54'),
(53, 'Improving Your Splunk Skills : Leverage the Operational Intelligence Capabilities of Splunk to Unlock New Hidden Business Insights', 29, 13, '9781838981020', 200.00, 53, 1, 0, '2022-09-17 00:26:07'),
(54, 'Learn You Some Erlang for Great Good! : A Beginners Guide', 24, 16, '9781849514231', 155.00, NULL, NULL, 1, '2022-09-17 00:28:08'),
(55, '	 Le Parti Del Procedimento Amministrativo : Tra Procedimento e Processo', 30, 9, '9788892175396', 158.00, NULL, NULL, 1, '2022-09-17 00:29:50'),
(56, 'Education and Copyright Compliance : A Toolkit', 31, 9, '9781920778347', 123.00, NULL, NULL, 1, '2022-09-17 00:30:29'),
(57, 'Contexalization : Meanings, Methods, and Models', 32, 11, '9781645083290', 45.65, NULL, NULL, 1, '2022-09-17 00:31:42'),
(58, 'Deconstructing Terrorist Violence : Faith As a Mask', 32, 11, '9789351502098', 59.99, NULL, NULL, 1, '2022-09-17 00:32:05'),
(59, 'Mount Fuji : Icon of Japan', 33, 16, '9781611172058', 145.00, 53, 1, 0, '2022-09-17 00:32:40'),
(60, 'Christology: a Guide for the Perplexed : A Guide for the Perplexed', 33, 16, '9780567666130', 0.00, 53, 1, 0, '2022-09-17 00:33:07'),
(61, 'Le Christianisme et la Révolution Française : Essai Historique', 24, 6, '9782335048124', 85.00, 53, 2, 0, '2022-09-16 16:35:07'),
(62, 'Historical Atlas of Northeast Asia, 1590-2010 : Korea, Manchuria, Mongolia, Eastern Siberia', 35, 16, '9780231537162', 214.20, NULL, NULL, 1, '2022-09-17 00:34:40'),
(63, 'Space, Knowledge and Power : Foucault and Geography', 24, 12, '9781317051909', 199.00, NULL, NULL, 1, '2022-09-16 16:35:15'),
(64, 'Najmychka', 36, 18, '9781782675105', 125.00, NULL, NULL, 1, '2022-09-16 16:36:22'),
(65, 'Mcyri', 36, 18, '9781782676584', 25.00, 53, 2, 0, '2022-09-17 00:37:08'),
(66, 'Kubok metelej : Russian Language', 37, 10, '9781784374204', 15.00, NULL, NULL, 1, '2022-09-17 00:37:37');

-- --------------------------------------------------------

--
-- Table structure for table `genre`
--

CREATE TABLE `genre` (
  `id` int(11) NOT NULL,
  `genre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genre`
--

INSERT INTO `genre` (`id`, `genre`) VALUES
(16, 'Arts'),
(4, 'Business'),
(5, 'Education'),
(18, 'Fiction'),
(6, 'General'),
(7, 'Health & Medicine'),
(8, 'History & Political Science'),
(17, 'Horror'),
(9, 'Law'),
(10, 'Literature & Language'),
(11, 'Religion & Philosophy'),
(13, 'Science & Technology'),
(12, 'Social Science');

-- --------------------------------------------------------

--
-- Table structure for table `loginlogs`
--

CREATE TABLE `loginlogs` (
  `id` int(11) NOT NULL,
  `IpAddress` varbinary(16) NOT NULL,
  `TryTime` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `loginlogs`
--

INSERT INTO `loginlogs` (`id`, `IpAddress`, `TryTime`) VALUES
(99, 0x3a3a31, 1683194405),
(100, 0x3a3a31, 1683194731),
(101, 0x3a3a31, 1683194746);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `user_type` varchar(10) NOT NULL DEFAULT 'user',
  `active` tinyint(1) NOT NULL,
  `password` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `user_type`, `active`, `password`) VALUES
(1, 'anton', 'antonraphaelcaballes@gmail.com', 'admin', 1, '202cb962ac59075b964b07152d234b70'),
(2, 'KC', 'arcekc@gmail.com', 'admin', 1, '202cb962ac59075b964b07152d234b70'),
(3, 'jason', 'json@gmail.com', 'user', 1, 'caf1a3dfb505ffed0d024130f58c5cfa'),
(5, 'Tom', 'tom@gmail.com', 'user', 1, 'caf1a3dfb505ffed0d024130f58c5cfa'),
(22, 'jakecalub', 'arce.kcmarie@gmail.com', 'admin', 1, '202cb962ac59075b964b07152d234b70'),
(30, '12200387', '12200387@gmail.com', 'user', 1, 'caf1a3dfb505ffed0d024130f58c5cfa'),
(31, 'jake', 'jake@gmail.com', 'user', 1, '2b6749c2bf9fe84fe630b35aa76aeacd'),
(32, 'brady', 'brady@gmail.com', 'approval', 0, '8a918b2564d28995b91eab27293373c9'),
(33, 'smith', 'smith@gmail.com', 'user', 1, '374e33945470bc878dfe61b243bbce66'),
(34, 'bhumika', 'bhumika@students.koi.edu.au', 'approval', 0, '374e33945470bc878dfe61b243bbce66'),
(35, 'susan', 'susan@students.koi.edu.au', 'approval', 0, '374e33945470bc878dfe61b243bbce66'),
(36, 'nikki', 'nikki@gmail.com', 'approval', 0, '374e33945470bc878dfe61b243bbce66');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookcase`
--
ALTER TABLE `bookcase`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookcase_id` (`bookcase_id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Indexes for table `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `genre` (`genre`);

--
-- Indexes for table `loginlogs`
--
ALTER TABLE `loginlogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `author`
--
ALTER TABLE `author`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `bookcase`
--
ALTER TABLE `bookcase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `genre`
--
ALTER TABLE `genre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `loginlogs`
--
ALTER TABLE `loginlogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookcase`
--
ALTER TABLE `bookcase`
  ADD CONSTRAINT `bookcase_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`bookcase_id`) REFERENCES `bookcase` (`id`),
  ADD CONSTRAINT `books_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `author` (`id`),
  ADD CONSTRAINT `books_ibfk_3` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
