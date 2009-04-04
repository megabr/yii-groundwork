-- phpMyAdmin SQL Dump
-- version 3.1.2deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 03, 2009 at 09:09 PM
-- Server version: 5.0.75
-- PHP Version: 5.2.6-3ubuntu4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `yii_groundwork`
--

-- --------------------------------------------------------

--
-- Table structure for table `Permission`
--

CREATE TABLE IF NOT EXISTS `Permission` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(45) NOT NULL,
  `description` varchar(45) default NULL,
  `controller` varchar(45) NOT NULL,
  `action` varchar(45) NOT NULL,
  `bizrule` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unique_access_rule` (`controller`,`action`,`bizrule`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `Permission`
--

INSERT INTO `Permission` (`id`, `name`, `description`, `controller`, `action`, `bizrule`) VALUES
(1, 'user/admin', 'user/admin', 'user', 'admin', ''),
(2, 'user/modify', 'user/modify', 'user', 'modify', ''),
(3, 'user/update', 'user/update', 'user', 'update', ''),
(4, 'user/create', 'user/create', 'user', 'create', ''),
(5, 'user/delete', 'user/delete', 'user', 'delete', ''),

-- --------------------------------------------------------

--
-- Table structure for table `Role`
--

CREATE TABLE IF NOT EXISTS `Role` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(45) NOT NULL,
  `description` varchar(45) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=100 ;

--
-- Dumping data for table `Role`
--

INSERT INTO `Role` (`id`, `name`, `description`) VALUES
(1, 'Member', 'Member'),
(99, 'Admin', 'Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `Role_has_Permission`
--

CREATE TABLE IF NOT EXISTS `Role_has_Permission` (
  `PermissionId` int(11) NOT NULL,
  `RoleId` int(11) NOT NULL,
  PRIMARY KEY  (`PermissionId`,`RoleId`),
  KEY `fk_Permission_has_Role_Permission` (`PermissionId`),
  KEY `fk_Permission_has_Role_Role` (`RoleId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Role_has_Permission`
--

INSERT INTO `Role_has_Permission` (`PermissionId`, `RoleId`) VALUES
(1, 99),
(2, 99),
(3, 1),
(3, 99),
(4, 1),
(4, 99),
(5, 99),
(6, 99),
(7, 99),
(8, 1),
(8, 99),
(9, 99),
(10, 99);

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `emailConfirmed` char(21) default NULL,
  `createTime` datetime default NULL,
  `updateTime` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`id`, `username`, `password`, `email`, `emailConfirmed`, `createTime`, `updateTime`) VALUES
(1, 'admin', '5f4dcc3b5aa765d61d8327deb882cf99', 'admin@example.com', 'h4kxzam3ry6dbbzbxzwvm', '2009-02-25 02:49:27', '2009-03-26 21:24:19');

-- --------------------------------------------------------

--
-- Table structure for table `User_has_Role`
--

CREATE TABLE IF NOT EXISTS `User_has_Role` (
  `UserId` int(11) NOT NULL,
  `RoleId` int(11) NOT NULL,
  PRIMARY KEY  (`UserId`,`RoleId`),
  KEY `fk_User_has_Role_User` (`UserId`),
  KEY `fk_User_has_Role_Role` (`RoleId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `User_has_Role`
--

INSERT INTO `User_has_Role` (`UserId`, `RoleId`) VALUES
(1, 99);
