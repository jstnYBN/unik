-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Хост: MySQL-8.4
-- Время создания: Ноя 10 2026 г., 03:10
-- Версия сервера: 8.4.8
-- Версия PHP: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `site`
--

-- --------------------------------------------------------

--
-- Структура таблицы `contacts`
--

CREATE TABLE `contacts` (
  `id` int NOT NULL,
  `label` varchar(50) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `contacts`
--

INSERT INTO `contacts` (`id`, `label`, `value`) VALUES
(3, 'Деканат', '+7 (999) 123-45-67'),
(4, 'Приёмная комиссия', 'priem@university.ru'),
(5, 'Создатель сайта', '+7 (917) 776-00-88'),
(7, 'Шарафутдинов А.Ф.', 'https://github.com/aseven77');

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE `news` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `created_at`) VALUES
(1, 'Открытие нового корпуса университета', 'Торжественное открытие нового учебного корпуса состоялось 15 сентября. В здании разместились современные лаборатории, лекционные аудитории и коворкинг для студентов.', '2026-11-09 21:49:30'),
(2, 'Студенты победили на хакатоне по веб-разработке', 'Команда нашего факультета заняла первое место на региональном хакатоне. Ребята представили проект онлайн-платформы для обмена учебными материалами и получили грант на развитие.', '2026-11-09 21:49:30'),
(3, 'Обновлено расписание зимней сессии', 'В разделе «Учёба» опубликовано обновлённое расписание экзаменов на зимнюю сессию. Проверьте даты и аудитории заранее — возможны изменения по некоторым дисциплинам.', '2026-11-09 21:49:30'),
(4, 'Запущен новый студенческий медиацентр', 'На базе факультета начал работу медиацентр: студенты могут попробовать себя в роли журналистов, операторов и монтажёров. Первые материалы уже доступны на сайте университета.', '2026-11-09 21:49:30'),
(6, 'В библиотеке открылся доступ к электронным научным журналам', 'С этого семестра студенты и преподаватели получили бесплатный доступ к крупнейшим базам электронных научных журналов. Авторизация выполняется по студенческому билету через личный кабинет на сайте библиотеки.', '2026-11-09 22:03:42');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `news`
--
ALTER TABLE `news`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
