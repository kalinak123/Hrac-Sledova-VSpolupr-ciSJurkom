-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: localhost:3306
-- Čas generovania: Po 27.Apr 2026, 14:52
-- Verzia serveru: 5.7.24
-- Verzia PHP: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `gametracker`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `player_stats`
--

CREATE TABLE `player_stats` (
  `stat_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `kills` int(11) NOT NULL,
  `deaths` int(11) NOT NULL,
  `wins` int(11) NOT NULL,
  `headshots` int(11) NOT NULL COMMENT 'headshot percentage'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `users`
--

CREATE TABLE `users` (
  `user_ID` int(11) NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(11) DEFAULT '0',
  `balance` int(11) NOT NULL DEFAULT '1000',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Sťahujem dáta pre tabuľku `users`
--

INSERT INTO `users` (`user_ID`, `nickname`, `email`, `password`, `role`, `balance`, `created_at`) VALUES
(1, 'jozojozo', 'jozo@jozo.com', '$2y$10$X8Nl3psBwR8AJu5qh1MYZeDTIqTZubmczPltpRhP4oUuZXshoPu/W', 0, 1000, '2026-04-27 14:41:48');

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `player_stats`
--
ALTER TABLE `player_stats`
  ADD PRIMARY KEY (`stat_id`);

--
-- Indexy pre tabuľku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_ID`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `player_stats`
--
ALTER TABLE `player_stats`
  MODIFY `stat_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `users`
--
ALTER TABLE `users`
  MODIFY `user_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
