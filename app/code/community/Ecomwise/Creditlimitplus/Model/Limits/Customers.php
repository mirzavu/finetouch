<?php
class Ecomwise_Creditlimitplus_Model_Limits_Customers extends Mage_Core_Model_Abstract {
	
	protected function _construct(){
		$this->_init('ecomwisecreditplus/limits_customers');
	}
	
	public function deleteByRuleId($rule_id){
		return $this->getResource()->deleteByRuleId($rule_id);
	}	
	
	 public function deleteByCustomerId($customer_id) {
	 	return $this->getResource()->deleteByCustomerId($customer_id);
	 }
	 
}