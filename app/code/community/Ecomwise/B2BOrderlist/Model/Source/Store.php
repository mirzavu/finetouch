<?php 
class Ecomwise_B2BOrderList_Model_Source_Store{
	
	public function toOptionArray()
	{
	
			$options = Mage::getResourceModel('core/store_collection')->load()->toOptionArray();
			array_unshift($options, array("value"=> "-1", "label"=> ""));
	        //Zend_Debug::dump($options); die();
			return $options;
	}
	
} 