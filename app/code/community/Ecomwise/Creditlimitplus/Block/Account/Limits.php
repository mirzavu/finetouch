<?php
class Ecomwise_Creditlimitplus_Block_Account_Limits extends Mage_Core_Block_Template{

	public function getCustomer(){		
		return  Mage::getSingleton('customer/session')->getCustomer();	
	}
	
	public function getCreditRules(){
		 
		$rules_collection = null;
		$customer = $this->getCustomer();
		if($customer && $customer->getGroupId() ){
			$customer_group_id = $customer->getGroupId();			
			$rule_array =  $this->_getRulesByCustomer(); //array (9,6);
			$date = Mage::getModel('core/date')->Date();
			$rules_collection = Mage::getModel('ecomwisecreditplus/limits')->getCollection()
			->addFieldToFilter(
					array( 'customer_groups', 'id' ),
					array( array('finset'=> $customer_group_id ), $rule_array )					
			)	
			->addFieldToFilter(
					'status', array('eq'=> 1 )
			)
			->setOrder('amount','DESC')	;
		}
		return $rules_collection;
	}
	
	protected function _getRulesByCustomer(){
				 
		$customer_id = $this->getCustomer()->getId();
		$rule_array = null;
		 
		$rules_collection = Mage::getModel('ecomwisecreditplus/limits_customers')->getCollection()
		->addFieldToFilter("customer_id",  array('eq'=> $customer_id ) )
		->addFieldToSelect('rule_id');
		 
		if( count($rules_collection) > 0 ){
			$rule_array = array();
			foreach ($rules_collection as $rule) {
				$rule_array[] = $rule->getRuleId();
			}
		}
		 
		return $rule_array;
	}
	
	public function getUsedCredit( $rule_id ){		
		
		$customer = $this->getCustomer();		
		$val = 0;
		if($customer && $customer->getId()){		
			$rules_collection = Mage::getModel('ecomwisecreditplus/customers')->getCollection()
									->addFieldToFilter("rule_id",  array('eq'=> $rule_id ) )
									->addFieldToFilter("customer_id",  array('eq'=> $customer->getId() ) );					
			$val = $rules_collection->getFirstItem()->getAmount();
			$val = ($val)?$val:0;		
		}		
		return $val;
	}
	
}