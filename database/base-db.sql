-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Gegenereerd op: 25 aug 2022 om 11:39
-- Serverversie: 5.7.25
-- PHP-versie: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `presentie`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `admin_user`
--

CREATE TABLE `admin_user` (
  `admin_user_id` int(11) UNSIGNED NOT NULL,
  `email` varbinary(600) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `password_token` varchar(255) DEFAULT NULL,
  `password_changed` timestamp NULL DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `admin_user`
--

INSERT INTO `admin_user` (`admin_user_id`, `email`, `password`, `password_token`, `password_changed`, `datetime`) VALUES
(2, 0xabd3457b85a549e4238f491e629417c0a2d174bb0dbbb664f47865a7747e4085, '$2y$10$JOEIOnJOzfYcQ4P5q3Dry.cevu9PmVbphd1z3M7K9LFoSVPpOpIXG', '', '2022-08-24 12:20:08', '2021-03-04 08:06:57'),
(3, 0xa66405a8e3bcddbf9617c7913df58d44, NULL, NULL, NULL, '2022-04-25 10:26:46'),
(4, 0x6f43b1c1867008d0f6609030f25e1b80, '$2y$10$Iv2sRXiM.Iw4VwNcRHltZeaq2pTON4GUste1iz4snA2FBnFHCV4Ce', '', '2022-04-26 07:19:22', '2022-04-25 10:27:02'),
(5, 0xcdba00cd117bcb8925663c731cb40bf4, NULL, NULL, NULL, '2022-04-25 10:27:07'),
(6, 0x90684ab8c42d167f2e53a16bdd00bad8, NULL, NULL, NULL, '2022-04-25 10:27:29'),
(7, 0x3082ec2d4c5ac813f7ff4e17ee6edc38a2d174bb0dbbb664f47865a7747e4085, NULL, NULL, NULL, '2022-04-25 10:27:34'),
(8, 0x340302d1b33b5b444c362a517c9fb7b2a2d174bb0dbbb664f47865a7747e4085, NULL, NULL, NULL, '2022-04-25 10:27:38');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `presentie`
--

CREATE TABLE `presentie` (
  `presentie_id` int(10) UNSIGNED NOT NULL,
  `check_time` timestamp NOT NULL,
  `check_type` enum('in','out') NOT NULL,
  `check_number` varbinary(600) NOT NULL,
  `check_signature` blob,
  `check_date` date NOT NULL,
  `last_changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `student`
--

CREATE TABLE `student` (
  `student_id` int(11) NOT NULL,
  `std_nr` varbinary(600) NOT NULL,
  `std_voornaam` varbinary(600) NOT NULL,
  `std_tussenvoegsel` varbinary(600) NOT NULL,
  `std_achternaam` varbinary(600) NOT NULL,
  `std_opleiding` varbinary(600) NOT NULL,
  `std_klas` varbinary(600) NOT NULL,
  `deleted` tinyint(1) UNSIGNED NOT NULL,
  `last_changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `admin_user`
--
ALTER TABLE `admin_user`
  ADD PRIMARY KEY (`admin_user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexen voor tabel `presentie`
--
ALTER TABLE `presentie`
  ADD PRIMARY KEY (`presentie_id`);

--
-- Indexen voor tabel `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `std_nr` (`std_nr`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `admin_user`
--
ALTER TABLE `admin_user`
  MODIFY `admin_user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT voor een tabel `presentie`
--
ALTER TABLE `presentie`
  MODIFY `presentie_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `student`
--
ALTER TABLE `student`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT;
