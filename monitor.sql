SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `monitor`
--

-- --------------------------------------------------------

--
-- 表的结构 `device`
--

CREATE TABLE IF NOT EXISTS `device` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `device_index` bigint(13) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `lasttime` int(10) unsigned NOT NULL,
  `longitude` int(10) unsigned NOT NULL,
  `latitude` int(10) unsigned NOT NULL,
  `temperature` double NOT NULL,
  `humidity` double NOT NULL,
  `particulate_matter` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `device_index` (`device_index`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `device`
--

INSERT INTO `device` (`id`, `device_index`, `name`, `description`, `lasttime`, `longitude`, `latitude`, `temperature`, `humidity`, `particulate_matter`) VALUES
(1, 13726247339, '暨南大学珠海校区监测点', '', 1413449566, 113540718, 22256467, 25, 57, 39),
(2, 13800138000, '板障山监测点', '', 1400000000, 113555302, 22256467, 30, 100, 30),
(3, 13800138001, '香洲区政府监测点', '', 1400000000, 113551173, 22273701, 32, 83, 90);

-- --------------------------------------------------------

--
-- 表的结构 `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `device_index` bigint(13) unsigned NOT NULL COMMENT 'phonenumber',
  `time` int(10) unsigned NOT NULL,
  `longitude` int(10) unsigned NOT NULL,
  `latitude` int(10) unsigned NOT NULL,
  `temperature` double NOT NULL,
  `humidity` double NOT NULL,
  `particulate_matter` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index` (`device_index`),
  KEY `time` (`time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- 转存表中的数据 `logs`
--

INSERT INTO `logs` (`id`, `device_index`, `time`, `longitude`, `latitude`, `temperature`, `humidity`, `particulate_matter`) VALUES
(2, 13726247339, 1412350288, 113540718, 22256467, 32.5, 90, 25),
(3, 13800138000, 1412350542, 113555302, 22256467, 30, 100, 30),
(4, 13800138001, 1412350646, 113551173, 22273701, 32, 83, 90),
(5, 13726247339, 1413438096, 113540718, 22256467, 28, 88, 29),
(6, 13726247339, 1413438123, 113540718, 22256467, 29, 70, 27),
(7, 13726247339, 1413448750, 113540718, 22256467, 26, 60, 35),
(8, 13726247339, 1413448904, 113540718, 22256467, 26, 60, 35),
(9, 13726247339, 1413449566, 113540718, 22256467, 25, 57, 39);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
