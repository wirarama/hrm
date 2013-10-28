-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 04, 2011 at 01:51 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hrd`
--

-- --------------------------------------------------------

--
-- Table structure for table `annual_leave`
--

CREATE TABLE IF NOT EXISTS `annual_leave` (
  `id` int(11) NOT NULL,
  `staff` int(11) NOT NULL,
  `start` date NOT NULL,
  `stop` date NOT NULL,
  `amount` tinyint(4) NOT NULL,
  `description` text NOT NULL,
  `status` enum('approved','not approved') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `annual_leave`
--

INSERT INTO `annual_leave` (`id`, `staff`, `start`, `stop`, `amount`, `description`, `status`) VALUES
(1, 2, '2011-11-11', '2011-11-30', 14, '<p>test</p>', 'not approved');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `description`) VALUES
(1, 'aktivitas harian', '');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE IF NOT EXISTS `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `telp` varchar(30) DEFAULT NULL,
  `mobile` varchar(30) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`id`, `category`, `name`, `address`, `telp`, `mobile`, `email`) VALUES
(1, 2, 'restaurant A', 'test address', '036123456', '08123456789', 'restauranta@yahoo.com'),
(2, 1, 'hotel', 'alamat hotel', '036123456', '08123456789', 'hotela@yahoo.com');

-- --------------------------------------------------------

--
-- Table structure for table `client_category`
--

CREATE TABLE IF NOT EXISTS `client_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `client_category`
--

INSERT INTO `client_category` (`id`, `name`, `description`) VALUES
(1, 'hotel', ''),
(2, 'restaurant', '');

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE IF NOT EXISTS `history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `doneby` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE IF NOT EXISTS `login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `overtime`
--

CREATE TABLE IF NOT EXISTS `overtime` (
  `id` int(11) NOT NULL,
  `staff` int(11) NOT NULL,
  `start` datetime NOT NULL,
  `stop` datetime NOT NULL,
  `amount` char(5) NOT NULL,
  `description` text NOT NULL,
  `status` enum('approved','not approved') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `overtime`
--

INSERT INTO `overtime` (`id`, `staff`, `start`, `stop`, `amount`, `description`, `status`) VALUES
(1, 3, '2011-11-28 16:37:00', '2011-11-29 18:20:00', '25:43', '<p>test</p>', 'not approved');

-- --------------------------------------------------------

--
-- Table structure for table `shift`
--

CREATE TABLE IF NOT EXISTS `shift` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff` int(11) NOT NULL,
  `shift` enum('off','shift1','reguler','shift2','OT shift1','OT reguler','OT shift2') NOT NULL,
  `date` date NOT NULL,
  `week` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `shift`
--

INSERT INTO `shift` (`id`, `staff`, `shift`, `date`, `week`) VALUES
(1, 1, 'shift1', '2011-11-28', 1),
(2, 1, 'shift1', '2011-11-29', 2),
(3, 1, 'shift2', '2011-11-30', 3),
(4, 1, 'shift2', '2011-12-01', 4),
(5, 1, 'reguler', '2011-12-02', 5),
(6, 1, 'off', '2011-12-03', 6),
(7, 1, 'off', '2011-12-04', 7),
(8, 2, 'off', '2011-11-28', 1),
(9, 2, 'off', '2011-11-29', 2),
(10, 2, 'shift1', '2011-11-30', 3),
(11, 2, 'shift1', '2011-12-01', 4),
(12, 2, 'shift2', '2011-12-02', 5),
(13, 2, 'shift2', '2011-12-03', 6),
(14, 2, 'reguler', '2011-12-04', 7),
(15, 3, 'shift2', '2011-11-28', 1),
(16, 3, 'shift2', '2011-11-29', 2),
(17, 3, 'off', '2011-11-30', 3),
(18, 3, 'off', '2011-12-01', 4),
(19, 3, 'reguler', '2011-12-02', 5),
(20, 3, 'reguler', '2011-12-03', 6),
(21, 3, 'reguler', '2011-12-04', 7);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE IF NOT EXISTS `staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_group` int(11) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `telp` varchar(30) DEFAULT NULL,
  `mobile` varchar(30) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `status` enum('admin','user') NOT NULL,
  `pictures` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `staff_group`, `username`, `password`, `first_name`, `last_name`, `address`, `telp`, `mobile`, `email`, `status`, `pictures`) VALUES
(1, 1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', 'test', NULL, '', '', '', 'admin', 'staff1.jpg'),
(2, 1, 'test', '16d7a4fca7442dda3ad93c9a726597e4', 'test', 'test', 'test', '123456', '123456', 'test@gmail.com', 'admin', 'staff2.jpg'),
(3, 1, 'etyetyety', 'e130e5e618f15cee7a519d8b7b8306a0', 'werwerwer', 'wrwerwerwer', 'ewrwerwerew', '134134134', '13413434', 'wwerwe@gmail.com', 'admin', 'staff3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `staff_group`
--

CREATE TABLE IF NOT EXISTS `staff_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `head` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `staff_group`
--

INSERT INTO `staff_group` (`id`, `name`, `description`, `head`) VALUES
(1, 'akunting', '', 1),
(2, 'marketing', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE IF NOT EXISTS `subject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`id`, `category`, `name`, `description`) VALUES
(1, 1, 'browsing internet', '');

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE IF NOT EXISTS `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `category` int(11) NOT NULL,
  `subject` int(11) NOT NULL,
  `client` int(11) NOT NULL,
  `description` text NOT NULL,
  `priority` tinyint(4) NOT NULL,
  `status` enum('closed','on progress','pending') NOT NULL,
  `start` datetime DEFAULT NULL,
  `stop` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `task_staff`
--

CREATE TABLE IF NOT EXISTS `task_staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task` int(11) NOT NULL,
  `staff` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
