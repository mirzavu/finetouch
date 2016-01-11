<?php
class Ecomwise_Creditlimitplus_Block_Credit_Rules_Grid_Renderers_Active extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {
		
	public function render(Varien_Object $row)
    {    	
    	$status = ($row->getData($this->getColumn()->getIndex())) ? Mage::helper('ecomwisecreditplus')->__('Active') : Mage::helper('ecomwisecreditplus')->__('Inactive');    	
    	return $status;
    }
}	
