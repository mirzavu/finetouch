<?php 
class Ecomwise_B2BOrderlist_Block_Filters_GridCatalogCustomer extends Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Text{
	
	public function getCondition(){
		if (is_null($this->getValue())) {
			return null;
		}
		
		$collection = Mage::Registry("catalogrule_collection");
		
		
		
		if($collection){
			
			$collection->addFieldToFilter("email", array("eq"=>$this->getValue()));
			//Zend_Debug::dump($collection->getSelect()->__toString()); die();
		}
		return null;
	}
	
} 