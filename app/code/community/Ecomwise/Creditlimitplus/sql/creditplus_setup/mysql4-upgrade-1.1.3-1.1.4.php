<?php
$installer = $this;
$installer->startSetup();
$installer->run("
				 ALTER TABLE `{$this->getTable('ecomwise_credit_limit')}` ADD `logo` varchar(255);  
		     ");
$installer->endSetup();  