-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 04 2019 г., 10:15
-- Версия сервера: 10.3.13-MariaDB
-- Версия PHP: 7.1.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `tz1`
--

-- --------------------------------------------------------

--
-- Структура таблицы `shortlinks`
--

CREATE TABLE `shortlinks` (
  `id` int(11) NOT NULL,
  `longurl` text NOT NULL,
  `shorturl` varchar(10) NOT NULL,
  `cashe` varchar(255) NOT NULL,
  `date_create` date NOT NULL,
  `counters` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `shortlinks`
--

INSERT INTO `shortlinks` (`id`, `longurl`, `shorturl`, `cashe`, `date_create`, `counters`) VALUES
(45, 'https%3A%2F%2Fgeekbrains.ru%2Fprofessions%2Fqa_engineer', 'zTb7N', '64eac9c7b63fdb4dc62d457af565f9e826dcda51a8c5d9da04ac61699fe5cce9', '2019-10-04', 2),
(46, 'https%3A%2F%2Fwww.forbes.ru%2Fmilliardery%2F383439-kak-boty-sdelali-rumynskogo-programmista-milliarderom%3Futm_referrer%3Dhttps%253a%252f%252fzen.yandex.com', 'zVY2c', '01409fb8e4e91f6a3d48b06f7d33b1439e17116ca0430e17c286bc5e3b3a3018', '2019-10-04', 1),
(47, 'https%3A%2F%2Fyandex.ru%2F', 'zFpr5', 'bb8fd05563271de3db0e7703383834873761d3bcb31f86fb762a3ecbe10cd467', '2019-10-04', 0),
(48, 'https%3A%2F%2Fchelyabinsk.hh.ru%2F', 'zcK31', 'ee742cfc392fa453d5a259776e28eaf18b3e8e3dae0b0d8629549606d3d67373', '2019-10-04', 0),
(49, 'https%3A%2F%2Fgeekbrains.ru%2Feducation', 'zNa00', '845ab81b9d00ef7c305472384b34338a27407e4ef96bd7671118c12b3c866a8b', '2019-10-04', 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `shortlinks`
--
ALTER TABLE `shortlinks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shorturl` (`shorturl`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `shortlinks`
--
ALTER TABLE `shortlinks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
