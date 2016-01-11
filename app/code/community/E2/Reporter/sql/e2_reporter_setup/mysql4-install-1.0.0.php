<?php

$installer = $this;

$installer->startSetup();

$installer->run("

	CREATE TABLE IF NOT EXISTS `{$this->getTable('e2_reporter_reports')}` (
	  `report_id` int(10) unsigned NOT NULL auto_increment,
	  `report_code` varchar(25),
	  `created_at` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	  `file_created_at` timestamp,
	  `content` text,
	  `error_message` text,
	  `hash` varchar(50),
	  PRIMARY KEY  (`report_id`)
	  
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();
