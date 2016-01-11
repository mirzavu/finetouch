<?php 
class Ecomwise_B2BOrderlist_Model_Source_CategoryPermissions extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
	public function getAllOptions()
    {
        if (!$this->_options) {
        	
        	$this->_options[] = array(
        			'label' => Mage::helper("core")->__(""),
        			'value' => "NONE",
        			'selected' => false,
        	);
        	
        	$this->_options[] = array(
        			'label' => Mage::helper("core")->__("All"),
        			'value' => "ALL",
        			'selected' => true,
        	);
        	
        	$customer_group = Mage::getModel('customer/group');
			$allGroups  = $customer_group->getCollection();
			foreach ($allGroups as $gr){
				$this->_options[] = array(
					 'label' => $gr->getCustomerGroupCode(),
            	     'value' => $gr->getCustomerGroupCode(),
					 'selected' => false,
				 );
			}
			
        }
        return $this->_options;
    }
}