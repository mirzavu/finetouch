<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
create table  IF NOT EXISTS hetvideo(hetvideo_id int not null auto_increment, 
vname varchar(255),
vurl varchar(255),
vcode varchar(255),
vcontent text,
vmeta_keywords varchar(255),
vmeta_description varchar(255),
vfeatured smallint(2),
status smallint(6),
vsort_order smallint(6),
vcreated_time datetime,
vupdate_time datetime,
primary key(hetvideo_id));
SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 
