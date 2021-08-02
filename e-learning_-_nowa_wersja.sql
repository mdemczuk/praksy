-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 02 Sie 2021, 21:13
-- Wersja serwera: 10.4.20-MariaDB
-- Wersja PHP: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `e-learning - test`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `access`
--

CREATE TABLE `access` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `course_pswd` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `registration_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_polish_ci NOT NULL,
  `general_description` text CHARACTER SET utf8 NOT NULL,
  `matters` text CHARACTER SET utf8 NOT NULL,
  `for_whom` text CHARACTER SET utf8 NOT NULL,
  `results` text CHARACTER SET utf8 NOT NULL,
  `additional_info` text CHARACTER SET utf8 NOT NULL,
  `language` text CHARACTER SET utf8 NOT NULL,
  `content` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `courses`
--

INSERT INTO `courses` (`id`, `title`, `general_description`, `matters`, `for_whom`, `results`, `additional_info`, `language`, `content`) VALUES
(1, 'Wprowadzenie do analizy danych RNA-Seq', 'Ten kurs stanowi wprowadzenie do tematyki związanej z analizą danych RNA-Seq. Pozwala on na zapoznanie się z najważniejszymi krokami analizy, powszechnie stosowanymi potokami analitycznymi i możliwymi zastosowaniami. W trakcie kursu poruszone zostaną zagadnienia kontroli jakości danych, składania transkryptomu ab initio oraz de novo, szacowania ekspresji genów i analizy różnicowej.', '1. Krótkie wprowadznie do wysokoprzepustowego sekwencjonowania genomów i transkryptomów.\r\n2. Kontrola jakości danych.\r\n3. Mapowanie odczytów, obliczanie poziomu ekspresji genów i tranksryptów.\r\n4. Analiza ekspresji różnicowej wraz z tworzeniem wykresów diagnostycznych.\r\n5. Składanie transkryptomu de novo (niezależne od genomu).\r\n6. Składanie transkryptomu ab initio (z mapowaniem odczytów do genomu).\r\n7. Adnotacja transkryptomów', 'Ten wprowadzający kurs skierowany jest do każdego, kto chce się dowiedzieć jak radzić sobie z zestawami wielkoskalowych danych RNA-Seq i poszerzać wiedzę o istotnych biologicznych aspektach tego zagadnienia. Kurs jest przygotowany pod kątem osób prowadzących badania w dziedzinach biotechnologii, biologii molekularnej, bioinformatyki i innych nauk biologicznych.', 'Po ukończeniu kursu uczestnicy powinni posiadać wiedzę o źródłach danych RNA-Seq, formatach danych, być w stanie wykonać analizę jakości tych danych, mapowanie do sekwencji referencyjnej, ocenę wartości ekspresji genów/transkryptów i wykonać analizę ekspresji różnicowej włącznie z interpretacją otrzymanych wyników. Uczestnicy posiądą również wiedzę o metodach składania transkryptomów i będą w stanie wykorzystać poznane potoki analityczne we własnych projektach. Duża uwaga zwrócona będzie na wizualizację danych, tworzenie wykresów, interpretację wyników i radzenie sobie z często spotykanymi problemami podczas analizy.', 'Kurs odbędzie się w terminie wskazanym po lewej stronie przycisku \"Zarejestruj się\" w formie webinarium. Wymagane jest posiadanie komputera z systemem Windows, Linux lub Mac OS posiadającego dostęp do internetu. Organizator zastrzega sobie prawo do odwołania kursu nie później niż na dwa tygodnie przed datą jego rozpoczęcia w przypadku mniej niż 5 uczestników.', 'polski', ''),
(2, 'Analiza interakcji DNA-białko', 'Ten kurs stanowi wprowadzenie do tematyki związanej z analizą danych ChIP-seq oraz ChIPexo, czyli wysokoprzepustowymi metodami badania interakcji białek z DNA. Kurs pozwala na zapoznanie się z najważniejszymi krokami analizy, powszechnie stosowanymi potokami analitycznymi i możliwymi zastosowaniami.', '1. Wprowadzenie do systemu Linux.\r\n2. Bazy danych.\r\n3. Detekcja miejsc występowania wiązań Białko-DNA z wykorzystaniem ChIP-Seq.\r\n4. Wyszukiwanie motywów sekwencji w miejscach wiązania białek z DNA.\r\n5. Wizualizacja danych (IGV).\r\n6. Analiza stanu chromatyny: modyfikacje histonów.\r\n7. Zwiększenie dokładności detekcji miejsc występowania wiązań Białko-DNA przy pomocy techniki ChIP-exo.\r\n8. Projekt - efektywna praca z plikami w formatach powszechnie używanych w pracy z danymi ChIP-Seq.', 'Ten wprowadzający kurs skierowany jest do każdego, kto chce się dowiedzieć, jak analizować dane ChIP-Seq pod kątem konkretnych pytań biologicznych. Kurs jest dostosowany do osób prowadzących badania w dziedzinach biotechnologii, biologii molekularnej, bioinformatyki i innych nauk biologicznych.', 'Po ukończeniu kursu uczestnicy powinni posiadać wiedzę o podstawowych źródłach danych ChIP-Seq oraz ChIP-exo i formatach danych wykorzystywanych powszechnie w trakcie analiz. Uczestnicy powinni umieć samodzielnie wykonać analizę polegającą na wykrywaniu miejsc interakcji DNA i białek, w tym czynników transkrypcyjnych i histonów, a także potrafić wyszukiwać motywy charakterystyczne dla tychże stref wiązania. Zwrócimy szczególną uwagę na wizualizację danych w przeglądarce genomowej, interpretację wyników i radzenie sobie z problemami często spotykanymi podczas analizy tego rodzaju danych.', 'Kurs odbędzie się w terminie wskazanym po lewej stronie przycisku \"Zarejstruj się\" w formie webinarium. Wymagane jest posiadanie komputera z systemem Windows, Linux lub Mac OS posiadającego dostęp do internetu. Organizator zastrzega sobie prawo do odwołania kursu nie później niż na dwa tygodnie przed datą jego rozpoczęcia w przypadku mniej niż 5 uczestników.', 'polski', '');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first name` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `last name` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `phone` int(9) NOT NULL,
  `email` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `first name`, `last name`, `phone`, `email`) VALUES
(1, 'admin', '', 0, 'admin'),
(2, 'Anna', 'Nowak', 999999999, 'anna@wp.pl'),
(3, 'Maria', 'Zawada', 0, 'maria@wp.pl');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `access`
--
ALTER TABLE `access`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `access`
--
ALTER TABLE `access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `access`
--
ALTER TABLE `access`
  ADD CONSTRAINT `access_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `access_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
