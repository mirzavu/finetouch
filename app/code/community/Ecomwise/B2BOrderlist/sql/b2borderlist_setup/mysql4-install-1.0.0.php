<?php

$installer = $this;

$installer->startSetup();

//$installer->run("ALTER TABLE {$this->getTable('customer_group')} ADD `hide_prices_for_store` VARCHAR(255) DEFAULT NULL;");

//category permissions setup
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');


$resource = Mage::getSingleton('core/resource');
$cr = Mage::getSingleton('core/resource')->getConnection('core_read');
$cw = Mage::getSingleton('core/resource')->getConnection('core_write');


$entityTypeId     = $setup->getEntityTypeId('catalog_category');
$attributeSetId   = $setup->getDefaultAttributeSetId($entityTypeId);


$gruoptablerow = $cr->fetchRow("SELECT attribute_group_id FROM ".$resource->getTableName('eav_attribute_group')." WHERE  attribute_set_id  = ".$attributeSetId." AND attribute_group_name = 'General Information'");

if($gruoptablerow != null){
	
	$attributeGroupId = $gruoptablerow['attribute_group_id'];
}else{
	$attributeGroupId = $setup->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);
}


$setup->addAttribute('catalog_category', 'allowed_customer_group_b2b', array(
		'label'         => 'Allow Category for customer group',
		'type'          => 'text',
		'input'         => 'multiselect',
		'backend'           => 'eav/entity_attribute_backend_array',
		'source'            => 'b2borderlist/source_categorypermissions',
		'input_renderer'    => 'b2borderlist/renderers_customergroups',
		'sort_order'        => 40,
		'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
		'visible'           => true,
		'required'          => false,
		'user_defined'      => false,
		'searchable'        => false,
		'filterable'        => false,
		'comparable'        => false,
		'visible_on_front'  => false,
		'visible_in_advanced_search' => false,
		'unique'            => false
));



$setup->addAttributeToGroup(
		$entityTypeId,
		$attributeSetId,
		$attributeGroupId,
		'allowed_customer_group_b2b',
		'999'  //sort_order
);

$customer_group = Mage::getModel('customer/group');
$allGroups  = $customer_group->getCollection();
$str='';
foreach ($allGroups as $gr){
	$str=$str.$gr->getCustomerGroupId().',';
}
$s=substr_replace($str ,"",-1);

Mage::getModel('core/config')->saveConfig('b2borderlist/parameters/groups', $s);

$attribute = Mage::getModel('eav/entity_attribute')->loadByCode('catalog_category','allowed_customer_group_b2b');


$table = $resource->getTableName('catalog_category_entity_text');

$query=$cw->fetchRow('Select `value_id` from `'.$table.'` order by `value_id` desc limit 1');
$id=$query['value_id'];
$cats=Mage::getModel('catalog/category')->getCollection();


/*
foreach($cats as $cat){
	$catId=$cat->load()->getId();
	$id=$id+1;
	$q1 = $cw->query('INSERT INTO '.$table.' values ('.$id.','.$entityTypeId.','.$attribute->getId().',0,'.$catId.',"ALL")');
}*/

$cmsPageData = array(
		'title' => 'Not Allowed',
		'root_template' => 'one_column',
		'identifier' => 'ecomwise_not_allowed',
		'stores' => array(0),//available for all store views
		'content' => "Not allowed!"
);

if(!Mage::getModel('cms/page')->load('ecomwise_not_allowed', 'identifier')->getId())
{
	Mage::getModel('cms/page')->setData($cmsPageData)->save();
}


 $installer->run("
 		DROP TABLE IF EXISTS {$this->getTable('ecomwise_b2b_customerrule')};
 		CREATE TABLE {$this->getTable('ecomwise_b2b_customerrule')} (
 		`rule_id` int(11) NOT NULL default '0',
 		`customer_id` int(11) NOT NULL default '0',
 		`email` varchar(255) NOT NULL default '0',
 		`firstname` varchar(255)  default NULL,
 		`lastname` varchar(255)  default NULL,
 		UNIQUE KEY `ecomwise_b2b_rule_customer` (`rule_id`,`customer_id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 ");


$category_collection =Mage::getModel("catalog/category");
foreach($category_collection as $category){
	$category->setData("allowed_customer_group_b2b", "ALL");
	$category->save();
	
}


$installer->endSetup();