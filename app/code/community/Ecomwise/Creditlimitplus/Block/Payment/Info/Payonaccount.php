<?php
class Ecomwise_Creditlimitplus_Block_Payment_Info_Payonaccount extends Mage_Payment_Block_Info
{	
	/* protected function _construct(){
		parent::_construct();
		$this->setTemplate('ecomwisecreditplus\payment\info\default.phtml');
	}  */	
	
	protected function _prepareSpecificInformation($transport = null)
	{
		if (null !== $this->_paymentSpecificInformation) {
			return $this->_paymentSpecificInformation;
		}		
		
		$info = $this->getInfo();
		$rule_id = $info->getPayonaccountplusOption();		
		$rule = Mage::getModel('ecomwisecreditplus/limits')->load( $rule_id );		
		$transport = new Varien_Object();
		$transport = parent::_prepareSpecificInformation($transport);
		$transport->addData(array(
				 Mage::helper('ecomwisecreditplus')->__('Credit Limit') => $rule->getName()
		));
		return $transport;
	}	
}  
