
--
-- Database: `fm`
--

-- --------------------------------------------------------

--
-- Table structure for table `feed_author`
--

CREATE TABLE IF NOT EXISTS `feed_author` (
  `author_id` int(11) NOT NULL AUTO_INCREMENT,
  `author_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `author_slug` varchar(255) CHARACTER SET utf8 NOT NULL,
  `author_site` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`author_id`),
  UNIQUE KEY `author_name` (`author_name`,`author_site`),
  UNIQUE KEY `author_slug` (`author_slug`,`author_site`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `feed_author`
--


-- --------------------------------------------------------

--
-- Table structure for table `feed_category`
--

CREATE TABLE IF NOT EXISTS `feed_category` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `cat_slug` varchar(180) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`cat_id`),
  KEY `cat_slug` (`cat_slug`),
  KEY `cat_id` (`cat_id`,`cat_slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `feed_category`
--


-- --------------------------------------------------------

--
-- Table structure for table `feed_item`
--

CREATE TABLE IF NOT EXISTS `feed_item` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_url` varchar(255) CHARACTER SET utf8 NOT NULL,
  `item_title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `item_excerpt` text CHARACTER SET utf8 NOT NULL,
  `item_content` text CHARACTER SET utf8 NOT NULL,
  `item_slug` varchar(255) CHARACTER SET utf8 NOT NULL,
  `item_site` int(11) NOT NULL DEFAULT '0',
  `item_author` int(11) NOT NULL DEFAULT '0',
  `item_datetime` datetime NOT NULL,
  `item_status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`item_id`),
  KEY `item_site` (`item_site`),
  KEY `item_author` (`item_author`),
  KEY `item_site_2` (`item_site`,`item_author`,`item_status`),
  KEY `item_author_2` (`item_author`,`item_status`),
  FULLTEXT KEY `item_excerpt` (`item_excerpt`),
  FULLTEXT KEY `item_content` (`item_content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `feed_item`
--


-- --------------------------------------------------------

--
-- Table structure for table `feed_option`
--

CREATE TABLE IF NOT EXISTS `feed_option` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `option_name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `option_value` text CHARACTER SET utf8 NOT NULL,
  `option_status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `feed_option`
--

INSERT INTO `feed_option` (`option_id`, `option_name`, `option_value`, `option_status`) VALUES
(1, 'facebook_api_key', '', 1),
(2, 'facebook_secret_key', '', 1),
(3, 'table_list', '10', 1),
(4, 'fetch_timestamp', '1293073981', 1),
(5, 'fetch_interval', '111', 1),
(6, 'fetch_daily_count', '8', 1),
(7, 'no_data_message', 'No data at the moment', 1);

-- --------------------------------------------------------

--
-- Table structure for table `feed_relationship`
--

CREATE TABLE IF NOT EXISTS `feed_relationship` (
  `rel_id` int(11) NOT NULL AUTO_INCREMENT,
  `rel_type` int(1) NOT NULL DEFAULT '0',
  `rel_attribute` int(11) NOT NULL DEFAULT '0',
  `rel_value` int(11) NOT NULL DEFAULT '0',
  `rel_date` date NOT NULL,
  PRIMARY KEY (`rel_id`),
  KEY `rel_type` (`rel_type`,`rel_attribute`),
  KEY `rel_type_2` (`rel_type`,`rel_value`),
  KEY `rel_date` (`rel_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `feed_relationship`
--


-- --------------------------------------------------------

--
-- Table structure for table `feed_site`
--

CREATE TABLE IF NOT EXISTS `feed_site` (
  `site_id` int(11) NOT NULL AUTO_INCREMENT,
  `site_url` varchar(255) NOT NULL,
  `site_feed` varchar(255) NOT NULL,
  `site_name` varchar(255) NOT NULL,
  `site_query` tinyint(1) NOT NULL DEFAULT '1',
  `site_lastquery` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `site_status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`site_id`),
  KEY `site_id` (`site_id`,`site_status`),
  KEY `site_status` (`site_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `feed_site`
--


-- --------------------------------------------------------

--
-- Table structure for table `feed_suggest`
--

CREATE TABLE IF NOT EXISTS `feed_suggest` (
  `suggest_id` int(11) NOT NULL AUTO_INCREMENT,
  `suggest_url` varchar(255) CHARACTER SET utf8 NOT NULL,
  `suggest_desc` text CHARACTER SET utf8 NOT NULL,
  `suggest_userid` int(11) NOT NULL DEFAULT '0',
  `suggest_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`suggest_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `feed_suggest`
--


-- --------------------------------------------------------

--
-- Table structure for table `feed_user`
--

CREATE TABLE IF NOT EXISTS `feed_user` (
  `user_id` int(11) NOT NULL,
  `user_firstname` varchar(90) CHARACTER SET utf8 NOT NULL,
  `user_lastname` varchar(90) CHARACTER SET utf8 NOT NULL,
  `user_name` varchar(90) CHARACTER SET utf8 NOT NULL,
  `user_locale` varchar(6) CHARACTER SET utf8 NOT NULL,
  `user_profile_url` varchar(90) CHARACTER SET utf8 NOT NULL,
  `user_image` varchar(90) CHARACTER SET utf8 NOT NULL,
  `user_group` int(11) NOT NULL DEFAULT '2',
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `feed_user`
--


-- --------------------------------------------------------

--
-- Table structure for table `feed_user_group`
--

CREATE TABLE IF NOT EXISTS `feed_user_group` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `group_status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `feed_user_group`
--

INSERT INTO `feed_user_group` (`group_id`, `group_name`, `group_status`) VALUES
(1, 'Administrator', 1),
(2, 'Member', 1);
