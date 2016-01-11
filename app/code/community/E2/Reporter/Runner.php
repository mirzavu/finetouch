<?php

// initialize Magento environment
require_once "app/Mage.php";
Mage::app();

$cron = Mage::getModel('e2_reporter/cron');
$cron->debugflag = true;

$cron->run();
