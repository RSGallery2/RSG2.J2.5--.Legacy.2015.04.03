SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for jos_rsgallery2_tags
-- ----------------------------


drop table jos_rsgallery2_tags;

CREATE TABLE `jos_rsgallery2_tags` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `enabled` tinyint(1) NOT NULL default '0',
  `description` varchar(255) NOT NULL default '',
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `user` tinyint(4) NOT NULL default '0',
  `uid` int(11) unsigned NOT NULL default '0',
  `ordering` int(11) NOT NULL default '0',
  `params` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO jos_rsgallery2_tags
   (`id`, `name`, `enabled`, `description`, `date_added`, `published`, `checked_out`, `checked_out_time`, `user`, `uid`, `ordering`, `params`)
VALUES
   (1, 'TEst1', 0, '', '7/20/2008 10:20:31', 1, 0, '0/0/0000 00:00:00', 0, 63, 1, '');

INSERT INTO jos_rsgallery2_tags
   (`id`, `name`, `enabled`, `description`, `date_added`, `published`, `checked_out`, `checked_out_time`, `user`, `uid`, `ordering`, `params`)
VALUES
   (2, 'test 2', 0, '', '0/0/0000 00:00:00', 1, 0, '0/0/0000 00:00:00', 0, 0, 3, '');

INSERT INTO jos_rsgallery2_tags
   (`id`, `name`, `enabled`, `description`, `date_added`, `published`, `checked_out`, `checked_out_time`, `user`, `uid`, `ordering`, `params`)
VALUES
   (4, 'Test 3', 0, '', '7/20/2008 10:19:08', 1, 0, '0/0/0000 00:00:00', 0, 63, 2, '');

