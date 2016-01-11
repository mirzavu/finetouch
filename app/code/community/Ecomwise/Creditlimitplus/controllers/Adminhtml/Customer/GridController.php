<?php 
class Ecomwise_Creditlimitplus_Adminhtml_Customer_GridController extends Mage_Adminhtml_Controller_Action
{
	
	public function indexAction()
	{
		$this->_initAction();
		$this->renderLayout();
	}
	
	/**
	 * Action for ajax request from assigned users grid
	 *
	 */
	public function editrolegridAction()
	{
		$this->getResponse()->setBody(
				$this->getLayout()->createBlock('ecomwisecreditplus/adminhtml_customer_edit_tab_limits')->toHtml()
		);	
	}	
}