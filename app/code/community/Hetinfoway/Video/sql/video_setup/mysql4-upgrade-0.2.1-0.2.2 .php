<?php
/*
$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('hetvideo')};
ALTER TABLE {$this->getTable('hetvideo')} ADD `category` int(11) NOT NULL;

    ");
	
	
$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('category')};
CREATE TABLE {$this->getTable('category')} (
  `category_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `filename` varchar(255) NOT NULL default '',
  `content` text NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

	
$installer->endSetup(); */
