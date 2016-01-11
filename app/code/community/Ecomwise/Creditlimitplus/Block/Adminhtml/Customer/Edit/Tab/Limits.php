<?php
class Ecomwise_Creditlimitplus_Block_Adminhtml_Customer_Edit_Tab_Limits
extends Mage_Adminhtml_Block_Widget_Form
implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
	/**
	 * Prepare content for tab
	 *
	 * @return string
	 */
	public function getTabLabel() {
		return Mage::helper('ecomwisecreditplus')->__('Credit Limits Rules');
	}
	
	/**
	 * Prepare title for tab
	 *
	 * @return string
	 */
	public function getTabTitle() {
		return Mage::helper('ecomwisecreditplus')->__('Credit Limits Rules');
	}
	
	/**
	 * Returns status flag about this tab can be showed or not
	 *
	 * @return true
	 */
	public function canShowTab() {
		if (Mage::registry('current_customer')->getId()) {
            return true;
        }
        return false;
	}
	
	/**
	 * Returns status flag about this tab hidden or not
	 *
	 * @return true
	 */
	public function isHidden() {
		return false;
	}
	
	public function __construct() {
		parent::__construct();
	
		$customerId = $this->getRequest()->getParam('id', false);			
		$this->setTemplate('creditplus/customer/edit/tab/rules.phtml');
	}
	
	protected function _prepareLayout() {
		$grid_block = $this->getLayout()->createBlock('ecomwisecreditplus/adminhtml_customer_edit_tab_grid_rules', 'creditLimits');		
		$this->setChild('creditLimits', $grid_block);		
		
		$serializer = $this->getLayout()->createBlock('adminhtml/widget_grid_serializer', 'credit_rules_grid_serializer');
		$serializer->initSerializerBlock($grid_block, 'getSelected', 'credit_rules_ids', 'credit_rules' );		
		$this->setChild('credit_rules_grid_serializer', $serializer);		
		
		return parent::_prepareLayout();
	}	
	
	protected function _getGridHtml() {
		return $this->getChildHtml('creditLimits');
	}	
	
	protected function _getGridSerializer() {		
		if(!$this->getRequest()->getParam('isAjax', false)){		
			return $this->getChildHtml('credit_rules_grid_serializer');		
		}else {
			return '';
		}	
	}
	
	protected function _getJsObjectName() {
		return $this->getChild('userGrid')->getJsObjectName();
	}	
}