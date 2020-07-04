-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 04 Lip 2020, 20:18
-- Wersja serwera: 10.1.35-MariaDB
-- Wersja PHP: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `szkola_z_www`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `klasy`
--

CREATE TABLE `klasy` (
  `id` int(11) NOT NULL,
  `nazwa` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `klasy`
--

INSERT INTO `klasy` (`id`, `nazwa`) VALUES
(1, '1b'),
(2, '2a'),
(3, '1a');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `nauczyciele`
--

CREATE TABLE `nauczyciele` (
  `id` int(11) NOT NULL,
  `imie` text COLLATE utf8_polish_ci NOT NULL,
  `nazwisko` text COLLATE utf8_polish_ci NOT NULL,
  `login` text COLLATE utf8_polish_ci NOT NULL,
  `haslo` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `nauczyciele`
--

INSERT INTO `nauczyciele` (`id`, `imie`, `nazwisko`, `login`, `haslo`) VALUES
(3, 'Adam', 'Dabrowski', 'ad2020', '$2y$10$JpwEDNJwivo8f6KSFA9aY..ZUGH21PA4auiwhMs.7yWKB3pNQb8q2'),
(4, 'Jan', 'Jankowski', 'jj', '$2y$10$lBZWj2swxiQiDIs.ivLbB..UubHvVU89MEk/IaoJrdBwt3plq.2o6'),
(5, 'Jan', 'Nowacki', 'jn', '$2y$10$bsAZQ7FgaCSFYTAYq/78SeZDnShJzmgE2J1hvGJUdeV7kzOy9R5Ru');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `przedmioty`
--

CREATE TABLE `przedmioty` (
  `id` int(11) NOT NULL,
  `nazwa` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `przedmioty`
--

INSERT INTO `przedmioty` (`id`, `nazwa`) VALUES
(1, 'Matematyka'),
(5, 'niem'),
(10, 'art'),
(11, 'PSI'),
(12, 'j. angielski'),
(13, 'historia'),
(14, 'geografia');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uczniowie`
--

CREATE TABLE `uczniowie` (
  `id` int(11) NOT NULL,
  `imie` text COLLATE utf8_polish_ci NOT NULL,
  `nazwisko` text COLLATE utf8_polish_ci NOT NULL,
  `login` text COLLATE utf8_polish_ci NOT NULL,
  `haslo` text COLLATE utf8_polish_ci NOT NULL,
  `klasa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `uczniowie`
--

INSERT INTO `uczniowie` (`id`, `imie`, `nazwisko`, `login`, `haslo`, `klasa_id`) VALUES
(3, 'Ada', 'Adamska', 'aa', '$2y$10$BrJcr82YVvU.Ce9YEbtczeATWi2ZkTRYrpl044Ngs89y/Dr3m4l1.', 1),
(4, 'Rafal', 'Kowalski', 'rafix', '$2y$10$YNfiLwPbzz5vVd6xLsgHjeRgNBYhFf9JUkE2iJWpZPEjOz5wwrCQC', 1),
(5, 'Marek', 'Markowski', 'mm', '$2y$10$KjC2wOftbfpIpYLwL43HL.G5HFcS8nmI0UgcHmXqIBomUTXbXbmnq', 2),
(6, 'Adrian', 'Duda', 'ad', '$2y$10$uRdeAgTi4HlvpL2788K/q..rSkUIuNQ3yA3meGPZmapfTf5DQzoaK', 1),
(7, 'Antek', 'Antkowiak', 'AnaN', '$2y$10$bpl5evdOdpotjfxr1XZ/aeT8P2JVpjmNqHnGYxLM/Af5P6B/PB2ly', 1),
(8, 'Zofia', 'Nowak', 'zofnow', '$2y$10$xcx0oPt4vBtEbcyYL8tbI.zvqCPgYU27M1lOyj0Dt6.Hf1/lI/mui', 2);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zadania`
--

CREATE TABLE `zadania` (
  `id` int(11) NOT NULL,
  `przedmiot_id` int(11) NOT NULL,
  `typ` int(11) NOT NULL,
  `tresc` text COLLATE utf8_polish_ci NOT NULL,
  `klucz` text COLLATE utf8_polish_ci NOT NULL,
  `alt` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `zadania`
--

INSERT INTO `zadania` (`id`, `przedmiot_id`, `typ`, `tresc`, `klucz`, `alt`) VALUES
(2, 12, 1, 'I # pizza.', 'like', ''),
(3, 12, 1, 'She # pasta.', 'likes', ''),
(6, 12, 1, 'We # eaten my breakfest.', 'have', ''),
(7, 12, 1, 'We # eaten my breakfest.', 'have', ''),
(8, 12, 1, 'I have never # to London.', 'been', ''),
(9, 12, 1, 'They live # London', 'in', ''),
(10, 12, 1, 'Where # you live?', 'do', ''),
(11, 12, 1, 'He # do it tomorrow.', 'will', ''),
(12, 12, 1, 'We live # Poznan.', 'in', '');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zajecia`
--

CREATE TABLE `zajecia` (
  `id` int(11) NOT NULL,
  `nauczyciel_id` int(11) NOT NULL,
  `przedmiot_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `zajecia`
--

INSERT INTO `zajecia` (`id`, `nauczyciel_id`, `przedmiot_id`) VALUES
(2, 3, 1),
(3, 3, 11),
(4, 4, 1),
(5, 4, 5),
(6, 5, 1),
(7, 5, 12),
(8, 5, 13),
(9, 5, 14),
(10, 5, 15);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `klasy`
--
ALTER TABLE `klasy`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `nauczyciele`
--
ALTER TABLE `nauczyciele`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `przedmioty`
--
ALTER TABLE `przedmioty`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `uczniowie`
--
ALTER TABLE `uczniowie`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `zadania`
--
ALTER TABLE `zadania`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `zajecia`
--
ALTER TABLE `zajecia`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `klasy`
--
ALTER TABLE `klasy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT dla tabeli `nauczyciele`
--
ALTER TABLE `nauczyciele`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT dla tabeli `przedmioty`
--
ALTER TABLE `przedmioty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT dla tabeli `uczniowie`
--
ALTER TABLE `uczniowie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT dla tabeli `zadania`
--
ALTER TABLE `zadania`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT dla tabeli `zajecia`
--
ALTER TABLE `zajecia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
