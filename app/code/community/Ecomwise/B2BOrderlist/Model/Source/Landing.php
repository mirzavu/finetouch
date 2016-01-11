<?php 
class Ecomwise_B2BOrderlist_Model_Source_Landing 
{	
	public function toOptionArray()
	{		
		$ret = array();		
		
		$categories = Mage::getModel('catalog/category')
		->getCollection()
		->addAttributeToSelect('*')		
		->addAttributeToFilter( 'parent_id',  array('neq' => 1  )   )		
		->addIsActiveFilter();		
		
		$ret[] = array('value'=>0, 'label'=>Mage::helper('b2borderlist')->__('Select Landing Page'));		
		$group1 = array();		
		foreach ($categories as $cat){			
			$cat_url = Mage::getModel("catalog/category")->load($cat->getId())->getUrlPath();		
			$group1[] =  array('value'=>$cat_url . '?mode=list', 'label'=>$cat->getName());				
		}		
		$ret[] = array('value'=>$group1, 'label'=>Mage::helper('b2borderlist')->__('Categories'));
		
		$cms_pages = Mage::getModel('cms/page')->getCollection();		
		$group2 = array();		
		foreach ($cms_pages as $page){			
			//$page_url = Mage::helper('cms/page')->getPageUrl( $page->getPageId() );			
			$group2[] =  array('value'=>$page->getIdentifier(), 'label'=>$page->getTitle() );
		}		
		$ret[] = array('value'=>$group2, 'label'=>Mage::helper('b2borderlist')->__('Cme Pages') );	
		
		return $ret;			
	}	
	
}