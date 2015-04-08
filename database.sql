-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- Host: sql203.eb2a.com
-- Generation Time: Apr 08, 2015 at 05:40 AM
-- Server version: 5.6.22-71.0
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `eb2a_14146314_beacons`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE IF NOT EXISTS `admins` (
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`username`, `password`) VALUES
('admin', 'admin123123');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE IF NOT EXISTS `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `password` text NOT NULL,
  `beacon_id` text NOT NULL,
  `beacon_major` int(11) NOT NULL,
  `beacon_minor` int(11) NOT NULL,
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `password`, `beacon_id`, `beacon_major`, `beacon_minor`, `time_stamp`) VALUES
(46, 'employee', '123456', 'b9407f30-f5f8-466e-aff9-25556b57fe6d', 14977, 60382, '2015-04-04 10:08:16'),
(32, 'Ahmed Karam', '678999', 'B9407F30-F5F8-466E-AFF9-25556B57FE6D', 12345, 5678, '2015-03-18 11:06:16'),
(43, 'shamy', '123456', 'b9407f30-f5f8-466e-aff9-25556b57fe6d', 38037, 63166, '2015-03-16 17:30:42');

-- --------------------------------------------------------

--
-- Table structure for table `employees_log`
--

CREATE TABLE IF NOT EXISTS `employees_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `day` date NOT NULL,
  `holiday` int(11) NOT NULL DEFAULT '0',
  `login_time` time DEFAULT NULL,
  `logout_time` time DEFAULT NULL,
  `employee_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=65 ;

--
-- Dumping data for table `employees_log`
--

INSERT INTO `employees_log` (`id`, `day`, `holiday`, `login_time`, `logout_time`, `employee_id`) VALUES
(14, '2015-01-25', 1, '00:00:00', '00:00:00', 2),
(16, '2015-01-28', 1, '00:00:00', '00:00:00', 2),
(17, '2015-01-29', 1, '00:00:00', '00:00:00', 2),
(18, '2015-01-30', 0, '08:00:00', NULL, 2),
(20, '2015-01-24', 1, NULL, NULL, 2),
(21, '2015-01-26', 1, '00:00:00', '00:00:00', 2),
(22, '2015-01-27', 1, NULL, NULL, 2),
(23, '2015-01-21', 1, NULL, NULL, 2),
(24, '2015-01-22', 1, NULL, NULL, 2),
(25, '2015-01-07', 1, NULL, NULL, 2),
(28, '2015-01-01', 1, '00:00:00', '00:00:00', 2),
(29, '2015-01-02', 0, '09:00:00', '13:00:00', 2),
(30, '2015-01-03', 0, '09:00:00', '13:00:00', 2),
(31, '2015-01-04', 0, '09:00:00', '13:00:00', 2),
(32, '2015-01-08', 0, '09:30:00', '13:00:00', 2),
(33, '2015-01-10', 0, '09:00:00', '12:30:00', 2),
(34, '2015-01-18', 1, NULL, NULL, 2),
(35, '2015-01-14', 1, NULL, NULL, 2),
(36, '2015-03-17', 0, '22:42:36', '22:42:46', 43),
(37, '2015-03-18', 0, '19:51:40', '19:51:45', 43),
(42, '2015-03-23', 0, '23:33:13', '23:33:27', 43),
(43, '2015-03-16', 1, NULL, NULL, 43),
(44, '2015-03-27', 0, '19:28:41', '19:29:03', 43),
(45, '2015-03-28', 0, '19:17:23', '16:21:59', 43),
(47, '2015-03-29', 0, '23:13:18', '23:13:51', 43),
(48, '2015-03-30', 0, '14:46:29', NULL, 43),
(49, '2015-03-31', 0, '21:48:24', '21:55:03', 43),
(50, '2015-04-01', 0, '18:24:11', '19:53:38', 43),
(61, '2015-04-02', 0, '15:23:28', '16:19:40', 43),
(62, '2015-04-03', 0, '19:22:58', '19:22:44', 43),
(63, '2015-04-04', 0, '12:38:25', '12:39:21', 46),
(64, '2015-04-07', 0, '07:40:48', '08:42:35', 43);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `start_time`, `end_time`) VALUES
(21, '09:00:00', '12:30:00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
