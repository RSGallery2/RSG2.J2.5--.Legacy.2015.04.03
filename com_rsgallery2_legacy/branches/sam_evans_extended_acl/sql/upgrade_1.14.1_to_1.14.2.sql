# adds ACL fields for all default Joomla user classes.

ALTER TABLE `#__rsgallery2_acl` ADD `author_view` tinyint(1) NOT NULL default '1';
ALTER TABLE `#__rsgallery2_acl` ADD `author_up_mod_img` tinyint(1) NOT NULL default '1';
ALTER TABLE `#__rsgallery2_acl` ADD `author_del_img` tinyint(1) NOT NULL default '0';
ALTER TABLE `#__rsgallery2_acl` ADD `author_create_mod_gal` tinyint(1) NOT NULL default '1';
ALTER TABLE `#__rsgallery2_acl` ADD `author_del_gal` tinyint(1) NOT NULL default '0';
ALTER TABLE `#__rsgallery2_acl` ADD `author_vote_view` tinyint(1) NOT NULL default '1';
ALTER TABLE `#__rsgallery2_acl` ADD `author_vote_vote` tinyint(1) NOT NULL default '1';

ALTER TABLE `#__rsgallery2_acl` ADD `editor_view` tinyint(1) NOT NULL default '1';
ALTER TABLE `#__rsgallery2_acl` ADD `editor_up_mod_img` tinyint(1) NOT NULL default '1';
ALTER TABLE `#__rsgallery2_acl` ADD `editor_del_img` tinyint(1) NOT NULL default '0';
ALTER TABLE `#__rsgallery2_acl` ADD `editor_create_mod_gal` tinyint(1) NOT NULL default '1';
ALTER TABLE `#__rsgallery2_acl` ADD `editor_del_gal` tinyint(1) NOT NULL default '0';
ALTER TABLE `#__rsgallery2_acl` ADD `editor_vote_view` tinyint(1) NOT NULL default '1';
ALTER TABLE `#__rsgallery2_acl` ADD `editor_vote_vote` tinyint(1) NOT NULL default '1';

ALTER TABLE `#__rsgallery2_acl` ADD `publisher_view` tinyint(1) NOT NULL default '1';
ALTER TABLE `#__rsgallery2_acl` ADD `publisher_up_mod_img` tinyint(1) NOT NULL default '1';
ALTER TABLE `#__rsgallery2_acl` ADD `publisher_del_img` tinyint(1) NOT NULL default '0';
ALTER TABLE `#__rsgallery2_acl` ADD `publisher_create_mod_gal` tinyint(1) NOT NULL default '1';
ALTER TABLE `#__rsgallery2_acl` ADD `publisher_del_gal` tinyint(1) NOT NULL default '0';
ALTER TABLE `#__rsgallery2_acl` ADD `publisher_vote_view` tinyint(1) NOT NULL default '1';
ALTER TABLE `#__rsgallery2_acl` ADD `publisher_vote_vote` tinyint(1) NOT NULL default '1';

ALTER TABLE `#__rsgallery2_acl` ADD `manager_view` tinyint(1) NOT NULL default '1';
ALTER TABLE `#__rsgallery2_acl` ADD `manager_up_mod_img` tinyint(1) NOT NULL default '1';
ALTER TABLE `#__rsgallery2_acl` ADD `manager_del_img` tinyint(1) NOT NULL default '1';
ALTER TABLE `#__rsgallery2_acl` ADD `manager_create_mod_gal` tinyint(1) NOT NULL default '1';
ALTER TABLE `#__rsgallery2_acl` ADD `manager_del_gal` tinyint(1) NOT NULL default '1';
ALTER TABLE `#__rsgallery2_acl` ADD `manager_vote_view` tinyint(1) NOT NULL default '1';
ALTER TABLE `#__rsgallery2_acl` ADD `manager_vote_vote` tinyint(1) NOT NULL default '1';
