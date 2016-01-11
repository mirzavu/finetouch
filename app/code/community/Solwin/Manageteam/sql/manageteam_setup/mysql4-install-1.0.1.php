<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
create table manageteam(manageteam_id int not null auto_increment, name varchar(100),
image varchar(255),
designation varchar(255),
description varchar(255),
status int(11),
facebookurl varchar(255),
googleurl varchar(255),
twitterurl varchar(255),
email varchar(255),
primary key(manageteam_id));
		
SQLTEXT;

$installer->run($sql);
$installer->endSetup();
	 