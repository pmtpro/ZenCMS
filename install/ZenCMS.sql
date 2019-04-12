-- phpMyAdmin SQL Dump
-- version 3.4.11.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 17, 2013 at 07:36 PM
-- Server version: 5.1.65
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `zenthang_m9x`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_config`
--

CREATE TABLE IF NOT EXISTS `zen_cms_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(50) NOT NULL,
  `value` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=55 ;

--
-- Dumping data for table `zen_cms_config`
--

INSERT INTO `zen_cms_config` (`id`, `key`, `value`) VALUES
(1, 'home', 'http://m9x.info'),
(2, 'theme_mobile', 'android'),
(3, 'show_chat', ''),
(4, 'title', 'ZenCMS - Web developers'),
(5, 'keyword', 'ZenCMS, wap developers, web, code wap, code lam wap, code wap seo tot, phat trien wap'),
(6, 'des', 'ZenCMS là phần mềm mã nguồn mở chuyên để làm wap, web, wap game, wap truyện, hình ảnh, rất tốt cho seo. Với rất nhiều tính năng thú vị'),
(16, 'delete_confirm_java', 'ZenCMS - Web developers'),
(17, 'set_copyright_mf_java', '1'),
(20, 'on_box_comment_game', ''),
(21, 'on_box_comment_story', '1'),
(22, 'on_box_comment_image', '1'),
(23, 'on_box_comment_video', ''),
(24, 'on_top_hot', '1'),
(25, 'num_post_top_hot', '5'),
(26, 'show_icon_on_top_hot', 'auto'),
(27, 'on_top_new', '1'),
(28, 'num_post_top_new', '10'),
(29, 'show_icon_on_top_new', 'auto'),
(30, 'fist_list_show_on_home', 'new'),
(31, 'navipage_show_on_home', 'new'),
(32, 'show_hot_link', '1'),
(33, 'url_bookmark_java', 'http://goo.gl/usHVuf'),
(34, 'auto_create_bookmark_java', ''),
(35, 'title_bookmark_java', 'ZenCMS - Web developers'),
(38, 'zen_active', 'b394126a0e52e75f1e3d535d0fb0d33c'),
(37, 'zen_license', ''),
(39, 'email', 'thangangle@gmail.com'),
(48, 'templates', 'a:7:{s:6:"Mobile";s:7:"default";s:5:"other";s:7:"default";s:3:"iOS";s:7:"default";s:9:"AndroidOS";s:7:"default";s:6:"JavaOS";s:7:"default";s:9:"SymbianOS";s:7:"default";s:14:"WindowsPhoneOS";s:7:"default";}'),
(40, 'mail_host', 'smtp.gmail.com'),
(41, 'mail_port', '587'),
(42, 'mail_smtp_secure', 'tls'),
(43, 'mail_smtp_auth', '1'),
(46, 'mail_setfrom', 'example@gmail.com'),
(44, 'mail_username', 'example@gmail.com'),
(45, 'mail_password', ''),
(47, 'mail_name', 'Example'),
(49, 'logo_watermark', '2lol.png'),
(50, 'chatbox_allow_guest_chat', '1'),
(51, 'chatbox_num_item_per_page', '10'),
(52, 'register_turn_off', '0'),
(53, 'register_turn_on_authorized_email', '1'),
(54, 'register_message', 'Website đang bảo trì &lt;b&gt;Vui lòng quay lại sau&lt;/b&gt;');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_forum`
--

CREATE TABLE IF NOT EXISTS `zen_cms_forum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `from` varchar(50) NOT NULL,
  `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `refid` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `des` text NOT NULL,
  `close` int(11) NOT NULL,
  `close_who` varchar(50) NOT NULL,
  `edit` int(11) NOT NULL,
  `who_edit` varchar(50) NOT NULL,
  `time_edit` int(11) NOT NULL,
  `protect` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `time_last_active` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `hidden` int(11) NOT NULL,
  `kiemduyet` int(11) NOT NULL,
  `who_kiemduyet` varchar(50) NOT NULL,
  `delete` int(11) NOT NULL,
  `who_delete` varchar(50) NOT NULL,
  `time_delete` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_forum_files`
--

CREATE TABLE IF NOT EXISTS `zen_cms_forum_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `name` varchar(300) NOT NULL,
  `size` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `down` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_forum_votes`
--

CREATE TABLE IF NOT EXISTS `zen_cms_forum_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `vid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `count` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_forum_votes_statistics`
--

CREATE TABLE IF NOT EXISTS `zen_cms_forum_votes_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `vid` int(11) NOT NULL,
  `vaid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `from` varchar(50) NOT NULL,
  `time` int(11) NOT NULL,
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
-- Table structure for table `zen_cms_link_list`
--

CREATE TABLE IF NOT EXISTS `zen_cms_link_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `title` varchar(200) NOT NULL,
  `link` varchar(255) NOT NULL,
  `rel` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `tags` text NOT NULL,
  `style` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
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
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_blogs`
--

CREATE TABLE IF NOT EXISTS `zen_cms_blogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `type_url` varchar(50) NOT NULL,
  `title` text NOT NULL,
  `type_title` varchar(50) NOT NULL,
  `name` text NOT NULL,
  `content` longtext NOT NULL,
  `keyword` text NOT NULL,
  `des` text NOT NULL,
  `icon` varchar(255) NOT NULL,
  `rel` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `type_data` varchar(50) NOT NULL,
  `type_view` varchar(50) NOT NULL,
  `font` varchar(50) NOT NULL,
  `color` varchar(50) NOT NULL,
  `recycle_bin` int(11) NOT NULL,
  `view` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`id`)
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
  `birth` varchar(20) NOT NULL,
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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
