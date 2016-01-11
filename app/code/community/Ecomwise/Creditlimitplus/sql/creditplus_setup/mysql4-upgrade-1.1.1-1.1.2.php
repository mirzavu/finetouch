<?php
$installer = $this;
$installer->startSetup();
$installer->run("
				CREATE TABLE IF NOT EXISTS `{$this->getTable('ecomwise_credit_customers')}` ( 
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT, 
				`rule_id` int(10),  		
				`customer_id` int(10),	
				`amount` decimal(12,4),
				`used_at` timestamp DEFAULT NOW(),			
				PRIMARY KEY (`id`)				
				) ENGINE=InnoDB DEFAULT CHARSET=utf8
		     ");
$installer->endSetup();