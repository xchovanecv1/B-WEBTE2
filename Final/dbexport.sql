-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Hostiteľ: localhost
-- Čas generovania: Ne 20.Máj 2018, 00:15
-- Verzia serveru: 5.7.21-0ubuntu0.16.04.1
-- Verzia PHP: 7.0.25-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `final`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `address`
--

CREATE TABLE `address` (
  `ID` int(11) NOT NULL,
  `Street` varchar(255) NOT NULL,
  `Number` varchar(100) NOT NULL,
  `ZIP` varchar(20) NOT NULL,
  `City` varchar(255) NOT NULL,
  `Country` varchar(10) DEFAULT NULL,
  `Geo` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Sťahujem dáta pre tabuľku `address`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `route` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Sťahujem dáta pre tabuľku `groups`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `group_users`
--

CREATE TABLE `group_users` (
  `id` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Sťahujem dáta pre tabuľku `group_users`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `login_history`
--

CREATE TABLE `login_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `login_type` varchar(100) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `mailer`
--

CREATE TABLE `mailer` (
  `ID` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `user` int(11) DEFAULT NULL,
  `mail` varchar(100) CHARACTER SET utf8 NOT NULL,
  `sent` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Sťahujem dáta pre tabuľku `mailer`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `newsletter`
--

CREATE TABLE `newsletter` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `text` text CHARACTER SET utf8 NOT NULL,
  `autor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Sťahujem dáta pre tabuľku `newsletter`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `pass_gen`
--

CREATE TABLE `pass_gen` (
  `ID` int(11) NOT NULL,
  `Done` tinyint(1) NOT NULL,
  `URL` varchar(255) CHARACTER SET utf8 NOT NULL,
  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Sťahujem dáta pre tabuľku `pass_gen`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `route`
--

CREATE TABLE `route` (
  `id` int(11) NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `definition` text CHARACTER SET utf8 NOT NULL,
  `length` double DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `creator` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Sťahujem dáta pre tabuľku `route`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `run`
--

CREATE TABLE `run` (
  `ID` int(11) NOT NULL,
  `User` int(11) NOT NULL,
  `Groupid` int(11) DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `Start` time DEFAULT NULL,
  `End` time DEFAULT NULL,
  `StLat` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `StLon` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `EnLat` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `EnLon` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `Length` double NOT NULL,
  `Rate` int(11) DEFAULT NULL,
  `Note` text CHARACTER SET utf8,
  `Route` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Sťahujem dáta pre tabuľku `run`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `schools`
--

CREATE TABLE `schools` (
  `ID` int(11) NOT NULL,
  `Type` varchar(10) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Address` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Sťahujem dáta pre tabuľku `schools`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Mail` varchar(100) NOT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `PassGen` int(11) DEFAULT NULL,
  `School` int(11) DEFAULT NULL,
  `Address` int(11) DEFAULT NULL,
  `Role` int(11) NOT NULL DEFAULT '100',
  `newsletter` int(11) DEFAULT NULL,
  `regdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Sťahujem dáta pre tabuľku `users`
--

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`ID`);

--
-- Indexy pre tabuľku `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pre tabuľku `group_users`
--
ALTER TABLE `group_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pre tabuľku `login_history`
--
ALTER TABLE `login_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pre tabuľku `mailer`
--
ALTER TABLE `mailer`
  ADD PRIMARY KEY (`ID`);

--
-- Indexy pre tabuľku `newsletter`
--
ALTER TABLE `newsletter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexy pre tabuľku `pass_gen`
--
ALTER TABLE `pass_gen`
  ADD PRIMARY KEY (`ID`);

--
-- Indexy pre tabuľku `route`
--
ALTER TABLE `route`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pre tabuľku `run`
--
ALTER TABLE `run`
  ADD PRIMARY KEY (`ID`);

--
-- Indexy pre tabuľku `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`ID`);

--
-- Indexy pre tabuľku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `address`
--
ALTER TABLE `address`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=283;
--
-- AUTO_INCREMENT pre tabuľku `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pre tabuľku `group_users`
--
ALTER TABLE `group_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT pre tabuľku `login_history`
--
ALTER TABLE `login_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pre tabuľku `mailer`
--
ALTER TABLE `mailer`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;
--
-- AUTO_INCREMENT pre tabuľku `newsletter`
--
ALTER TABLE `newsletter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pre tabuľku `pass_gen`
--
ALTER TABLE `pass_gen`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=284;
--
-- AUTO_INCREMENT pre tabuľku `route`
--
ALTER TABLE `route`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pre tabuľku `run`
--
ALTER TABLE `run`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT pre tabuľku `schools`
--
ALTER TABLE `schools`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT pre tabuľku `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=284;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
