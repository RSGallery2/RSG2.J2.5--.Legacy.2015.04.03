#
# install sql for RSGallery2
#

CREATE TABLE IF NOT EXISTS `#__rsgallery2_galleries` (
  `id` int(11) NOT NULL auto_increment,
  `parent` int(11) NOT NULL default 0,
  `name` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `published` tinyint(1) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `hits` int(11) NOT NULL default '0',
  `params` text NOT NULL,
  `user` tinyint(4) NOT NULL default '0',
  `uid` int(11) unsigned NOT NULL default '0',
  `allowed` varchar(100) NOT NULL default '0',
  `thumb_id` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS `#__rsgallery2_files` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `descr` text,
  `gallery_id` int(9) unsigned NOT NULL default '0',
  `title` varchar(50) NOT NULL default '',
  `hits` int(11) unsigned NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `rating` int(10) unsigned NOT NULL default '0',
  `votes` int(10) unsigned NOT NULL default '0',
  `comments` int(10) unsigned NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '1',
  `checked_out` int(11) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(9) unsigned NOT NULL default '0',
  `approved` tinyint(1) unsigned NOT NULL default '1',
  `userid` int(10) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UK_name` (`name`),
  KEY `id` (`id`)
)TYPE=MyISAM ;

CREATE TABLE `#__rsgallery2_comments` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_ip` varchar(50) NOT NULL default '0.0.0.0',
  `parent_id` int(11) NOT NULL default '0',
  `item_id` int(11) NOT NULL,
  `item_table` varchar(50) default NULL,
  `datetime` datetime NOT NULL,
  `subject` varchar(100) default NULL,
  `comment` text NOT NULL,
  `published` tinyint(1) NOT NULL default '1',
  `checked_out` int(11) default NULL,
  `checked_out_time` datetime default NULL,
  `ordering` int(11) NOT NULL,
  `params` text,
  `hits` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS `#__rsgallery2_config` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  `value` text NOT NULL,
 PRIMARY KEY `id` (`id`)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS `#__rsgallery2_acl` (
  `id` int(11) NOT NULL auto_increment,
  `gallery_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL default '0',
  `public_view` tinyint(1) NOT NULL default '1',
  `public_up_mod_img` tinyint(1) NOT NULL default '0',
  `public_del_img` tinyint(1) NOT NULL default '0',
  `public_create_mod_gal` tinyint(1) NOT NULL default '0',
  `public_del_gal` tinyint(1) NOT NULL default '0',
  `public_vote_view` tinyint( 1 ) NOT NULL default '1',
  `public_vote_vote` tinyint( 1 ) NOT NULL default '0',
  `registered_view` tinyint(1) NOT NULL default '1',
  `registered_up_mod_img` tinyint(1) NOT NULL default '1',
  `registered_del_img` tinyint(1) NOT NULL default '0',
  `registered_create_mod_gal` tinyint(1) NOT NULL default '1',
  `registered_del_gal` tinyint(1) NOT NULL default '0',
  `registered_vote_view` tinyint( 1 ) NOT NULL default '1',
  `registered_vote_vote` tinyint( 1 ) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;


# DROP TABLE IF EXISTS `#__rsgallery2_tags`;
CREATE TABLE IF NOT EXISTS `#__rsgallery2_tags` (
  `id`                int AUTO_INCREMENT NOT NULL,
  `name`              varchar(255) NOT NULL,
  `enabled`           tinyint(1) NOT NULL DEFAULT '0',
  `description`       varchar(255) NOT NULL,
  `date_added`        datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published`         tinyint(1) NOT NULL DEFAULT '0',
  `checked_out`       int UNSIGNED NOT NULL DEFAULT '0',
  `checked_out_time`  datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user`              tinyint NOT NULL DEFAULT '0',
  `uid`               int UNSIGNED NOT NULL DEFAULT '0',
  `ordering`          int NOT NULL DEFAULT '0',
  `params`            text NOT NULL,
  /* Keys */
  PRIMARY KEY (`id`)
) TYPE = MyISAM
  CHARACTER SET `latin1` COLLATE `latin1_swedish_ci`;
  
  


# DROP TABLE IF EXISTS `#__rsgallery2_tagmatch`;
CREATE TABLE IF NOT EXISTS `#__rsgallery2_tagmatch` (
  `match_id`         int AUTO_INCREMENT NOT NULL,
  `image_id`         int NOT NULL DEFAULT '0',
  `tag_id`           int NOT NULL DEFAULT '0',
  `date_match_made`  datetime,
  /* Keys */
  PRIMARY KEY (`match_id`)
) TYPE = MyISAM
  CHARACTER SET `latin1` COLLATE `latin1_swedish_ci`;