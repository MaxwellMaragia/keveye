-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 21, 2019 at 10:19 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `keveye`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `article` varchar(100) NOT NULL,
  `club` varchar(40) NOT NULL,
  `time_date` varchar(10) NOT NULL,
  `author` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `article`, `club`, `time_date`, `author`) VALUES
(1, 'National soccer Tournmaent', 'Over the years the soccer team has proven to be the best in the national games tournament and recent', 'Journalism', '23/06/2018', 'Brian Mcmillan'),
(2, 'Environment conservation', 'efjlwjefwl ewfnlwef wefnwef ewfnwe fewkfwe fnewfew fwefkewnfewk fwefkwenflew nslds sdlsd ', 'Wildlife Club', '26/08/2018', 'Dan Dan');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_name` varchar(60) NOT NULL,
  `book_id` int(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `id` int(100) NOT NULL,
  `category` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_name`, `book_id`, `state`, `id`, `category`) VALUES
('Mustahiki Meya', 50, 'unavailable', 1, 'Set book'),
('Mc Millan dictionary', 54, 'available', 2, 'Dictionary');

-- --------------------------------------------------------

--
-- Table structure for table `book_lend_list`
--

CREATE TABLE `book_lend_list` (
  `student_names` varchar(60) NOT NULL,
  `admission` int(40) NOT NULL,
  `book_name` varchar(60) NOT NULL,
  `book_id` int(30) NOT NULL,
  `date_assigned` varchar(30) NOT NULL,
  `return_date` varchar(30) NOT NULL,
  `id` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `book_lend_list`
--

INSERT INTO `book_lend_list` (`student_names`, `admission`, `book_name`, `book_id`, `date_assigned`, `return_date`, `id`) VALUES
('JECTON OMONDI', 168, 'Mustahiki Meya', 50, '2018-12-11 04:57:52', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `book_logs`
--

CREATE TABLE `book_logs` (
  `student_name` varchar(50) NOT NULL,
  `student_id` int(50) NOT NULL,
  `book_title` varchar(60) NOT NULL,
  `book_serial` int(30) NOT NULL,
  `librarian` varchar(30) NOT NULL,
  `action` varchar(30) NOT NULL,
  `date` varchar(30) NOT NULL,
  `id` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `book_logs`
--

INSERT INTO `book_logs` (`student_name`, `student_id`, `book_title`, `book_serial`, `librarian`, `action`, `date`, `id`) VALUES
('JECTON OMONDI', 168, 'Mustahiki Meya', 50, 'Charles Muhinga', 'book issue', '2018-12-11 04:57:52', 1);

-- --------------------------------------------------------

--
-- Table structure for table `carousel_pics`
--

CREATE TABLE `carousel_pics` (
  `id` int(11) NOT NULL,
  `image` varchar(150) NOT NULL,
  `short_message` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `carousel_pics`
--

INSERT INTO `carousel_pics` (`id`, `image`, `short_message`) VALUES
(8, 'student.jpg', 'students'),
(9, 'student1.jpg', 'students');

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `form` int(30) NOT NULL,
  `stream` varchar(30) NOT NULL,
  `initials` varchar(30) NOT NULL,
  `id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`form`, `stream`, `initials`, `id`) VALUES
(2, 'N', '2N', 8),
(3, 'N', '3N', 9),
(3, 'S', '3S', 10),
(4, 'N', '4N', 11),
(1, 'N', '1N', 12);

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `type` varchar(40) NOT NULL,
  `description` varchar(250) NOT NULL,
  `recipient` varchar(30) NOT NULL,
  `amount` varchar(40) NOT NULL,
  `date` varchar(40) NOT NULL,
  `id` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`type`, `description`, `recipient`, `amount`, `date`, `id`) VALUES
('Service', 'payment\r\n                                ', 'Douglas Mushemi', '12000', '11-11-18 17:19:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `featured_news`
--

CREATE TABLE `featured_news` (
  `id` int(10) NOT NULL,
  `title` varchar(50) NOT NULL,
  `time_date` varchar(10) NOT NULL,
  `details` varchar(255) NOT NULL,
  `image` varchar(100) NOT NULL DEFAULT 'picha/Machakos-Boys-Logo.fw_.png'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `featured_news`
--

INSERT INTO `featured_news` (`id`, `title`, `time_date`, `details`, `image`) VALUES
(1, 'Principal''s brief', '23/06/2018', 'jewfnlkfbne/lkjfnew/lskgnerkj.aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaabg.erkherjkbg jvk khbfwekjfbwkefbwekfjbwelfkjbwefkbf', 'Machakos-Boys-Logo.fw_.png');

-- --------------------------------------------------------

--
-- Table structure for table `featured_student`
--

CREATE TABLE `featured_student` (
  `id` int(10) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT 'picha/Machakos-Boys-Logo.fw_.png',
  `name` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `details` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `featured_student`
--

INSERT INTO `featured_student` (`id`, `image`, `name`, `title`, `details`) VALUES
(1, 'picha/Machakos-Boys-Logo.fw_.png', 'Malvin Okeyo', 'cleanest student', 'this is the cleanest student ');

-- --------------------------------------------------------

--
-- Table structure for table `fee_logs`
--

CREATE TABLE `fee_logs` (
  `student_id` int(30) NOT NULL,
  `student_name` varchar(30) NOT NULL,
  `bursar_name` varchar(30) NOT NULL,
  `fee_paid` int(30) NOT NULL,
  `term` varchar(30) NOT NULL,
  `fee_set` int(10) NOT NULL,
  `receipt` varchar(30) NOT NULL,
  `date` varchar(30) NOT NULL,
  `id` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fee_logs`
--

INSERT INTO `fee_logs` (`student_id`, `student_name`, `bursar_name`, `fee_paid`, `term`, `fee_set`, `receipt`, `date`, `id`) VALUES
(154, 'WALTER O.KASURE', 'Margaret Wambui', 45000, '2019 term 2', 32000, '12as3edxc', '2018-11-11 17:20:11', 1),
(156, 'HOSCAR ODIWUOR W.', 'Margaret Wambui', 37000, '2019 term 2', 32000, 'cash', '2018-11-14 10:32:46', 2),
(168, 'JECTON OMONDI', 'Margaret Wambui', 18000, '2019 term 2', 13000, 'cash', '2018-12-04 21:28:37', 3),
(168, 'JECTON OMONDI', 'Margaret Wambui', -1200, '2019 term 2', 13000, 'ca', '2018-12-04 21:29:13', 4),
(168, 'JECTON OMONDI', 'Margaret Wambui', 1000, '2019 term 2', 13000, '12', '2018-12-04 21:30:05', 5),
(274, 'DICKSON OMONDI OURU', 'Margaret Wambui', 17800, '2019 term 2', 13000, '1234321', '2018-12-05 12:52:54', 6),
(274, 'DICKSON OMONDI OURU', 'Margaret Wambui', -10000, '2019 term 2', 13000, 'll', '2018-12-05 12:53:11', 7),
(168, 'JECTON OMONDI', 'Margaret Wambui', 5000, '2019 term 2', 13000, 'cash', '2018-12-22 10:29:35', 8),
(124, 'JANE  LEONORA ADHIAMBO', 'Margaret Wambui', 20000, '2019 term 3', 15000, '1234326778', '2018-12-24 07:27:20', 9);

-- --------------------------------------------------------

--
-- Table structure for table `fee_structure`
--

CREATE TABLE `fee_structure` (
  `form` int(1) NOT NULL,
  `term` varchar(30) NOT NULL,
  `category` varchar(20) NOT NULL,
  `amount` int(30) NOT NULL,
  `amount_paid` int(40) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fee_structure`
--

INSERT INTO `fee_structure` (`form`, `term`, `category`, `amount`, `amount_paid`, `id`) VALUES
(3, '2019 term 2', 'day', 32000, 102000, 1),
(1, '2019 term 2', 'day', 13000, 30600, 2),
(1, '2019 term 2', 'boarding', 45000, 0, 3),
(3, '2019 term 3', 'day', 15000, 20000, 4);

-- --------------------------------------------------------

--
-- Table structure for table `final_result`
--

CREATE TABLE `final_result` (
  `names` varchar(30) NOT NULL,
  `admission` varchar(50) NOT NULL,
  `class` varchar(30) NOT NULL,
  `form` int(1) NOT NULL,
  `period` varchar(30) NOT NULL,
  `Computer` varchar(4) NOT NULL,
  `French` varchar(4) NOT NULL,
  `Business` varchar(4) NOT NULL,
  `Agriculture` varchar(4) NOT NULL,
  `CRE` varchar(4) NOT NULL,
  `History` varchar(4) NOT NULL,
  `Geography` varchar(4) NOT NULL,
  `Biology` varchar(4) NOT NULL,
  `Physics` varchar(4) NOT NULL,
  `Chemistry` varchar(4) NOT NULL,
  `Kiswahili` varchar(4) NOT NULL,
  `English` varchar(4) NOT NULL,
  `Mathematics` varchar(4) NOT NULL,
  `total` double NOT NULL,
  `cumulative` float NOT NULL,
  `count` int(3) NOT NULL,
  `average` float NOT NULL,
  `grade` varchar(2) NOT NULL,
  `points` int(3) NOT NULL,
  `remarks` varchar(30) NOT NULL,
  `term` varchar(2) NOT NULL,
  `id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `final_result`
--

INSERT INTO `final_result` (`names`, `admission`, `class`, `form`, `period`, `Computer`, `French`, `Business`, `Agriculture`, `CRE`, `History`, `Geography`, `Biology`, `Physics`, `Chemistry`, `Kiswahili`, `English`, `Mathematics`, `total`, `cumulative`, `count`, `average`, `grade`, `points`, `remarks`, `term`, `id`) VALUES
('JECTON OMONDI', '168', '3N', 3, '2018 term 1', '', '', '', '', '', '', '', '', '', '', '', '', '57.5', 57.5, 57.5, 1, 7.1875, 'E', 1, 'IMPROVE', '1', 81),
('DICKSON OMONDI OURU', '274', '3N', 3, '2018 term 1', '', '', '', '', '', '', '', '', '', '', '', '', '38', 38, 38, 1, 4.75, 'E', 1, 'IMPROVE', '1', 82),
('FILEX OCHIENG', '313', '3N', 3, '2018 term 1', '', '', '', '', '', '', '', '', '', '', '', '', '68.5', 68.5, 68.5, 1, 8.5625, 'E', 1, 'IMPROVE', '1', 83),
('EVALINE AKINYI OMONDI', '314', '3N', 3, '2018 term 1', '', '', '', '', '', '', '', '', '', '', '', '', '84.5', 84.5, 84.5, 1, 10.5625, 'E', 1, 'IMPROVE', '1', 84),
('ZEDEKIAH  OJORE', '316', '3N', 3, '2018 term 1', '', '', '', '', '', '', '', '', '', '', '', '', '60', 60, 60, 1, 7.5, 'E', 1, 'IMPROVE', '1', 85),
('ROSE ACHIENG AOL', '318', '3N', 3, '2018 term 1', '', '', '', '', '', '', '', '', '', '', '', '', '80', 80, 80, 1, 10, 'E', 1, 'IMPROVE', '1', 86);

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(10) NOT NULL,
  `image` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `image`, `category`) VALUES
(10, 'codei web systems schools general KELLY.jpg', 'visiting'),
(11, 'student.jpg', 'visiting'),
(12, 'student1.jpg', 'visiting');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_category`
--

CREATE TABLE `gallery_category` (
  `id` int(50) NOT NULL,
  `category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gallery_category`
--

INSERT INTO `gallery_category` (`id`, `category`) VALUES
(1, 'drama'),
(2, 'games');

-- --------------------------------------------------------

--
-- Table structure for table `grading_system`
--

CREATE TABLE `grading_system` (
  `lower_limit` int(3) NOT NULL,
  `upper_limit` int(3) NOT NULL,
  `grade` varchar(2) NOT NULL,
  `points` int(4) NOT NULL,
  `remarks` varchar(30) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `grading_system`
--

INSERT INTO `grading_system` (`lower_limit`, `upper_limit`, `grade`, `points`, `remarks`, `id`) VALUES
(0, 29, 'E', 1, 'IMPROVE', 25),
(30, 34, 'D-', 2, 'WORK HARDER', 26),
(35, 39, 'D', 3, 'WORK HARDER', 27),
(40, 44, 'D+', 4, 'WORK HARDER', 28),
(45, 49, 'C-', 5, 'GOOD', 29),
(50, 54, 'C', 6, 'GOOD', 30),
(55, 59, 'C+', 7, 'GOOD', 31),
(60, 64, 'B-', 8, 'V.GOOD', 32),
(65, 69, 'B', 9, 'V.GOOD', 33),
(70, 74, 'B+', 10, 'EXCELLENT', 34),
(75, 79, 'A-', 11, 'EXCELLENT', 35),
(80, 100, 'A', 12, 'EXCELLENT', 36);

-- --------------------------------------------------------

--
-- Table structure for table `graduates`
--

CREATE TABLE `graduates` (
  `names` varchar(50) NOT NULL,
  `admission` int(30) NOT NULL,
  `fees` int(6) NOT NULL,
  `id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `graduates`
--

INSERT INTO `graduates` (`names`, `admission`, `fees`, `id`) VALUES
('NANCY ATIENO MOI', 133, 36800, 1),
('ROSELINE AKINYI', 134, 36800, 2),
('WINNIE ATIENO OUMA', 136, 36800, 3),
('KASURE JUNE ODOYO', 138, 36800, 4),
('EUNICE ACHIENG ODONGO', 139, 36800, 5),
('MARK  OTIENO    OTUKA ', 143, 36800, 6),
('SYLVESTER OTIENO', 144, 36800, 7),
('CALVIN ONYANGO', 145, 36800, 8),
('RAPHAEL RAMOGI', 147, 36800, 9),
('CHRISTINE  .E  AWUOR O.', 148, 36800, 10),
('JOYCE AKINYI ASEMBO', 149, 36800, 11),
('EVANCE ODONGO WERE', 151, 36800, 12),
('MICHAEL OTIENO', 153, 36800, 13),
('WALTER O.KASURE', 154, -8200, 14),
('AUMA NANCY OMOLLO', 155, 36800, 15),
('HOSCAR ODIWUOR W.', 156, -200, 16),
('STALLON ONYANGO', 157, 36800, 17),
('BRANDON OKOTH O.', 158, 36800, 18),
('WICKLIFE OCHIENG O.', 159, 36800, 19),
('ONYANGO BRADLEY OKUMU', 160, 36800, 20),
('OUMA ROSE ATIENO', 161, 36800, 21),
('ONYANGO EZEKIEL O.', 162, 36800, 22),
('ELIZABETH ANYANGO O.', 163, 36800, 23),
('MILKA VALLEN ADHIAMBO', 164, 36800, 24),
('PETER ONYANGO', 165, 36800, 25),
('OKETCH ELIZABETH ATIENO', 166, 36800, 26),
('SYLVANUS MARWARA', 170, 36800, 27),
('VICTOR OMONDI O.', 171, 36800, 28),
('PETER OTIENO', 172, 36800, 29),
('OKUOGO FELIX OMONDI', 173, 36800, 30),
('AKINYI LYDIA MADARA', 174, 36800, 31),
('GODFREY BILLY OTIENO', 175, 36800, 32),
('OUMA COLLINCE O.', 176, 36800, 33),
('ODUOR EUNICE AKOTH', 179, 36800, 34),
('MIRIAM ATIENO', 180, 36800, 35),
('STEPHEN OTIENO OMONDI', 181, 36800, 36),
('VIVIAN AKINYI OMONDI', 184, 36800, 37),
('GEOFREY OTIENO RIZIKI', 185, 36800, 38),
('DENNIS ONYANGO O.', 186, 36800, 39),
('MARYANNE AKINYI', 190, 36800, 40),
('BREMIER OMONDI', 191, 36800, 41),
('SELLINE ACHIENG', 195, 36800, 42),
('LOICE ATIENO', 196, 36800, 43),
('PATRICK OCHIENG', 202, 36800, 44),
('STEVEN J. NYADENDA', 204, 36800, 45),
('MASELINE ATIENO', 208, 36800, 46),
('BRENDA ATIENO', 217, 36800, 47),
('STEPHEN ODONGO', 224, 36800, 48),
('MILLICENT ATIENO', 227, 36800, 49),
('ERUSTUS J. OTIENO', 229, 36800, 50),
('FED OTIENO OPODA', 287, 36800, 51),
('VINCENT O. OBILO', 295, 36800, 52),
('JOHN ODIERO JUMA', 299, 36800, 53),
('JOSEPH OCHIENG OBURA', 302, 36800, 54),
('BRIAN OCHIENG', 304, 36800, 55),
('CATHERINE  ADHIAMBO', 309, 36800, 56),
('ODEGI  O  DALMUS', 310, 36800, 57),
('ESTHER CLARIS', 312, 36800, 58),
('VERONICA OCHIENG', 378, 36800, 59),
('BRIAN  OGUDA', 384, 36800, 60),
('EMMANUEL OMONDI', 400, 36800, 61),
('CAROLYNE AKINYI', 404, 36800, 62),
('CALVINCE  OCHIENG', 405, 36800, 63),
('DELVINCE WARIOBA', 407, 36800, 64),
('EUNICE  ADHIAMBO', 409, 36800, 65),
('ROMNEY LAWRENCE ODENYO', 429, 36800, 66),
('LINET ADHIAMBO OKELLO', 432, 36800, 67);

-- --------------------------------------------------------

--
-- Table structure for table `income_sources`
--

CREATE TABLE `income_sources` (
  `name` varchar(30) NOT NULL,
  `amount` varchar(30) NOT NULL,
  `description` varchar(250) NOT NULL,
  `party` varchar(50) NOT NULL,
  `date` varchar(40) NOT NULL,
  `id` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `income_sources`
--

INSERT INTO `income_sources` (`name`, `amount`, `description`, `party`, `date`, `id`) VALUES
('Donation', '100000', 'Donation for buying books\r\n                                ', 'Government', '14-11-18 10:33', 1);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `name`, `contact`, `subject`, `message`) VALUES
(1, 'malvo', '0712599273', 'exams', 'when are the exams?'),
(11, 'malvo', '712898989', 'teo', 'I want teo\r'),
(12, 'malvo', '712898990', 'teo', 'I want teo\r'),
(13, 'malvo', '712898991', 'teo', 'I want teo\r'),
(14, 'malvo', '712898992', 'teo', 'I want teo\r'),
(15, 'malvo', '712898993', 'teo', '\r'),
(16, 'malvo', '712898994', 'teo', 'I want teo\r'),
(17, 'malvo', '712898995', 'teo', '\r'),
(18, 'malvo', '', 'teo', 'I want teo\r'),
(19, 'malvo', '712898997', 'teo', 'I want teo\r'),
(20, 'malvo', '712898998', 'teo', 'I want teo\r'),
(21, 'a', 'a', 'a', 'a'),
(22, 'a', 'a', 'a', 'a'),
(23, 'rR', 'r', 'r', 'r'),
(24, 'alvo', 'kibeboscar@gmail.com', 'result', 'qwqwq');

-- --------------------------------------------------------

--
-- Table structure for table `minimum_subjects`
--

CREATE TABLE `minimum_subjects` (
  `form` int(11) NOT NULL,
  `minimum` varchar(11) NOT NULL,
  `id` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `minimum_subjects`
--

INSERT INTO `minimum_subjects` (`form`, `minimum`, `id`) VALUES
(1, '11', 2),
(4, '8', 3),
(2, '11', 4),
(3, '8', 5);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `module` varchar(60) NOT NULL,
  `state` varchar(30) NOT NULL,
  `id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`module`, `state`, `id`) VALUES
('add results', 'unlocked', 1),
('clear results', 'unlocked', 4),
('set term fees', 'unlocked', 5),
('pay student fees', 'unlocked', 8),
('add books', 'unlocked', 9),
('issue books', 'unlocked', 11),
('grading system', 'locked', 12),
('clear graduates', 'unlocked', 13);

-- --------------------------------------------------------

--
-- Table structure for table `news_events`
--

CREATE TABLE `news_events` (
  `id` int(100) NOT NULL,
  `title` varchar(50) NOT NULL,
  `time_date` varchar(10) NOT NULL,
  `details` varchar(255) NOT NULL,
  `image` varchar(100) NOT NULL DEFAULT 'picha/Machakos-Boys-Logo.fw_.png'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `news_events`
--

INSERT INTO `news_events` (`id`, `title`, `time_date`, `details`, `image`) VALUES
(2, 'School games', '06/10/2018', 'this will  be held inthe school pitch where there will be a dual between the teachers and the studen', 'picha/Slide01.jpg'),
(3, 'AGM', '30/8/2018', 'All parents are required to attend', 'Machakos-Boys-Logo.fw_.png'),
(4, 'Zalego', '12/9/2017', 'This event wil be hosted by Zalego, a software company.\r\nIt will mostly benefit copm students', 'author.jpg'),
(5, 'HH', '4/9/2018', 'yoyoyoyuo', 'become.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` int(10) NOT NULL,
  `notice` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `notice`) VALUES
(1, 'PTA meeting on Monday ggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg'),
(2, 'Drama club fundraiser');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `admission` varchar(50) NOT NULL,
  `names` varchar(30) NOT NULL,
  `class` varchar(30) NOT NULL,
  `form` int(5) NOT NULL,
  `subject` varchar(30) NOT NULL,
  `cat` float NOT NULL,
  `mid` float NOT NULL,
  `total` float NOT NULL,
  `grade` varchar(3) NOT NULL,
  `points` int(30) NOT NULL,
  `remarks` varchar(30) NOT NULL,
  `initials` varchar(10) NOT NULL,
  `identifier` varchar(50) NOT NULL,
  `period` varchar(30) NOT NULL,
  `cat_entered` int(1) NOT NULL,
  `exam_entered` int(1) NOT NULL,
  `id` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`admission`, `names`, `class`, `form`, `subject`, `cat`, `mid`, `total`, `grade`, `points`, `remarks`, `initials`, `identifier`, `period`, `cat_entered`, `exam_entered`, `id`) VALUES
('168', 'JECTON OMONDI', '3N', 3, 'Mathematics', 27, 30.5, 57.5, 'C+', 7, 'GOOD', 'Maxi', '20181Mathematics168', '2018 term 1', 1, 1, 225),
('274', 'DICKSON OMONDI OURU', '3N', 3, 'Mathematics', 21.5, 16.5, 38, 'D', 3, 'WORK HARDER', 'Maxi', '20181Mathematics274', '2018 term 1', 1, 1, 226),
('313', 'FILEX OCHIENG', '3N', 3, 'Mathematics', 33.5, 35, 68.5, 'B', 9, 'V.GOOD', 'Maxi', '20181Mathematics313', '2018 term 1', 1, 1, 227),
('314', 'EVALINE AKINYI OMONDI', '3N', 3, 'Mathematics', 43.5, 41, 84.5, 'A', 12, 'EXCELLENT', 'Maxi', '20181Mathematics314', '2018 term 1', 1, 1, 228),
('316', 'ZEDEKIAH  OJORE', '3N', 3, 'Mathematics', 27, 33, 60, 'B-', 8, 'V.GOOD', 'Maxi', '20181Mathematics316', '2018 term 1', 1, 1, 229),
('318', 'ROSE ACHIENG AOL', '3N', 3, 'Mathematics', 45, 35, 80, 'A', 12, 'EXCELLENT', 'Maxi', '20181Mathematics318', '2018 term 1', 1, 1, 230);

-- --------------------------------------------------------

--
-- Table structure for table `school_fees`
--

CREATE TABLE `school_fees` (
  `term` varchar(30) NOT NULL,
  `total_paid` varchar(30) NOT NULL,
  `date_initiated` varchar(30) NOT NULL,
  `id` int(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `staff_accounts`
--

CREATE TABLE `staff_accounts` (
  `names` varchar(30) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(50) NOT NULL,
  `resetpassword` varchar(250) NOT NULL,
  `account` varchar(30) NOT NULL,
  `state` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `subject1` varchar(30) NOT NULL,
  `subject2` varchar(30) NOT NULL,
  `last_login` varchar(30) NOT NULL,
  `id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staff_accounts`
--

INSERT INTO `staff_accounts` (`names`, `username`, `password`, `resetpassword`, `account`, `state`, `email`, `subject1`, `subject2`, `last_login`, `id`) VALUES
('Codei ', 'keveye', '68ab3a20b51cf480cf800e544b67761e', '8532b6f2157d77eae6d6f94365cec2cc', 'admin', 'active', 'maxmaragia@gmail.com', '', '', '2019-01-16 09:40:56', 1),
('Kevin Mandera', '1001', '68ab3a20b51cf480cf800e544b67761e', '8532b6f2157d77eae6d6f94365cec2cc', 'teacher', 'active', 'maxmaragia@gmail.com', 'English', 'French', '2018-11-20 08:17:56', 3),
('Margaret Wambui', '1000', '68ab3a20b51cf480cf800e544b67761e', '0', 'bursar', 'active', 'margiewambui@gmail.com', '', '', '2018-12-24 07:27:00', 6),
('Charles Muhinga', '2000', '68ab3a20b51cf480cf800e544b67761e', '0', 'librarian', 'active', 'kimathi@gmail.com', '', '', '2018-12-11 04:57:11', 8),
('Brian Omondi', '2001', '68ab3a20b51cf480cf800e544b67761e', '0', 'teacher', 'active', 'brianomondi13@gmail.com', 'Physics', 'Mathematics', '2019-01-20 11:51:59', 9),
('James', '4040', '68ab3a20b51cf480cf800e544b67761e', '', 'teacher', 'active', 'maxmaragia@gmail.com', 'English', 'CRE', '2018-12-22 08:58:49', 10);

-- --------------------------------------------------------

--
-- Table structure for table `streams`
--

CREATE TABLE `streams` (
  `stream_name` varchar(30) NOT NULL,
  `id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `streams`
--

INSERT INTO `streams` (`stream_name`, `id`) VALUES
('N', 3),
('S', 4);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `AdmissionNumber` varchar(30) NOT NULL,
  `names` varchar(60) NOT NULL,
  `gender` char(1) NOT NULL,
  `kcpe` int(3) NOT NULL,
  `password` varchar(60) NOT NULL,
  `class` varchar(30) NOT NULL,
  `stream` varchar(30) NOT NULL,
  `form` int(2) NOT NULL,
  `category` varchar(30) NOT NULL,
  `account` varchar(30) NOT NULL,
  `security_question` varchar(250) NOT NULL,
  `security_answer` varchar(250) NOT NULL,
  `fathersNames` varchar(30) NOT NULL,
  `mothersNames` varchar(30) NOT NULL,
  `fathersmobile` varchar(10) NOT NULL,
  `mothersmobile` varchar(10) NOT NULL,
  `fee_paid` int(20) NOT NULL,
  `fee_owed` int(20) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`AdmissionNumber`, `names`, `gender`, `kcpe`, `password`, `class`, `stream`, `form`, `category`, `account`, `security_question`, `security_answer`, `fathersNames`, `mothersNames`, `fathersmobile`, `mothersmobile`, `fee_paid`, `fee_owed`, `id`) VALUES
('168', 'JECTON OMONDI', 'M', 196, 'd41d8cd98f00b204e9800998ecf8427e', '3N', 'N', 3, 'day', 'active', ' what was your first pet', 'dog', '', '', '', '', 22800, 17800, 1),
('274', 'DICKSON OMONDI OURU', 'M', 237, '02cda78c2c7f52e7400d1fda3d94aeab', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 7800, 17800, 2),
('313', 'FILEX OCHIENG', 'M', 247, '775aded00b563f375e2c0dc5fa49e279', '3N', 'N', 3, 'day', 'active', 'What''s your favorite food?', 'Ugali beef', '', '', '', '', 0, 17800, 3),
('314', 'EVALINE AKINYI OMONDI', 'F', 196, '77284049c6cb046524eabcc59f741ea8', '3N', 'N', 3, 'day', 'active', 'What''s the name of your first pet?', 'maxi', '', '', '', '', 0, 17800, 4),
('316', 'ZEDEKIAH  OJORE', 'M', 192, '3fe94a002317b5f9259f82690aeea4cd', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 5),
('318', 'ROSE ACHIENG AOL', 'F', 196, '432aca3a1e345e339f35a30c8f65edce', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 6),
('320', 'ALFRED  COLLINS OTIENO', 'M', 200, '320722549d1751cf3f247855f937b982', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 7),
('323', 'ALPHONCE  OLUOCH', 'M', 305, 'bc6dc48b743dc5d013b1abaebd2faed2', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 8),
('324', 'MACKLINE ADHIAMBO', 'F', 240, 'f2fc990265c712c49d51a18a32b39f0c', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 9),
('326', 'VINCENT  OMONDI', 'M', 314, 'a666587afda6e89aec274a3657558a27', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 10),
('328', 'RONNY  OJWANG', 'M', 209, 'cd00692c3bfe59267d5ecfac5310286c', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 11),
('330', 'EDDY OMOLLO ACHIENG', 'M', 275, 'fe73f687e5bc5280214e0486b273a5f9', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 12),
('334', 'DENNIS ODIWUOR', 'M', 243, '2f2b265625d76a6704b08093c652fd79', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 13),
('338', 'PAUL BRIAN ONYANGO', 'M', 235, '819f46e52c25763a55cc642422644317', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 14),
('339', 'PAMELA  AWINO', 'F', 217, '04025959b191f8f9de3f924f0940515f', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 15),
('341', 'FREDRICK  ODHIAMBO', 'M', 293, '3dd48ab31d016ffcbf3314df2b3cb9ce', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 16),
('343', 'MICHAEL OKUTE', 'M', 272, '3ad7c2ebb96fcba7cda0cf54a2e802f5', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 17),
('344', 'JEMIMA  ACHIENG', 'F', 205, 'b3967a0e938dc2a6340e258630febd5a', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 18),
('346', 'CHRISTOPHER  ABOKE', 'M', 276, '13f9896df61279c928f19721878fac41', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 19),
('348', 'NEWTON  ODIWUOR', 'M', 169, '01386bd6d8e091c2ab4c7c7de644d37b', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 20),
('349', 'EUNICE  ATIENO', 'F', 207, '0bb4aec1710521c12ee76289d9440817', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 21),
('351', 'LYDIA  AKINYI  ONYANGO', 'F', 210, 'efe937780e95574250dabe07151bdc23', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 22),
('355', 'ANYANGO  LORINE', 'F', 263, '82cec96096d4281b7c95cd7e74623496', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 23),
('357', 'NERLY ANYANGO ODHIAMBO', 'F', 246, 'fb7b9ffa5462084c5f4e7e85a093e6d7', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 24),
('360', 'EDWIN  OTIENO  OLUM', 'M', 292, 'e7b24b112a44fdd9ee93bdf998c6ca0e', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 25),
('362', 'EMARLINE  ADHIAMBO', 'F', 169, 'c3e878e27f52e2a57ace4d9a76fd9acf', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 26),
('363', 'GEOFREY  OUMA', 'M', 207, '00411460f7c92d2124a67ea0f4cb5f85', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 27),
('365', 'OPIYO  MELVINE  ALUOCH', 'F', 228, '9be40cee5b0eee1462c82c6964087ff9', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 28),
('369', 'TIMNA  ZERA WASONGA', 'F', 286, '0c74b7f78409a4022a2c4c5a5ca3ee19', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 29),
('370', 'TONNY  ABALA  OMONDI', 'M', 235, 'd709f38ef758b5066ef31b18039b8ce5', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 30),
('372', 'OYOO  SILVESTER', 'M', 209, '24b16fede9a67c9251d3e7c7161c83ac', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 31),
('374', 'MERCELINE  ACHIENG', 'F', 256, 'ad972f10e0800b49d76fed33a21f6698', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 32),
('377', 'WYCLIFFE ODHIAMBO', 'M', 239, 'd34ab169b70c9dcd35e62896010cd9ff', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 33),
('380', 'ANDREW  GEORGE', 'M', 199, 'bca82e41ee7b0833588399b1fcd177c7', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 34),
('381', 'OTUMBA GRACE  AKOTH', 'F', 186, '00ec53c4682d36f5c4359f4ae7bd7ba1', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 35),
('383', 'IRINE  ACHIENG', 'F', 188, 'beed13602b9b0e6ecb5b568ff5058f07', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 36),
('386', 'PHILIP   ODHIAMBO', 'M', 228, '39461a19e9eddfb385ea76b26521ea48', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 37),
('389', 'CHARLES   OTIENO', 'M', 205, 'c86a7ee3d8ef0b551ed58e354a836f2b', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 38),
('391', 'LUCY  AWINO', 'F', 232, '5a4b25aaed25c2ee1b74de72dc03c14e', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 39),
('392', 'PAUL  O  ABURA', 'M', 158, 'f73b76ce8949fe29bf2a537cfa420e8f', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 40),
('394', 'GODFREY  OTIENO', 'M', 180, '28f0b864598a1291557bed248a998d4e', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 41),
('398', 'EVERLINE ANYANGO', 'F', 140, 'b7b16ecf8ca53723593894116071700c', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 42),
('399', 'DENIS  O  OCHIENG', 'M', 206, '352fe25daf686bdb4edca223c921acea', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 43),
('402', 'RUTH  AKINYI', 'F', 181, '69cb3ea317a32c4e6143e665fdb20b14', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 44),
('410', 'JOHN ODHIAMBO OUMA', 'M', 215, '1068c6e4c8051cfd4e9ea8072e3189e2', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 45),
('411', 'RACHAEL ADHIAMBO OUKO', 'F', 188, '17d63b1625c816c22647a73e1482372b', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 46),
('412', 'PHENNY  AUMA OGOLA', 'F', 169, 'b9228e0962a78b84f3d5d92f4faa000b', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 47),
('420', 'BENARD  OYIER JAOKO', 'M', 174, 'b6f0479ae87d244975439c6124592772', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 48),
('423', 'ELIJAH  OKOTH OREMBE', 'M', 213, 'faa9afea49ef2ff029a833cccc778fd0', '3N', 'N', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 49),
('315', 'PETER ORWATH MAWERE', 'M', 251, 'ad13a2a07ca4b7642959dc0c4c740ab6', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 50),
('317', 'JACKLINE AKOTH MAWERE ', 'F', 252, '5b8add2a5d98b1a652ea7fd72d942dac', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 51),
('319', 'DERRICK  OLUOCH', 'M', 275, '8d3bba7425e7c98c50f52ca1b52d3735', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 52),
('321', 'JOHN OOKO  OKELO', 'M', 223, 'caf1a3dfb505ffed0d024130f58c5cfa', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 53),
('322', 'NANCY  AUMA ', 'F', 190, '5737c6ec2e0716f3d8a7a5c4e0de0d9a', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 54),
('325', 'ERICK ODUOR', 'M', 268, '89f0fd5c927d466d6ec9a21b9ac34ffa', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 55),
('327', 'DAVID  OTIENO', 'M', 181, 'b83aac23b9528732c23cc7352950e880', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 56),
('329', 'CHRISPHINE OTIENO', 'M', 214, '6faa8040da20ef399b63a72d0e4ab575', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 57),
('331', 'VICTOR  ONYANGO', 'M', 207, '6da37dd3139aa4d9aa55b8d237ec5d4a', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 58),
('333', 'MERCY  ACHIENG', 'F', 242, '310dcbbf4cce62f762a2aaa148d556bd', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 59),
('336', 'EVANCE  OTIENO', 'M', 218, '6855456e2fe46a9d49d3d3af4f57443d', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 60),
('337', 'BRAYAN  OTIENO', 'M', 244, '357a6fdf7642bf815a88822c447d9dc4', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 61),
('340', 'GEOPHREY   OTIENO', 'M', 249, '40008b9a5380fcacce3976bf7c08af5b', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 62),
('342', 'MARSDEN OCHIENG', 'M', 230, '58238e9ae2dd305d79c2ebc8c1883422', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 63),
('345', 'KEVIN  ADERO', 'M', 193, 'd81f9c1be2e08964bf9f24b15f0e4900', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 64),
('347', 'OSCAR  ADEDE', 'M', 228, 'c5ff2543b53f4cc0ad3819a36752467b', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 65),
('350', 'ANNA  ANYANGO', 'F', 249, '9de6d14fff9806d4bcd1ef555be766cd', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 66),
('352', 'JOSEPHINE  AKINYI', 'F', 160, '371bce7dc83817b7893bcdeed13799b5', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 67),
('353', 'VENA AKINYI', 'F', 188, '138bb0696595b338afbab333c555292a', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 68),
('354', 'MAXMILLAH AKOTH', 'F', 230, '8dd48d6a2e2cad213179a3992c0be53c', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 69),
('356', 'TRASA PRUDENCE  OWUOR', 'F', 218, '6c524f9d5d7027454a783c841250ba71', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 70),
('358', 'VICTOR WESONGA', 'M', 255, 'aa942ab2bfa6ebda4840e7360ce6e7ef', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 71),
('359', 'JEDIDAH ADHIAMBO  OWITI', 'F', 209, 'c058f544c737782deacefa532d9add4c', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 72),
('361', 'AMOLLO BASIL ODHIAMBO', 'M', 229, '52720e003547c70561bf5e03b95aa99f', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 73),
('364', 'AMADI  VIVIAN AMADI', 'F', 211, 'bac9162b47c56fc8a4d2a519803d51b3', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 74),
('366', 'OMONDI DANIEL  OTIENO', 'M', 311, '5ef698cd9fe650923ea331c15af3b160', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 75),
('368', 'JOCINTER AKINYI ONYANGO', 'F', 222, 'cf004fdc76fa1a4f25f62e0eb5261ca3', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 76),
('371', 'STEPHEN ONYANGO', 'M', 244, '41f1f19176d383480afa65d325c06ed0', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 77),
('373', 'MONICA  OWINO', 'F', 207, 'ffd52f3c7e12435a724a8f30fddadd9c', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 78),
('375', 'BRIAN  OMONDI', 'M', 239, 'f61d6947467ccd3aa5af24db320235dd', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 79),
('376', 'LEILA  AUMA  OKOTH', 'F', 196, '142949df56ea8ae0be8b5306971900a4', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 80),
('379', 'BEN  OKELLO', 'M', 195, 'a02ffd91ece5e7efeb46db8f10a74059', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 81),
('382', 'MILLICENT  AKINYI', 'F', 211, '4f6ffe13a5d75b2d6a3923922b3922e5', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 82),
('385', 'GEOPHREY   OTIENO  OIGO', 'M', 185, 'dc912a253d1e9ba40e2c597ed2376640', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 83),
('387', 'CHRISTOPHER  OTIENO', 'M', 221, '8efb100a295c0c690931222ff4467bb8', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 84),
('388', 'RODI PURITY AKINYI', 'F', 165, 'd9fc5b73a8d78fad3d6dffe419384e70', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 85),
('390', 'MICHAEL  OKOTH', 'M', 177, 'a01a0380ca3c61428c26a231f0e49a09', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 86),
('393', 'FELIX  OCHUNG  WERE', 'M', 223, '70c639df5e30bdee440e4cdf599fec2b', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 87),
('395', 'BLEVINCE  ODHIAMBO', 'M', 172, '1543843a4723ed2ab08e18053ae6dc5b', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 88),
('397', 'ATIENO  JUDITH  PEREZ', 'F', 287, 'e46de7e1bcaaced9a54f1e9d0d2f800d', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 89),
('406', 'TABU KENNEDY OCHIENG', 'M', 222, '8cb22bdd0b7ba1ab13d742e22eed8da2', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 90),
('408', 'ELIJAH OLOO ', 'M', 198, '0d0fd7c6e093f7b804fa0150b875b868', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 91),
('413', 'ROSELINE  ATIENO', 'F', 168, '0deb1c54814305ca9ad266f53bc82511', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 92),
('415', 'LILIAN JUDITH ATIENO', 'F', 171, '42e7aaa88b48137a16a1acd04ed91125', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 93),
('416', 'SAMSON ONYANGO JAGOGO', 'M', 178, '8fe0093bb30d6f8c31474bd0764e6ac0', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 94),
('418', 'EDITH ANYANGO ONYANGO', 'F', 228, 'd1f255a373a3cef72e03aa9d980c7eca', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 95),
('419', 'ENOS  ODERO  OCHIENG', 'M', 143, '7eacb532570ff6858afd2723755ff790', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 96),
('422', 'MERCY  CHARITY JUMA', 'F', 155, 'f85454e8279be180185cac7d243c5eb3', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 97),
('425', 'PURITY ACHIENG ODEMBA', 'F', 194, '25b2822c2f5a3230abfadd476e8b04c9', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 98),
('428', 'ALOYCE OWUOR', 'F', 285, '8d7d8ee069cb0cbbf816bbb65d56947e', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 99),
('431', 'ODIDA OJWAKA GILLIAN', 'M', 233, '66368270ffd51418ec58bd793f2d9b1b', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 100),
('430', 'STEVEN OKELO OMONDI', 'M', 271, 'f74909ace68e51891440e4da0b65a70c', '3S', 'S', 3, 'day', 'active', '', '', '', '', '', '', 0, 17800, 101),
('124', 'JANE  LEONORA ADHIAMBO', 'F', 229, '68ab3a20b51cf480cf800e544b67761e', '4N', 'N', 4, 'day', 'active', 'Who is your favorite musician?', 'chronixx', '', '', '0707338839', '', 20000, 19800, 102),
('183', 'JACKLINE ADHIAMBO', 'F', 230, 'cedebb6e872f539bef8c3f919874e9d7', '4N', 'N', 4, 'day', 'active', '', '', '', '', '0712599273', '', 0, 19800, 103),
('206', 'LAVENDA ADHIAMBO', 'F', 236, '7eabe3a1649ffa2b3ff8c02ebfd5659f', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 104),
('207', 'JOHN JAOKO', 'M', 268, '69adc1e107f7f7d035d7baf04342e1ca', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 105),
('212', 'LAVENDA ATIENO ORWA', 'F', 234, '1534b76d325a8f591b52d302e7181331', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 106),
('213', 'GEOFREY ONYANGO', 'M', 307, '979d472a84804b9f647bc185a877a8b5', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 107),
('216', 'JOCINTER ATIENO', 'F', 234, '45fbc6d3e05ebd93369ce542e8f2322d', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 108),
('218', 'OMONDI GEOFREY', 'M', 220, 'e96ed478dab8595a7dbda4cbcbee168f', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 109),
('221', 'SELINE  ACHIENG ABALA', 'F', 273, '060ad92489947d410d897474079c1477', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 110),
('226', 'JAMES OMONDI', 'M', 231, '9cfdf10e8fc047a44b08ed031e1f0ed1', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 111),
('234', 'YVON AWINO', 'F', 212, '289dff07669d7a23de0ef88d2f7129e7', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 112),
('237', 'CONSILATER ACHIENG', 'F', 218, '539fd53b59e3bb12d203f45a912eeaf2', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 113),
('238', 'JOB OKOTH OTANA', 'M', 346, 'ac1dd209cbcc5e5d1c6e28598e8cbbe8', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 114),
('240', 'STEPHEN AWALA', 'M', 235, '335f5352088d7d9bf74191e006d8e24c', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 115),
('243', 'EVANS ONYANGO', 'M', 316, 'cb70ab375662576bd1ac5aaf16b3fca4', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 116),
('245', 'LILIAN AKINYI', 'F', 214, '0266e33d3f546cb5436a10798e657d97', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 117),
('247', 'APONDI LAVIN', 'F', 248, '3cec07e9ba5f5bb252d13f5f431e4bbb', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 118),
('249', 'LILIAN ATIENO', 'F', 204, '077e29b11be80ab57e1a2ecabb7da330', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 119),
('255', ' ACHIENG WILFRIDA', 'F', 175, 'fe131d7f5a6b38b23cc967316c13dae2', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 120),
('261', 'SAMUEL OTIENO', 'M', 234, 'b1a59b315fc9a3002ce38bbe070ec3f5', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 121),
('263', 'MARY QUINTER ATIENO', 'F', 250, '8c19f571e251e61cb8dd3612f26d5ecf', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 122),
('265', 'DUNATA AUMA', 'F', 245, 'e56954b4f6347e897f954495eab16a88', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 123),
('267', 'JUDITH AUMA', 'F', 230, 'eda80a3d5b344bc40f3bc04f65b7a357', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 124),
('268', 'CHRISTIANO WILLIAM', 'M', 248, '8f121ce07d74717e0b1f21d122e04521', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 125),
('271', 'PHESTO ONYANGO', 'M', 256, '7f100b7b36092fb9b06dfb4fac360931', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 126),
('273', 'JOSEPH ODHIAMBO', 'M', 317, '4734ba6f3de83d861c3176a6273cac6d', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 127),
('276', 'BRIAN OCHENG', 'M', 226, 'db8e1af0cb3aca1ae2d0018624204529', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 128),
('277', 'PEREZ OPONDO', 'F', 216, '20f07591c6fcb220ffe637cda29bb3f6', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 129),
('279', 'JOHN MARK OKOTH', 'M', 281, 'd395771085aab05244a4fb8fd91bf4ee', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 130),
('282', 'JECTONE ODIWUOR', 'M', 207, '6a9aeddfc689c1d0e3b9ccc3ab651bc5', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 131),
('284', 'WELLINGTONE ODIWUOR', 'M', 219, '46ba9f2a6976570b0353203ec4474217', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 132),
('286', 'FREDRICK WASONGA', 'M', 249, '16a5cdae362b8d27a1d8f8c7b78b4330', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 133),
('288', 'LYDIA ATIENO', 'F', 285, '48aedb8880cab8c45637abc7493ecddd', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 134),
('291', 'JACKIM  ODUOR', 'M', 245, '9c838d2e45b2ad1094d42f4ef36764f6', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 135),
('293', 'APOLO OWAK OBIERO', 'M', 165, '53c3bce66e43be4f209556518c2fcb54', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 136),
('311', 'VIVIAN ADHIAMBO OMONDI', 'F', 303, '9dfcd5e558dfa04aaf37f137a1d9d3e5', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 137),
('414', 'SHELDON OKETCH JUMA', 'M', 307, '66808e327dc79d135ba18e051673d906', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 138),
('424', 'EUNICE MACRINE O', 'F', 258, '3c7781a36bcd6cf08c11a970fbe0e2a6', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 139),
('426', 'SHALTON ODHIAMBO', 'M', 280, '6ecbdd6ec859d284dc13885a37ce8d81', '4N', 'N', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 140),
('209', 'VIOLET ACHIENG WANGA', 'F', 253, 'b1d10e7bafa4421218a51b1e1f1b0ba2', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 141),
('211', 'SAMUEL GOGO ODUNDO', 'M', 242, 'eb163727917cbba1eea208541a643e74', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 142),
('214', 'ANDREW OUMA', 'M', 288, 'ca46c1b9512a7a8315fa3c5a946e8265', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 143),
('215', 'SYPROSE ATIENO ', 'F', 273, '3b8a614226a953a8cd9526fca6fe9ba5', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 144),
('219', 'GEORGE OTIENO', 'M', 217, 'c0e190d8267e36708f955d7ab048990d', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 145),
('220', 'BRENDA ATIENO', 'F', 246, 'ec8ce6abb3e952a85b8551ba726a1227', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 146),
('222', 'VIVIAN ADHIAMBO OTIENO', 'F', 232, 'bcbe3365e6ac95ea2c0343a2395834dd', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 147),
('225', 'JARED OMONDI', 'M', 184, 'd1c38a09acc34845c6be3a127a5aacaf', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 148),
('228', 'COLLINS OMONDI AKAL', 'M', 210, '74db120f0a8e5646ef5a30154e9f6deb', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 149),
('233', 'JULIET AUMA', 'F', 246, 'e165421110ba03099a1c0393373c5b43', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 150),
('235', 'TERESA ATIENO ODIERO', 'F', 301, '577ef1154f3240ad5b9b413aa7346a1e', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 151),
('236', 'COLLINS OMONDI ONAGO', 'M', 323, '01161aaa0b6d1345dd8fe4e481144d84', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 152),
('239', 'EMMANUEL OTIENO ODAGO', 'M', 267, '555d6702c950ecb729a966504af0a635', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 153),
('242', 'FREDRICK ODHIAMBO', 'M', 271, 'e4a6222cdb5b34375400904f03d8e6a5', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 154),
('244', 'BRENDA AUMA', 'F', 208, '9188905e74c28e489b44e954ec0b9bca', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 155),
('246', 'MARY ATIENO PAULINE', 'F', 273, '38db3aed920cf82ab059bfccbd02be6a', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 156),
('253', 'RODGERS ONYANGO RABET', 'M', 252, 'c24cd76e1ce41366a4bbe8a49b02a028', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 157),
('254', 'MONICA ATIENO ', 'F', 238, 'c52f1bd66cc19d05628bd8bf27af3ad6', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 158),
('258', 'MARGRET ATIENO', 'F', 228, '502e4a16930e414107ee22b6198c578f', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 159),
('260', 'WASHINGTONE OMONDI', 'M', 232, 'a4f23670e1833f3fdb077ca70bbd5d66', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 160),
('262', 'JUVENUS OKUMA', 'M', 243, '36660e59856b4de58a219bcf4e27eba3', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 161),
('264', 'IRENE ATIENO ', 'F', 192, 'd6baf65e0b240ce177cf70da146c8dc8', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 162),
('266', 'EDITH ANYANGO', 'F', 222, 'f7664060cc52bc6f3d620bcedc94a4b6', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 163),
('270', 'SHARON AKINYI', 'F', 244, '39059724f73a9969845dfe4146c5660e', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 164),
('272', 'KENNEDY  O. NYATAYA ', 'M', 193, '7a614fd06c325499f1680b9896beedeb', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 165),
('278', 'MESHACK MWALO', 'M', 232, '07cdfd23373b17c6b337251c22b7ea57', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 166),
('280', 'GEORGE ODHIAMBO', 'M', 215, '92c8c96e4c37100777c7190b76d28233', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 167),
('281', 'LYDIA AKOTH', 'F', 275, 'e3796ae838835da0b6f6ea37bcf8bcb7', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 168),
('283', 'DAVID ONYANGO TITO', 'M', 312, '0f49c89d1e7298bb9930789c8ed59d48', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 169),
('289', 'LILIAN AUMA ', 'F', 260, '839ab46820b524afda05122893c2fe8e', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 170),
('294', 'MICHAEL OLUM ODONGO', 'M', 223, '6883966fd8f918a4aa29be29d2c386fb', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 171),
('300', 'MOSES OCHIENG ONYANGO', 'M', 270, '94f6d7e04a4d452035300f18b984988c', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 172),
('301', 'PHOEBE ACHIENG', 'F', 238, '34ed066df378efacc9b924ec161e7639', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 173),
('307', 'ODHIAMBO M JUMA', 'M', 241, '8e98d81f8217304975ccb23337bb5761', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 174),
('332', 'VICTOR  OMONDI  ', 'M', 295, 'c042f4db68f23406c6cecf84a7ebb0fe', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 175),
('367', 'SHARON ANYANGO', 'F', 209, '05049e90fa4f5039a8cadc6acbb4b2cc', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 176),
('396', 'ALPHONCE  OUMA', 'M', 222, 'f8c1f23d6a8d8d7904fc0ea8e066b3bb', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 177),
('403', 'AUSTINE ODAWA', 'M', 302, 'bbf94b34eb32268ada57a3be5062fe7d', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 178),
('427', 'DORINE ACHIENG', 'F', 288, '18997733ec258a9fcaf239cc55d53363', '4S', 'S', 4, 'day', 'active', '', '', '', '', '', '', 0, 19800, 179);

-- --------------------------------------------------------

--
-- Table structure for table `student_bills`
--

CREATE TABLE `student_bills` (
  `admission` varchar(30) NOT NULL,
  `bill` varchar(40) NOT NULL,
  `term` varchar(40) NOT NULL,
  `date` varchar(40) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `student_notice`
--

CREATE TABLE `student_notice` (
  `id` int(10) NOT NULL,
  `class` varchar(50) NOT NULL,
  `message` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student_notice`
--

INSERT INTO `student_notice` (`id`, `class`, `message`, `file`) VALUES
(4, 'form 3', 'assignment', 'downloads/5b39f3309a83b5.93530349.docx'),
(5, 'form 1', 'the school trip scheduled for august has been postponed for further details chechk the file uploaded', 'downloads/5b39f399708c40.73670454.htm'),
(6, 'form 1', 'ahfshgsgfx', 'downloads/5b39f9622c8120.77334406.jpg'),
(7, 'form 4', 'cat i revision', 'downloads/5b39fa4ad24785.50038217.jpg'),
(8, 'form 1', 'mhbkhb', 'downloads/5b70e89b78ca42.41839330.pdf'),
(9, 'form 1', 'mhbkhb', 'downloads/5b70ea95678362.36510183.pdf'),
(10, 'form 1', 'mhbkhb', 'downloads/5b70eae0db5b65.17313263.pdf'),
(11, 'form 1', 'fanya kazi', 'downloads/5b70ed9be11db1.94200763.pdf'),
(12, 'form 1', 'fanya kazi', 'downloads/5b70edc4141cd5.39620203.pdf'),
(13, 'form 1', 'fanya kazi', 'downloads/5b70edd6d40451.30831873.pdf'),
(14, 'form 1', 'fanya kazi', 'downloads/5b70ee103237d6.21116762.pdf'),
(15, 'form 1', 'fanya kazi', 'downloads/5b70ee42ef9021.31132359.pdf'),
(16, 'form 1', 'fanya kazi', 'downloads/5b70ee7be29db3.22608907.pdf'),
(17, 'form 1', 'fanya kazi', 'downloads/5b70ee8d6cae20.39678974.pdf'),
(18, 'form 1', 'fanya kazi', 'downloads/5b70eea15911b6.10072153.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `SubjectName` varchar(30) NOT NULL,
  `SubjectKey` varchar(30) NOT NULL,
  `ID` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`SubjectName`, `SubjectKey`, `ID`) VALUES
('Mathematics', 'math', 1),
('English', 'eng', 2),
('Kiswahili', 'kis', 3),
('Chemistry', 'che', 4),
('Physics', 'phy', 5),
('Biology', 'bio', 6),
('History', 'his', 7),
('Geography', 'geo', 8),
('Agriculture', 'agri', 9),
('Business', 'bst', 10),
('CRE', 'cre', 11);

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `id` int(10) NOT NULL,
  `image` varchar(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`id`, `image`, `name`, `title`) VALUES
(1, 'picha/1 (1).jpg', 'Malvin Okeyo', 'Developer'),
(2, 'picha/1 (1).jpg', 'John Doe', 'School Captain');

-- --------------------------------------------------------

--
-- Table structure for table `terms`
--

CREATE TABLE `terms` (
  `term` varchar(30) NOT NULL,
  `opening_date` varchar(12) NOT NULL,
  `closing_date` varchar(30) NOT NULL,
  `state` varchar(30) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `terms`
--

INSERT INTO `terms` (`term`, `opening_date`, `closing_date`, `state`, `id`) VALUES
('2018 term 3', '27/08/2018', '', 'completed', 1),
('2019 term 1', '07/01/2019', '', 'completed', 2),
('2019 term 2', '3/1/2019', '27/08/2019', 'completed', 3),
('2019 term 3', '7-6-2019', '7-6-2019', 'In progress', 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `book_lend_list`
--
ALTER TABLE `book_lend_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `book_logs`
--
ALTER TABLE `book_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carousel_pics`
--
ALTER TABLE `carousel_pics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `featured_news`
--
ALTER TABLE `featured_news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `featured_student`
--
ALTER TABLE `featured_student`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fee_logs`
--
ALTER TABLE `fee_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fee_structure`
--
ALTER TABLE `fee_structure`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `final_result`
--
ALTER TABLE `final_result`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery_category`
--
ALTER TABLE `gallery_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grading_system`
--
ALTER TABLE `grading_system`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `graduates`
--
ALTER TABLE `graduates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `income_sources`
--
ALTER TABLE `income_sources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `minimum_subjects`
--
ALTER TABLE `minimum_subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_events`
--
ALTER TABLE `news_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `school_fees`
--
ALTER TABLE `school_fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_accounts`
--
ALTER TABLE `staff_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `streams`
--
ALTER TABLE `streams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_bills`
--
ALTER TABLE `student_bills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_notice`
--
ALTER TABLE `student_notice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terms`
--
ALTER TABLE `terms`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `book_lend_list`
--
ALTER TABLE `book_lend_list`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `book_logs`
--
ALTER TABLE `book_logs`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `carousel_pics`
--
ALTER TABLE `carousel_pics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `featured_news`
--
ALTER TABLE `featured_news`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `featured_student`
--
ALTER TABLE `featured_student`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `fee_logs`
--
ALTER TABLE `fee_logs`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `fee_structure`
--
ALTER TABLE `fee_structure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `final_result`
--
ALTER TABLE `final_result`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;
--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `gallery_category`
--
ALTER TABLE `gallery_category`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `grading_system`
--
ALTER TABLE `grading_system`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT for table `graduates`
--
ALTER TABLE `graduates`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;
--
-- AUTO_INCREMENT for table `income_sources`
--
ALTER TABLE `income_sources`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `minimum_subjects`
--
ALTER TABLE `minimum_subjects`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `news_events`
--
ALTER TABLE `news_events`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=231;
--
-- AUTO_INCREMENT for table `school_fees`
--
ALTER TABLE `school_fees`
  MODIFY `id` int(40) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `staff_accounts`
--
ALTER TABLE `staff_accounts`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `streams`
--
ALTER TABLE `streams`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;
--
-- AUTO_INCREMENT for table `student_bills`
--
ALTER TABLE `student_bills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `student_notice`
--
ALTER TABLE `student_notice`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `ID` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `terms`
--
ALTER TABLE `terms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
