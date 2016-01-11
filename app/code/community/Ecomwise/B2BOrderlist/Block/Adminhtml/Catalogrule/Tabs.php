<?php

class Ecomwise_B2BOrderlist_Block_Adminhtml_Catalogrule_Tabs extends Mage_Adminhtml_Block_Promo_Catalog_Edit_Tabs{

	protected function _prepareLayout(){
		parent::_preparelayout();
		 
		$this->addTabAfter('promocatalog_individual', array(
				'label'     => Mage::helper('catalog')->__('Individual Customers'),
				'url'       => $this->getUrl('adminhtml/customerrule/individual', array('_current' => true)),
				'class'     => 'ajax',
		), "actions_section");
						
		return $this;
	}
}
