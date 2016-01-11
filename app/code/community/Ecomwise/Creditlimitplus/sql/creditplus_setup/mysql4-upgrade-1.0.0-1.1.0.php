<?php
$installer = $this;
$installer->startSetup();
$installer->run("
				CREATE TABLE IF NOT EXISTS `{$this->getTable('ecomwise_credit_limit_customers')}` ( 
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT, 
				`rule_id` int(10),  		
				`customer_id` int(10),				
				PRIMARY KEY (`id`)				
				) ENGINE=InnoDB DEFAULT CHARSET=utf8
		     ");
$installer->endSetup();