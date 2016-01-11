<?php 
$installer = $this;

$installer->startSetup();

$attributeModel = Mage::getModel('eav/entity_attribute')->loadByCode("catalog_category", "allowed_customer_group_b2b");
if($attributeModel->getId()){
	$attId = $attributeModel->getId();
	$installer->run("UPDATE  {$this->getTable('catalog/eav_attribute')} SET frontend_input_renderer='b2borderlist/renderers_customergroups' WHERE attribute_id = ".$attId.";");
}

$installer->endSetup();