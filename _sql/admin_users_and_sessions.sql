-- phpMyAdmin SQL Dump
-- version 3.3.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 13, 2011 at 11:06 AM
-- Server version: 5.1.46
-- PHP Version: 5.2.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `web_framework`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_sessions`
--

CREATE TABLE IF NOT EXISTS `admin_sessions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `session` varchar(32) NOT NULL DEFAULT '',
  `user` int(11) unsigned NOT NULL DEFAULT '0',
  `startdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `language` char(2) NOT NULL DEFAULT 'EN',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `admin_sessions`
--


-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL DEFAULT 'user',
  `username` varchar(64) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `language` char(2) NOT NULL DEFAULT 'EN',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `type`, `username`, `password`, `name`, `language`) VALUES(1, 'admin0', 'admin', '16d7a4fca7442dda3ad93c9a726597e4', 'Administrator', 'EN');
