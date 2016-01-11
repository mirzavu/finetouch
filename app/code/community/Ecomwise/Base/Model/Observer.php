<?php 
class Ecomwise_Base_Model_Observer extends Varien_Object{
			
	public function uninstall($observer){
		
		$allowedcustomer=Mage::getConfig()->getModuleConfig('Ecomwise_B2BOrderlist')->is('active', 'true');
		$attribute = Mage::getModel('eav/entity_attribute')->loadByCode('catalog_category','allowed_customer_group_b2b');
		$resource = Mage::getSingleton('core/resource');
		$attId = $attribute->getId();
		$cr = Mage::getSingleton('core/resource')->getConnection('core_read');
		$cw = Mage::getSingleton('core/resource')->getConnection('core_write');
		
		if(!$allowedcustomer) {
			
			//remove attribute from set
			
			$table = $resource->getTableName('eav_entity_attribute');		
			$q1 = $cw->query('DELETE FROM '.$table.' WHERE attribute_id = '.$attribute->getId().' ');
			
			//remove cms page
			Mage::getModel('cms/page')->load('ecomwise_not_allowed')->delete();
		}
		
		if($allowedcustomer) {
			
			$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
			
			$entityTypeId     = $setup->getEntityTypeId('catalog_category');
			$attributeSetId   = $setup->getDefaultAttributeSetId($entityTypeId);	
			
			$gruoptablerow = $cr->fetchRow("SELECT attribute_group_id FROM ".$resource->getTableName('eav_attribute_group')." WHERE  attribute_set_id  = ".$attributeSetId." AND attribute_group_name = 'General Information'");
		
			if($gruoptablerow != null){				
				$attributeGroupId = $gruoptablerow['attribute_group_id'];
			}else{
				$attributeGroupId = $setup->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);
			}
		
			$setup->addAttributeToGroup(
					$entityTypeId,
					$attributeSetId,
					$attributeGroupId,
					'allowed_customer_group_b2b',
					'999'  //sort_order
			);			
		}
	}
}

