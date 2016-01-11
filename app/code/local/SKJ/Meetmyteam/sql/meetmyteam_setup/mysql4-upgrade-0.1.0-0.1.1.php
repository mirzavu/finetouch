<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("

		-- DROP TABLE IF EXISTS meetmyteam_cat;
		CREATE TABLE meetmyteam_cat (
		`meetmyteam_cat_id` int(11) unsigned NOT NULL auto_increment,
		`title` varchar(255) NOT NULL default '',
		`description` text NOT NULL default '',
		`status` smallint(6) NOT NULL default '0',
		`created_time` datetime NULL,
		`update_time` datetime NULL,
		PRIMARY KEY (`meetmyteam_cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

		");


$installer->endSetup();
