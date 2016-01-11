<?php

/**
 * Atlas Extensions
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Atlas Commercial License
 * that is available through the world-wide-web at this URL:
 *
 * @copyright   Copyright (c) 2015 Atlas Extensions
 * @license     Commercial
 */
$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE {$this->getTable('atlas_bannerslider')} ADD `sort` int(10) NOT NULL AFTER `url`;
UPDATE {$this->getTable('atlas_bannerslider')} SET `sort` = '1' WHERE `bannerslider_id` =1;
UPDATE {$this->getTable('atlas_bannerslider')} SET `sort` = '2' WHERE `bannerslider_id` =2;
UPDATE {$this->getTable('atlas_bannerslider')} SET `sort` = '3' WHERE `bannerslider_id` =3;

DROP TABLE IF EXISTS {$this->getTable('atlas_bannerslidergroup')};

CREATE TABLE {$this->getTable('atlas_bannerslidergroup')} (
  `bannerslidergroup_id` int(11) unsigned NOT NULL auto_increment,
  `store_id` varchar(50) NOT NULL default '0',
  `status` BOOL  NOT NULL,
  `name` varchar(255) NOT NULL default '',
  `added_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL,
  UNIQUE (`bannerslidergroup_id`),
  PRIMARY KEY (`bannerslidergroup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT  INTO {$this->getTable('atlas_bannerslidergroup')} (`store_id`,`status`,`name`,`added_date`,`updated_date`)
 values ('0','1','group1','','');
 
INSERT  INTO {$this->getTable('atlas_bannerslidergroup')} (`store_id`,`status`,`name`,`added_date`,`updated_date`)
 values ('0','1','group2','','');
 
INSERT  INTO {$this->getTable('atlas_bannerslidergroup')} (`store_id`,`status`,`name`,`added_date`,`updated_date`)
 values ('0','1','group3','','');

DROP TABLE IF EXISTS {$this->getTable('atlas_bannerslidergroup_relation')};

CREATE TABLE {$this->getTable('atlas_bannerslidergroup_relation')} (
  `bannerslidergroup_relation_id` int(11) unsigned NOT NULL auto_increment,
  `banner_id` int(11) NOT NULL default '0',
  `group_id` int(11)  NOT NULL default '0',
  `added_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL,
  UNIQUE (`bannerslidergroup_relation_id`),
  PRIMARY KEY (`bannerslidergroup_relation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8; 

INSERT  INTO {$this->getTable('atlas_bannerslidergroup_relation')} (`banner_id`,`group_id`,`added_date`,`updated_date`)
 values ('1','1','','');
INSERT  INTO {$this->getTable('atlas_bannerslidergroup_relation')} (`banner_id`,`group_id`,`added_date`,`updated_date`)
 values ('2','1','','');
INSERT  INTO {$this->getTable('atlas_bannerslidergroup_relation')} (`banner_id`,`group_id`,`added_date`,`updated_date`)
 values ('3','1','','');

INSERT  INTO {$this->getTable('atlas_bannerslidergroup_relation')} (`banner_id`,`group_id`,`added_date`,`updated_date`)
 values ('1','2','','');
INSERT  INTO {$this->getTable('atlas_bannerslidergroup_relation')} (`banner_id`,`group_id`,`added_date`,`updated_date`)
 values ('2','2','','');
INSERT  INTO {$this->getTable('atlas_bannerslidergroup_relation')} (`banner_id`,`group_id`,`added_date`,`updated_date`)
 values ('3','2','','');
 
INSERT  INTO {$this->getTable('atlas_bannerslidergroup_relation')} (`banner_id`,`group_id`,`added_date`,`updated_date`)
 values ('1','3','','');
INSERT  INTO {$this->getTable('atlas_bannerslidergroup_relation')} (`banner_id`,`group_id`,`added_date`,`updated_date`)
 values ('2','3','','');
INSERT  INTO {$this->getTable('atlas_bannerslidergroup_relation')} (`banner_id`,`group_id`,`added_date`,`updated_date`)
 values ('3','3','','');

 ");

$installer->endSetup();
?>