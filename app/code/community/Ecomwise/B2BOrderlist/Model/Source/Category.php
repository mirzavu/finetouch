<?php 
class Ecomwise_B2BOrderlist_Model_Source_Category /* extends Mage_Eav_Model_Entity_Attribute_Source_Abstract  */{
	
	public function toOptionArray($addEmpty = true)
	{
		$tree = Mage::getResourceModel('catalog/category_tree');	
		$collection = Mage::getResourceModel('catalog/category_collection');
	
		$collection->addAttributeToSelect('name')
		->addRootLevelFilter()
		->load();
	
		$options = array();
	
		if ($addEmpty) {
			$options[] = array(
					'label' => Mage::helper('adminhtml')->__('-- Please Select a Category --'),
					'value' => ''
			);
		}
		foreach ($collection as $category) {
			$options[] = array(
					'label' => $category->getName(),
					'value' => $category->getId()
			);
			$level = '';			
			$this->getChildren($category, $level, $options );			
		}					
		
		return $options;
	}	
	
	private function getChildren($category, $level, &$options){		
		
		//while ($subcats = $category->getChildren()){
		$subcats = $category->getChildren();
		if($subcats){
			$level .= '- ';
			foreach(explode(',',$subcats) as $subCatid)
			{
				$category = Mage::getModel('catalog/category')->load($subCatid);
				$options[] = array(
						'label' => $level . $category->getName(),
						'value' => $category->getId()
				);				
				 $this->getChildren($category, $level, $options);
			}		
		}		
		return $options;		
	}		
}