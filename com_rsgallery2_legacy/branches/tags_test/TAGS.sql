
-- Creates tables for tag matching.  For test branch

-- Table: jos_rsgallery2_tags

-- DROP TABLE IF EXISTS `jos_rsgallery2_tags`;

CREATE TABLE `jos_rsgallery2_tags` (
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
) ENGINE = MyISAM
  CHARACTER SET `latin1` COLLATE `latin1_swedish_ci`;
  
  
-- Table: jos_rsgallery2_tagmatch

-- DROP TABLE IF EXISTS `jos_rsgallery2_tagmatch`;

CREATE TABLE `jos_rsgallery2_tagmatch` (
  `match_id`         int AUTO_INCREMENT NOT NULL,
  `image_id`         int NOT NULL DEFAULT '0',
  `tag_id`           int NOT NULL DEFAULT '0',
  `date_match_made`  datetime,
  /* Keys */
  PRIMARY KEY (`match_id`)
) ENGINE = MyISAM
  CHARACTER SET `latin1` COLLATE `latin1_swedish_ci`;


  
  