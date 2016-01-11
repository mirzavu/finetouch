 <?php
class Ecomwise_Creditlimitplus_Block_Payment_Form_Payonaccount extends Mage_Payment_Block_Form
{
	protected function _construct()  
    {  
        parent::_construct();
        $this->setTemplate('ecomwisecreditplus/payment/form/payonaccount.phtml');
    }     
    
    public function getQuote(){
    	return Mage::getSingleton('checkout/session')->getQuote();
    }
    
    public function getCreditRules(){
    	
    	$rules_collection = null;    	
    	$quote = $this->getQuote();    	
    	if($quote->getCustomer() && $quote->getCustomer()->getGroupId() ){     		
    		$customer_group_id = $quote->getCustomer()->getGroupId();    		
    		/* $rules_collection = Mage::getModel('ecomwisecreditplus/limits')->getCollection()
    		->addFieldToFilter('customer_groups', array('finset'=> $customer_group_id )); */    		
    		$rule_array =  $this->_getRulesByCustomer(); 		
    		$date = Mage::getModel('core/date')->Date();    		
    		$rules_collection = Mage::getModel('ecomwisecreditplus/limits')->getCollection()
		    		->addFieldToFilter('status',  array('eq'=> 1 )  )
		    		->addFieldToFilter( 
		    				array( 'customer_groups', 'id' ),
		    				array( array('finset'=> $customer_group_id ), $rule_array )
		    		)
		    		->addFieldToFilter(
		    				'from_date',
		    				array(
		    						array( 'to' => $date, 'date' => true ),
		    						array('from_date', 'null'=>''),
		    				)
		    		)
		    		->addFieldToFilter(
		    				'to_date',
		    				array(
		    						array('from' => $date, 'date' => true ),
		    						array('to_date', 'null'=>''),
		    				)
		    		)
		    		->setOrder('amount','DESC')	;    	
    	}      	
    	return $rules_collection;    	
    }
    
    protected function _getRulesByCustomer(){
    	
    	$quote = $this->getQuote();
    	$customer_id = $quote->getCustomer()->getId();    	
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
    
    public function getUsedAmount($rule_id){    	
    	
    	$quote = $this->getQuote();    	
    	$customer = $quote->getCustomer();
    	$customer_id = $customer->getId();    	
    	$model = Mage::getModel('ecomwisecreditplus/customers')
			    	->getCollection()
			    	->addFieldToFilter('rule_id',  array('eq'=>$rule_id))
			    	->addFieldToFilter('customer_id',  array('eq'=>$customer_id))
			    	->addFieldToSelect( 'amount' );			    	
    	
    	return $model->getFirstItem()->getAmount()  + $quote->getBaseGrandTotal();    	
    }
    
    public function getUsedCredit( $rule_id ){
    
    	$quote = $this->getQuote();
    	$customer = $quote->getCustomer();
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