-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 07, 2014 at 03:15 AM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Table structure for table `zen_cms_blogs`
--

CREATE TABLE IF NOT EXISTS `zen_cms_blogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `title` text NOT NULL,
  `name` text NOT NULL,
  `content` longtext NOT NULL,
  `keyword` text NOT NULL,
  `des` text NOT NULL,
  `icon` varchar(255) NOT NULL,
  `rel` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `type_data` varchar(50) NOT NULL,
  `status` int(11) NOT NULL,
  `view` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_userid` (`type`),
  KEY `z_idx_type` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_blogs_comments`
--

CREATE TABLE IF NOT EXISTS `zen_cms_blogs_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `msg` text NOT NULL,
  `ip` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_blogs_files`
--

CREATE TABLE IF NOT EXISTS `zen_cms_blogs_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `url` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `down` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `status` varchar(500) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_blogs_images`
--

CREATE TABLE IF NOT EXISTS `zen_cms_blogs_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_blogs_links`
--

CREATE TABLE IF NOT EXISTS `zen_cms_blogs_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `link` varchar(255) NOT NULL,
  `click` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_blogs_settings`
--

CREATE TABLE IF NOT EXISTS `zen_cms_blogs_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_chatbox`
--

CREATE TABLE IF NOT EXISTS `zen_cms_chatbox` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `time` int(11) NOT NULL,
  `user_agent` text NOT NULL,
  `group` varchar(100) NOT NULL,
  `edit` int(11) NOT NULL,
  `who_edit` varchar(50) NOT NULL,
  `time_edit` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_config`
--

CREATE TABLE IF NOT EXISTS `zen_cms_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(50) NOT NULL,
  `value` varchar(500) NOT NULL,
  `func_import` varchar(50) NOT NULL,
  `func_export` varchar(50) NOT NULL,
  `locate` varchar(50) NOT NULL,
  `for` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_dislikes`
--

CREATE TABLE IF NOT EXISTS `zen_cms_dislikes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fromid` int(11) NOT NULL,
  `toid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `ip` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_likes`
--

CREATE TABLE IF NOT EXISTS `zen_cms_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fromid` int(11) NOT NULL,
  `toid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `ip` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_messages`
--

CREATE TABLE IF NOT EXISTS `zen_cms_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(50) NOT NULL,
  `to` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `msg` text NOT NULL,
  `readed` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `hidden` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_mobigate`
--

CREATE TABLE IF NOT EXISTS `zen_cms_mobigate` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `appid` text NOT NULL,
  `stt` int(100) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_tags`
--

CREATE TABLE IF NOT EXISTS `zen_cms_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `tag` varchar(100) NOT NULL,
  `time` int(11) NOT NULL,
  `view` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_users`
--

CREATE TABLE IF NOT EXISTS `zen_cms_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `birth` int(11) DEFAULT NULL,
  `sex` varchar(10) NOT NULL,
  `avatar` varchar(240) NOT NULL,
  `status` tinytext NOT NULL,
  `welcome` text NOT NULL,
  `sign` varchar(100) NOT NULL,
  `like` int(11) NOT NULL,
  `last_ip` varchar(255) NOT NULL,
  `last_login` int(11) NOT NULL,
  `time_reg` int(11) NOT NULL,
  `perm` varchar(50) DEFAULT NULL,
  `comment` varchar(50) NOT NULL,
  `security_code` text NOT NULL,
  `profile_theme` tinyint(4) NOT NULL,
  `profile_css` text NOT NULL,
  `profile_theme_ver` float NOT NULL,
  `exp` int(11) NOT NULL,
  `coin` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `smiles` text NOT NULL,
  `ss_zen_login` varchar(1000) NOT NULL,
  `protect` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_users_set`
--

CREATE TABLE IF NOT EXISTS `zen_cms_users_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `key` varchar(50) CHARACTER SET utf8 NOT NULL,
  `value` varchar(255) NOT NULL,
  `func_import` varchar(50) NOT NULL,
  `func_export` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_views`
--

CREATE TABLE IF NOT EXISTS `zen_cms_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(100) NOT NULL,
  `to` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_widgets`
--

CREATE TABLE IF NOT EXISTS `zen_cms_widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wg` text NOT NULL,
  `weight` int(11) NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `template` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
