<?php
class Ecomwise_Creditlimitplus_Model_Payment_Payonaccount extends Mage_Payment_Model_Method_Abstract
{
	protected $_code = 'payonaccountplus';	
	protected $_formBlockType = 'ecomwisecreditplus/payment_form_payonaccount';
	protected $_infoBlockType = 'ecomwisecreditplus/payment_info_payonaccount';	
	
	/**
	 * Method that will be executed instead of authorize or capture
	 * if flag isInitializeNeeded set to true
	 *
	 * @param string $paymentAction
	 * @param object $stateObject
	 *
	 * @return Mage_Payment_Model_Abstract
	 */
	public function initialize($paymentAction, $stateObject)
	{
		if(($status = $this->getConfigData('order_status'))) {
			$stateObject->setStatus($status);
			$state = $this->_getAssignedState($status);
			$stateObject->setState($state);
			$stateObject->setIsNotified(true);
		}
		return $this;
	}
	
	/**
	 * Get the assigned state of an order status
	 *
	 * @param string order_status
	 */
	protected function _getAssignedState($status)
	{
		$item = Mage::getResourceModel('sales/order_status_collection')
		->joinStates()
		->addFieldToFilter('main_table.status', $status)
		->getFirstItem();
	
		return $item->getState();
	}
	
	/**
	 * Check whether payment method can be used
	 *
	 * TODO: payment method instance is not supposed to know about quote
	 *
	 * @param Mage_Sales_Model_Quote|null $quote
	 *
	 * @return bool
	 */
	public function isAvailable($quote = null)
	{
		//if(!Mage::getSingleton('checkout/session')->getQuote()->getCustomer()->getEmail() || !Mage::getStoreConfig('creditlimitplus/parameters/enabled')){
		if(($quote &&  !$quote->getCustomer()->getEmail()) || !Mage::getStoreConfig('creditlimitplus/parameters/enabled')){		
			return false;
		}
		
		$block = Mage::app()->getLayout()->getBlockSingleton('ecomwisecreditplus/payment_form_payonaccount');		
		$rules_collection = $block->getCreditRules();
		if(count($rules_collection) == 0){
			return false;
		}
		
		return parent::isAvailable($quote);	
	}
			
	public function assignData($data)
	{
		if (!($data instanceof Varien_Object)) {
			$data = new Varien_Object($data);
		}
		$info = $this->getInfoInstance();
		$info->setPayonaccountplusOption($data->getPayonaccountplusOption());	
		return $this;
	}
	
	public function validate()
	{		
		parent::validate();		 
		$quote = Mage::getSingleton('checkout/session')->getQuote();	
		$customer = $quote->getCustomer();	
		$errorMsg = false;
		$key = "creditlimitplus_ordersave_" . $quote->getId() . "_" . $customer->getId();		
		
		if($customer){				
			$info = $this->getInfoInstance();			
			$rule_id = $info->getPayonaccountplusOption();
			if(empty($rule_id)){
				$errorCode = 'invalid_data';
				$errorMsg = Mage::helper('ecomwisecreditplus')->__('Please select one of the credit lmits');
			} else {				
				$customer_id = $customer->getId();				 
				$customer_model = Mage::getModel('ecomwisecreditplus/customers')
									->getCollection()
									->addFieldToFilter('rule_id', $rule_id)
									->addFieldToFilter('customer_id', $customer_id)
									->addFieldToSelect( 'amount' );				
				$limit_model = Mage::getModel('ecomwisecreditplus/limits')->getCollection()
										->addFieldToFilter('id', $rule_id)
										->addFieldToSelect( 'amount' );				
				$amount = $customer_model->getFirstItem()->getAmount()  + $quote->getBaseGrandTotal();
				$allowed_amount = $limit_model->getFirstItem()->getAmount();				
				if(($amount > $allowed_amount) && is_null(Mage::registry($key))){
					$errorMsg = Mage::helper('ecomwisecreditplus')->__('You can not buy on credit because your maximum credit limit is reached. For more information please contact us.');
				}
			}				
		}else {
			$errorMsg = Mage::helper('ecomwisecreditplus')->__('Only loged in customers can use this payment method');
		}		
		if( $errorMsg ){
			Mage::throwException($errorMsg);
		}
		return $this;		 
	}    
}