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
	
DROP TABLE IF EXISTS {$this->getTable('atlas_bannerslider')};

CREATE TABLE {$this->getTable('atlas_bannerslider')} (
  `bannerslider_id` int(11) unsigned NOT NULL auto_increment,
  `store_id` varchar(50) NOT NULL default '0',
  `status` BOOL  NOT NULL,
  `img` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `added_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL,
  UNIQUE (`bannerslider_id`, `url`),
  PRIMARY KEY (`bannerslider_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT  INTO {$this->getTable('atlas_bannerslider')} (`store_id`,`status`,`img`,`title`,`url`,`added_date`,`updated_date`)
 values ('0','1','atlas_banner/Jellyfish.jpg','jellyfish','jellyfish','','');
 
INSERT  INTO {$this->getTable('atlas_bannerslider')} (`store_id`,`status`,`img`,`title`,`url`,`added_date`,`updated_date`)
 values ('0','1','atlas_banner/Tulips.jpg','Tulips','Tulips','','');
 
INSERT  INTO {$this->getTable('atlas_bannerslider')} (`store_id`,`status`,`img`,`title`,`url`,`added_date`,`updated_date`)
 values ('0','1','atlas_banner/Desert.jpg','Desert','Desert','','');
 
 ");

$installer->endSetup();
?>