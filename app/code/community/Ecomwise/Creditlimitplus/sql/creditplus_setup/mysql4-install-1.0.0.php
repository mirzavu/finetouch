<?php
$installer = $this;
$installer->startSetup();
$installer->run("
				CREATE TABLE IF NOT EXISTS `{$this->getTable('ecomwise_credit_limit')}` ( 
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT, 
				`name` varchar(255), 
				`amount` decimal(12,4),
				`from_date` timestamp NULL DEFAULT NULL,
				`to_date` timestamp NULL DEFAULT NULL,  
				`status` smallint(6), 
				`customer_groups` text,								
				PRIMARY KEY (`id`)				
				) ENGINE=InnoDB DEFAULT CHARSET=utf8
		     ");
$installer->endSetup();