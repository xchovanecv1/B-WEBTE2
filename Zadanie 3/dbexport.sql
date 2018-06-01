-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Hostiteľ: localhost
-- Čas generovania: St 14.Mar 2018, 20:39
-- Verzia serveru: 5.7.21-0ubuntu0.16.04.1
-- Verzia PHP: 7.0.25-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `zad3`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `login_history`
--

CREATE TABLE `login_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `login_type` varchar(100) NOT NULL,
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Sťahujem dáta pre tabuľku `login_history`
--

INSERT INTO `login_history` (`id`, `user_id`, `login_type`, `time`) VALUES
(1, 1, 'LDAP', '2018-03-13 12:00:00'),
(2, 3, 'LDAP', '2018-03-13 21:48:33'),
(3, 4, 'LDAP', '2018-03-14 00:16:37'),
(4, 5, 'LDAP', '2018-03-14 00:46:06'),
(5, 6, 'LDAP', '2018-03-14 00:47:57'),
(6, 6, 'LDAP', '2018-03-14 00:58:29'),
(7, 6, 'LDAP', '2018-03-14 00:59:16'),
(8, 6, 'Registration', '2018-03-14 10:58:40'),
(9, 8, 'Google', '2018-03-14 13:30:32'),
(10, 8, 'Google', '2018-03-14 13:30:52'),
(11, 8, 'Google', '2018-03-14 13:32:10'),
(12, 8, 'Google', '2018-03-14 13:35:23'),
(13, 6, 'LDAP', '2018-03-14 14:43:25'),
(14, 8, 'Google', '2018-03-14 14:58:21'),
(15, 8, 'Google', '2018-03-14 15:47:17'),
(16, 6, 'LDAP', '2018-03-14 16:38:14'),
(17, 6, 'LDAP', '2018-03-14 16:38:48'),
(18, 6, 'LDAP', '2018-03-14 16:39:24'),
(19, 8, 'Google', '2018-03-14 16:40:14'),
(20, 9, 'Registration', '2018-03-14 16:43:18'),
(21, 9, 'Registration', '2018-03-14 16:57:19'),
(22, 8, 'Google', '2018-03-14 17:03:03'),
(23, 8, 'Google', '2018-03-14 17:04:16'),
(24, 8, 'Google', '2018-03-14 17:04:55'),
(25, 6, 'LDAP', '2018-03-14 17:07:57'),
(26, 11, 'LDAP', '2018-03-14 17:08:24'),
(27, 9, 'Registration', '2018-03-14 17:10:48'),
(28, 8, 'Google', '2018-03-14 17:20:18'),
(29, 8, 'LDAP', '2018-03-14 17:21:50'),
(30, 8, 'LDAP', '2018-03-14 17:23:39'),
(31, 8, 'LDAP', '2018-03-14 17:23:45'),
(32, 8, 'LDAP', '2018-03-14 17:39:35'),
(33, 8, 'LDAP', '2018-03-14 17:39:40'),
(34, 8, 'LDAP', '2018-03-14 17:39:44'),
(35, 8, 'LDAP', '2018-03-14 18:13:07'),
(36, 8, 'Google', '2018-03-14 18:17:06'),
(37, 8, 'Google', '2018-03-14 18:18:13'),
(38, 8, 'Google', '2018-03-14 18:18:21'),
(39, 8, 'LDAP', '2018-03-14 18:24:00'),
(40, 8, 'LDAP', '2018-03-14 18:24:06'),
(41, 8, 'LDAP', '2018-03-14 18:25:45'),
(42, 8, 'Google', '2018-03-14 18:27:05'),
(43, 8, 'Google', '2018-03-14 18:27:47'),
(44, 8, 'Google', '2018-03-14 18:37:20'),
(45, 12, 'LDAP', '2018-03-14 18:39:36'),
(46, 9, 'Registration', '2018-03-14 18:49:36'),
(47, 13, 'Registration', '2018-03-14 19:54:51'),
(48, 13, 'Registration', '2018-03-14 19:54:58'),
(49, 13, 'Registration', '2018-03-14 19:56:31'),
(50, 13, 'Registration', '2018-03-14 19:56:50'),
(51, 13, 'Registration', '2018-03-14 19:57:04'),
(52, 13, 'Registration', '2018-03-14 19:57:25'),
(53, 13, 'Registration', '2018-03-14 19:57:43'),
(54, 13, 'Registration', '2018-03-14 19:58:51'),
(55, 13, 'Registration', '2018-03-14 19:58:58'),
(56, 8, 'LDAP', '2018-03-14 19:59:04'),
(57, 13, 'Registration', '2018-03-14 20:00:54'),
(58, 8, 'LDAP', '2018-03-14 20:00:59'),
(59, 8, 'LDAP', '2018-03-14 20:02:58'),
(60, 13, 'Registration', '2018-03-14 20:03:07'),
(61, 8, 'LDAP', '2018-03-14 20:03:16'),
(62, 8, 'Google', '2018-03-14 20:03:33'),
(63, 13, 'Registration', '2018-03-14 20:08:33'),
(64, 13, 'Registration', '2018-03-14 20:08:46'),
(65, 13, 'Registration', '2018-03-14 20:12:30'),
(66, 8, 'Google', '2018-03-14 20:16:37'),
(67, 13, 'Registration', '2018-03-14 20:29:02'),
(68, 13, 'Registration', '2018-03-14 20:31:01'),
(69, 13, 'Registration', '2018-03-14 20:31:48'),
(70, 1, 'Google', '2018-03-14 20:37:15'),
(71, 1, 'Google', '2018-03-14 20:37:28'),
(72, 1, 'LDAP', '2018-03-14 20:37:39'),
(73, 1, 'Google', '2018-03-14 20:37:50'),
(74, 1, 'Registration', '2018-03-14 20:38:03');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Surname` varchar(50) NOT NULL,
  `Login` varchar(50) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `LDAPLogin` varchar(50) DEFAULT NULL,
  `Google` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Sťahujem dáta pre tabuľku `users`
--

INSERT INTO `users` (`id`, `Name`, `Surname`, `Login`, `Password`, `LDAPLogin`, `Google`) VALUES
(1, 'Peter', 'Kapusta', 'asd', '....', 'ldapname', 'mail@example.com');

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `login_history`
--
ALTER TABLE `login_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pre tabuľku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `login_history`
--
ALTER TABLE `login_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;
--
-- AUTO_INCREMENT pre tabuľku `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
