-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 14, 2017 at 06:18 PM
-- Server version: 5.5.58-0ubuntu0.14.04.1-log
-- PHP Version: 5.5.9-1ubuntu4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ci-boilrerplate`
--

-- --------------------------------------------------------

--
-- Table structure for table `master_users`
--

CREATE TABLE IF NOT EXISTS `master_users` (
  `uid` int(5) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_status` enum('0','1','2','3') NOT NULL DEFAULT '0' COMMENT 'pending,active,inactive,deleted',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `master_users`
--

INSERT INTO `master_users` (`uid`, `username`, `user_email`, `user_password`, `user_status`) VALUES
(1, 'sameer', 'sam@sam.com', '827ccb0eea8a706c4c34a16891f84e7b', '1');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
