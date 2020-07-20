-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 20 Lip 2020, 14:33
-- Wersja serwera: 10.4.13-MariaDB
-- Wersja PHP: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
-- Struktura tabeli dla tabeli `odczytane`
--

CREATE TABLE `odczytane` (
  `id` int(11) NOT NULL,
  `uczen_id` int(11) NOT NULL,
  `powiadomienie_id` int(11) NOT NULL,
  `odcz` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `odczytane`
--

INSERT INTO `odczytane` (`id`, `uczen_id`, `powiadomienie_id`, `odcz`) VALUES
(31, 4, 16, 0),
(32, 6, 16, 0),
(33, 7, 16, 0),
(35, 4, 16, 0),
(36, 6, 16, 0),
(37, 7, 16, 0),
(39, 4, 17, 0),
(40, 6, 17, 0),
(41, 7, 17, 0),
(43, 4, 18, 0),
(44, 6, 18, 0),
(45, 7, 18, 0),
(47, 4, 19, 0),
(48, 6, 19, 0),
(49, 7, 19, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `powiadomienia`
--

CREATE TABLE `powiadomienia` (
  `id` int(11) NOT NULL,
  `nauczyciel_id` int(11) NOT NULL,
  `klasa_id` int(11) NOT NULL,
  `przedmiot_id` int(11) NOT NULL,
  `wiadomosc` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `powiadomienia`
--

INSERT INTO `powiadomienia` (`id`, `nauczyciel_id`, `klasa_id`, `przedmiot_id`, `wiadomosc`) VALUES
(3, 4, 2, 5, 'Szanowni Państwo,\r\nDodałem nowe zagadnienia w zadaniach z J. Niemieckiego pozdrawiam.'),
(4, 4, 1, 1, 'Witam wszystkich bardzo serdecznie.'),
(6, 5, 2, 14, 'Witam,\r\nChciałbym przełożyć dzisiejsze zajęcia z geografii na godz. 16:00\r\nPozdrawiam'),
(17, 4, 1, 5, 'Witam!\r\n\r\nZajęcia odbędą się jutro o 12:30!\r\n\r\nPozdrawiam'),
(18, 4, 1, 1, 'SIEMANKO'),
(19, 4, 1, 1, 'Krystian to Cwel');

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
-- Struktura tabeli dla tabeli `terminarz`
--

CREATE TABLE `terminarz` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(250) NOT NULL,
  `p_wydarzenia` datetime NOT NULL,
  `k_wydarzenia` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(8, 'Zofia', 'Nowak', 'zofnow', '$2y$10$xcx0oPt4vBtEbcyYL8tbI.zvqCPgYU27M1lOyj0Dt6.Hf1/lI/mui', 2),
(9, 'Bart', 'Babcb', 'bb', '$2y$10$Cx2c8iBvysIv1kbGzGjsRuIDgheWgNH/ixIriZ8b3q3P3aoj.Nl3u', 3);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wykonanezadania`
--

CREATE TABLE `wykonanezadania` (
  `id` int(11) NOT NULL,
  `uczen_id` int(11) NOT NULL,
  `zadanieAng_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `wykonanezadania`
--

INSERT INTO `wykonanezadania` (`id`, `uczen_id`, `zadanieAng_id`) VALUES
(1, 3, 2),
(2, 5, 7),
(3, 5, 11),
(4, 5, 12),
(5, 5, 10),
(6, 5, 9),
(7, 5, 3),
(8, 5, 2);

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
(2, 12, 1, 'I # pizza.', 'like', '1'),
(3, 12, 1, 'She # pasta.', 'likes', '2'),
(7, 12, 1, 'We # eaten my breakfest.', 'have', '3'),
(9, 12, 1, 'They live # London', 'in', '4'),
(10, 12, 1, 'Where # you live?', 'do', '5'),
(11, 12, 1, 'He # do it tomorrow.', 'will', '6'),
(12, 12, 1, 'We live # Poznan.', 'in', '7');

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
-- Indeksy dla tabeli `odczytane`
--
ALTER TABLE `odczytane`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `powiadomienia`
--
ALTER TABLE `powiadomienia`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `przedmioty`
--
ALTER TABLE `przedmioty`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `terminarz`
--
ALTER TABLE `terminarz`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `uczniowie`
--
ALTER TABLE `uczniowie`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `wykonanezadania`
--
ALTER TABLE `wykonanezadania`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT dla tabeli `nauczyciele`
--
ALTER TABLE `nauczyciele`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT dla tabeli `odczytane`
--
ALTER TABLE `odczytane`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT dla tabeli `powiadomienia`
--
ALTER TABLE `powiadomienia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT dla tabeli `przedmioty`
--
ALTER TABLE `przedmioty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT dla tabeli `terminarz`
--
ALTER TABLE `terminarz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `uczniowie`
--
ALTER TABLE `uczniowie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT dla tabeli `wykonanezadania`
--
ALTER TABLE `wykonanezadania`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT dla tabeli `zadania`
--
ALTER TABLE `zadania`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT dla tabeli `zajecia`
--
ALTER TABLE `zajecia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
