<?php
class Ecomwise_B2BOrderlist_Block_Page_Html_Head extends Mage_Page_Block_Html_Head
{
	protected function _construct()
	    {	    		   		
	   	 	$magentoVersion = Mage::getVersion();    	
	    	if(version_compare($magentoVersion, '1.9', '>=')){
	    		$this->setTemplate('page/html/head.phtml');
	    	}
	    	else {
	        	$this->setTemplate('b2borderlist/page/html/head.phtml');
	    	}	    	
	    }
	
}