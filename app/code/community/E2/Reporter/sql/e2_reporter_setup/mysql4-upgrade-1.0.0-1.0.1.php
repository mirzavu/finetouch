<?php
$installer = $this;

$installer->startSetup();
$installer->getConnection()->modifyColumn($installer->getTable('e2_reporter_reports'), 'file_created_at', 'datetime NOT NULL');
$installer->endSetup();
