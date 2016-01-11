<?php 
class Ecomwise_B2BOrderlist_Model_Source_Customergroup
{	
	public function toOptionArray()
	{		
		$customer_groups = Mage::getModel('customer/group')->getCollection();	
		$ret = array();	
		$ret[] = array('value'=>-1, 'label'=> '');
		foreach ($customer_groups as $group){			
			$ret[] = array('value'=>$group->getCustomerGroupId(), 'label'=> $group->getCustomerGroupCode());		
		}		
		return $ret;				
	}	
}