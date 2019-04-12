--
-- Table structure for table `zen_cms_blogs`
--

CREATE TABLE IF NOT EXISTS `zen_cms_blogs` (
`id` int(11) NOT NULL,
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
  `attach` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL,
  `time_update` int(11) NOT NULL,
  `weight` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_blogs_comments`
--

CREATE TABLE IF NOT EXISTS `zen_cms_blogs_comments` (
`id` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `msg` text NOT NULL,
  `ip` varchar(255) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_blogs_files`
--

CREATE TABLE IF NOT EXISTS `zen_cms_blogs_files` (
`id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `url` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `down` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `status` varchar(500) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_blogs_images`
--

CREATE TABLE IF NOT EXISTS `zen_cms_blogs_images` (
`id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_blogs_links`
--

CREATE TABLE IF NOT EXISTS `zen_cms_blogs_links` (
`id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `link` varchar(255) NOT NULL,
  `click` int(11) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_blogs_settings`
--

CREATE TABLE IF NOT EXISTS `zen_cms_blogs_settings` (
`id` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_config`
--

CREATE TABLE IF NOT EXISTS `zen_cms_config` (
`id` int(11) NOT NULL,
  `key` varchar(50) NOT NULL,
  `value` text,
  `func_import` varchar(50) NOT NULL,
  `func_export` varchar(50) NOT NULL,
  `locate` varchar(50) NOT NULL,
  `for` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_dislikes`
--

CREATE TABLE IF NOT EXISTS `zen_cms_dislikes` (
`id` int(11) NOT NULL,
  `fromid` int(11) NOT NULL,
  `toid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `ip` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_likes`
--

CREATE TABLE IF NOT EXISTS `zen_cms_likes` (
`id` int(11) NOT NULL,
  `fromid` int(11) NOT NULL,
  `toid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `ip` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_messages`
--

CREATE TABLE IF NOT EXISTS `zen_cms_messages` (
`id` int(11) NOT NULL,
  `from` varchar(50) NOT NULL,
  `to` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `msg` text NOT NULL,
  `readed` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `hidden` int(11) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_tags`
--

CREATE TABLE IF NOT EXISTS `zen_cms_tags` (
`id` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `tag` varchar(100) NOT NULL,
  `time` int(11) NOT NULL,
  `view` int(11) NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_users`
--

CREATE TABLE IF NOT EXISTS `zen_cms_users` (
`id` int(11) NOT NULL,
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
  `protect` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_users_set`
--

CREATE TABLE IF NOT EXISTS `zen_cms_users_set` (
`id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `key` varchar(50) NOT NULL,
  `value` varchar(255) NOT NULL,
  `func_import` varchar(50) NOT NULL,
  `func_export` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zen_cms_widgets`
--

CREATE TABLE IF NOT EXISTS `zen_cms_widgets` (
`id` int(11) NOT NULL,
  `wg` text NOT NULL,
  `weight` int(11) NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `template` varchar(255) NOT NULL,
  `callback` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `zen_cms_blogs`
--
ALTER TABLE `zen_cms_blogs`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_userid` (`type`), ADD KEY `z_idx_type` (`type`);

--
-- Indexes for table `zen_cms_blogs_comments`
--
ALTER TABLE `zen_cms_blogs_comments`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zen_cms_blogs_files`
--
ALTER TABLE `zen_cms_blogs_files`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zen_cms_blogs_images`
--
ALTER TABLE `zen_cms_blogs_images`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zen_cms_blogs_links`
--
ALTER TABLE `zen_cms_blogs_links`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zen_cms_blogs_settings`
--
ALTER TABLE `zen_cms_blogs_settings`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zen_cms_config`
--
ALTER TABLE `zen_cms_config`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zen_cms_dislikes`
--
ALTER TABLE `zen_cms_dislikes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zen_cms_likes`
--
ALTER TABLE `zen_cms_likes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zen_cms_messages`
--
ALTER TABLE `zen_cms_messages`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zen_cms_tags`
--
ALTER TABLE `zen_cms_tags`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zen_cms_users`
--
ALTER TABLE `zen_cms_users`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zen_cms_users_set`
--
ALTER TABLE `zen_cms_users_set`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zen_cms_widgets`
--
ALTER TABLE `zen_cms_widgets`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `zen_cms_blogs`
--
ALTER TABLE `zen_cms_blogs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `zen_cms_blogs_comments`
--
ALTER TABLE `zen_cms_blogs_comments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `zen_cms_blogs_files`
--
ALTER TABLE `zen_cms_blogs_files`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `zen_cms_blogs_images`
--
ALTER TABLE `zen_cms_blogs_images`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `zen_cms_blogs_links`
--
ALTER TABLE `zen_cms_blogs_links`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `zen_cms_blogs_settings`
--
ALTER TABLE `zen_cms_blogs_settings`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `zen_cms_config`
--
ALTER TABLE `zen_cms_config`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `zen_cms_dislikes`
--
ALTER TABLE `zen_cms_dislikes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `zen_cms_likes`
--
ALTER TABLE `zen_cms_likes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `zen_cms_messages`
--
ALTER TABLE `zen_cms_messages`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `zen_cms_tags`
--
ALTER TABLE `zen_cms_tags`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `zen_cms_users`
--
ALTER TABLE `zen_cms_users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `zen_cms_users_set`
--
ALTER TABLE `zen_cms_users_set`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `zen_cms_widgets`
--
ALTER TABLE `zen_cms_widgets`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
