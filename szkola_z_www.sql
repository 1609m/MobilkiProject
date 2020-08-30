-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 30 Sie 2020, 12:58
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
  `nauczyciel_id` int(11) NOT NULL,
  `powiadomienie_id` int(11) NOT NULL,
  `odcz` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `ogloszeniazad`
--

CREATE TABLE `ogloszeniazad` (
  `id` int(11) NOT NULL,
  `nauczyciel_id` int(11) NOT NULL,
  `klasa_id` int(11) NOT NULL,
  `przedmiot_id` int(11) NOT NULL,
  `wiadomosc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `ogloszeniazad`
--

INSERT INTO `ogloszeniazad` (`id`, `nauczyciel_id`, `klasa_id`, `przedmiot_id`, `wiadomosc`) VALUES
(67, 4, 1, 1, 'Witam, do juta mają państwo do zrobienia 3 Test\r\nPozdrawam');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `paczkap`
--

CREATE TABLE `paczkap` (
  `id` int(11) NOT NULL,
  `paczkazad_id` int(11) NOT NULL,
  `zadania_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `paczkap`
--

INSERT INTO `paczkap` (`id`, `paczkazad_id`, `zadania_id`) VALUES
(8, 9, 2),
(9, 9, 7),
(10, 9, 35),
(11, 10, 2),
(12, 10, 3),
(13, 10, 12),
(14, 10, 34),
(15, 10, 35),
(36, 18, 2),
(37, 18, 3),
(38, 18, 34),
(39, 18, 35),
(41, 18, 37),
(42, 19, 44),
(43, 19, 42),
(46, 21, 44),
(47, 21, 41),
(48, 22, 3),
(49, 23, 46),
(50, 23, 42),
(51, 24, 44),
(52, 24, 46),
(53, 24, 41),
(54, 24, 42),
(55, 24, 47);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `paczkazad`
--

CREATE TABLE `paczkazad` (
  `id` int(11) NOT NULL,
  `klasa_id` int(11) NOT NULL,
  `przedmiot_id` int(11) NOT NULL,
  `termin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `paczkazad`
--

INSERT INTO `paczkazad` (`id`, `klasa_id`, `przedmiot_id`, `termin`) VALUES
(9, 1, 12, '2020-08-21'),
(10, 1, 12, '2020-08-22'),
(18, 3, 12, '2020-08-30'),
(19, 1, 1, '2020-08-30'),
(21, 1, 1, '2020-08-31'),
(22, 1, 12, '2020-09-02'),
(23, 1, 1, '2020-08-28'),
(24, 1, 1, '2020-09-01');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `powiadomienia`
--

CREATE TABLE `powiadomienia` (
  `id` int(11) NOT NULL,
  `nauczyciel_id` int(11) NOT NULL,
  `nauczycielO_id` int(11) NOT NULL,
  `uczen_id` int(11) NOT NULL,
  `uczenO_id` int(11) NOT NULL,
  `klasa_id` int(11) NOT NULL,
  `przedmiot_id` int(11) NOT NULL,
  `wiadomosc` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `powiadomienia`
--

INSERT INTO `powiadomienia` (`id`, `nauczyciel_id`, `nauczycielO_id`, `uczen_id`, `uczenO_id`, `klasa_id`, `przedmiot_id`, `wiadomosc`) VALUES
(3, 4, 0, 0, 0, 2, 5, 'Szanowni Państwo,\r\nDodałem nowe zagadnienia w zadaniach z J. Niemieckiego pozdrawiam.'),
(4, 4, 0, 0, 0, 1, 1, 'Witam wszystkich bardzo serdecznie.'),
(6, 5, 0, 0, 0, 2, 14, 'Witam,\r\nChciałbym przełożyć dzisiejsze zajęcia z geografii na godz. 16:00\r\nPozdrawiam'),
(17, 4, 0, 0, 0, 1, 5, 'Witam!\r\n\r\nZajęcia odbędą się jutro o 12:30!\r\n\r\nPozdrawiam'),
(196, 0, 3, 4, 0, 0, 11, '123'),
(197, 0, 3, 4, 0, 0, 1, 'eldo'),
(198, 0, 4, 3, 0, 0, 12, '123');

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
(2, 5, 7),
(3, 5, 11),
(4, 5, 12),
(5, 5, 10),
(6, 5, 9),
(7, 5, 3),
(8, 5, 2),
(38, 3, 9),
(39, 3, 2);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wyniki`
--

CREATE TABLE `wyniki` (
  `id` int(11) NOT NULL,
  `uczen_id` int(11) NOT NULL,
  `paczkazad_id` int(11) NOT NULL,
  `wynik` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `wyniki`
--

INSERT INTO `wyniki` (`id`, `uczen_id`, `paczkazad_id`, `wynik`) VALUES
(1, 3, 19, 100),
(3, 3, 24, 40),
(4, 3, 22, 100),
(5, 3, 23, 50);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zadania`
--

CREATE TABLE `zadania` (
  `id` int(11) NOT NULL,
  `przedmiot_id` int(11) NOT NULL,
  `typ` int(11) NOT NULL,
  `tresc` text COLLATE utf8_polish_ci NOT NULL,
  `klucz` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `zadania`
--

INSERT INTO `zadania` (`id`, `przedmiot_id`, `typ`, `tresc`, `klucz`) VALUES
(2, 12, 1, 'I # pizza.', 'like'),
(3, 12, 1, 'She # pasta.', 'likes'),
(7, 12, 1, 'We # eaten my breakfest.', 'have'),
(9, 12, 1, 'They live # London', 'in'),
(10, 12, 1, 'Where # you live?', 'do'),
(11, 12, 1, 'He # do it tomorrow.', 'will'),
(12, 12, 1, 'We live # Poznan.', 'in'),
(34, 12, 2, 'I * you.', ''),
(35, 12, 2, 'What is color of the sun?', ''),
(37, 12, 2, 'She * late at school yesterday.', ''),
(41, 1, 2, 'Ile to jest  2+2x2-(2+2)x2?', ''),
(42, 1, 2, 'Do trzech jednakowych naczyń wlano tyle wody, że w pierwszym naczyniu woda zajmowała 2/3 pojemności, w drugim 3/4 pojemności, a w trzecim 5/7 pojemności danego naczynia.', ''),
(44, 1, 1, '2+2x2-(2+2)x2', '-2'),
(46, 1, 1, '2x2', '4'),
(47, 1, 2, 'Paweł ma ćwierć jabłka a Tomek ma pół jabłka ile mają łącznie jabłek?', '');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zadaniaabc`
--

CREATE TABLE `zadaniaabc` (
  `id` int(11) NOT NULL,
  `zadania_id` int(11) NOT NULL,
  `pytanie` text NOT NULL,
  `TF` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `zadaniaabc`
--

INSERT INTO `zadaniaabc` (`id`, `zadania_id`, `pytanie`, `TF`) VALUES
(63, 34, 'like', 1),
(64, 34, 'seven', 0),
(65, 34, 'hello', 0),
(66, 35, 'green', 0),
(67, 35, 'yellow', 1),
(68, 35, 'black', 0),
(69, 35, 'pink', 0),
(75, 37, 'is', 0),
(76, 37, 'has', 0),
(77, 37, 'was', 0),
(84, 41, '0', 0),
(85, 41, '-2', 1),
(86, 41, '2', 0),
(87, 41, '4', 0),
(88, 42, 'W naczyniu drugim było więcej wody niż w naczyniu trzecim', 1),
(89, 42, 'W pierwszym i drugim naczyniu łącznie było tyle samo wody, co w trzecim naczyniu.', 0),
(92, 47, '1.5', 0),
(93, 47, '0.75', 1),
(94, 47, '1', 0);

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
(3, 4, 12),
(4, 4, 1),
(5, 5, 12);

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
-- Indeksy dla tabeli `ogloszeniazad`
--
ALTER TABLE `ogloszeniazad`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `paczkap`
--
ALTER TABLE `paczkap`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `paczkazad`
--
ALTER TABLE `paczkazad`
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
-- Indeksy dla tabeli `wyniki`
--
ALTER TABLE `wyniki`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `zadania`
--
ALTER TABLE `zadania`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `zadaniaabc`
--
ALTER TABLE `zadaniaabc`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=301;

--
-- AUTO_INCREMENT dla tabeli `ogloszeniazad`
--
ALTER TABLE `ogloszeniazad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT dla tabeli `paczkap`
--
ALTER TABLE `paczkap`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT dla tabeli `paczkazad`
--
ALTER TABLE `paczkazad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT dla tabeli `powiadomienia`
--
ALTER TABLE `powiadomienia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=199;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT dla tabeli `wyniki`
--
ALTER TABLE `wyniki`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT dla tabeli `zadania`
--
ALTER TABLE `zadania`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT dla tabeli `zadaniaabc`
--
ALTER TABLE `zadaniaabc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT dla tabeli `zajecia`
--
ALTER TABLE `zajecia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
