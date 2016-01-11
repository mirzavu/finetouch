<?php
class Ecomwise_Creditlimitplus_Model_Observer {	
	
	public function orderSaveAfter(Varien_Event_Observer $observer) {			
		
		if(Mage::registry('ecomwisecreditplus_order_save_after')){
			return $this;
		}
		
		$order = $observer->getOrder();		
		$pyment = $order->getPayment();		
		
		if($pyment->getMethod() == 'payonaccountplus' ){			
			$base_grand_total = $order->getBaseGrandTotal();			
			$rule_id = $pyment->getPayonaccountplusOption();
			$customer_id = $order->getCustomerId();			
			
			$key = "creditlimitplus_ordersave_" . $order->getQuoteId() . "_" . $customer_id;
			Mage::register($key, true);			
			
			$model = Mage::getModel('ecomwisecreditplus/customers')
						->getCollection()
						->addFieldToFilter('rule_id', $rule_id)
						->addFieldToFilter('customer_id', $customer_id);			
			
			$values = $model->getFirstItem();
			$id = $values->getId();			
			$amount = $base_grand_total + $values->getAmount();
			
			$data = array('rule_id'=> $rule_id,'customer_id'=> $customer_id, 'amount' => $amount, 'used_at' => now() );
			$model = Mage::getModel('ecomwisecreditplus/customers')->load( $id )->addData($data);
			$model->setId( $id )->save();	

			Mage::register('ecomwisecreditplus_order_save_after',true);			
			
		}		
	}	
	
	public function customer_save_before(Varien_Event_Observer $observer){	
		
		if(Mage::registry('ecomwisecreditplus_customer_save_before_executed')){
			return $this;
		}	
		
		$request = Mage::app()->getRequest();		
		if ($request->getPost() && $request->getParam('credit_rules_ids')) {
			$credit_limits = $request->getParam('credit_rules_ids');		
			$credit_limits = Mage::helper('adminhtml/js')->decodeGridSerializedInput($credit_limits);		
			$customer_id = $observer->getCustomer()->getId();		
			Mage::getModel('ecomwisecreditplus/limits_customers')->deleteByCustomerId( $customer_id );			
			foreach ($credit_limits as $rule_id){
				$customers_model = Mage::getModel('ecomwisecreditplus/limits_customers');
				$data = array(
						'customer_id' => $customer_id,
						'rule_id' => $rule_id
				);
				$customers_model->addData($data);//->save();
				$customers_model->save();
			}			
		}		
		Mage::register('ecomwisecreditplus_customer_save_before_executed',true);		
	}
	
	public function sales_order_invoice_pay(Varien_Event_Observer $observer){
	
		$invoice = $observer->getInvoice();
		$order = $invoice->getOrder();
		$pyment = $order->getPayment();
		$this->releaseCredit($order, $pyment);
	}
	
	public function sales_order_payment_cancel(Varien_Event_Observer $observer){
	
		$order = $observer->getPayment()->getOrder();
		$pyment = $observer->getPayment();
		$this->releaseCredit($order, $pyment);
	}
	
	private function releaseCredit($order, $pyment){
	
		if($pyment->getMethod() == 'payonaccountplus'){
			$base_grand_total = $order->getBaseGrandTotal();
			$rule_id = $pyment->getPayonaccountplusOption();
			$customer_id = $order->getCustomerId();			
	
			$model = Mage::getModel('ecomwisecreditplus/customers')
			->getCollection()
			->addFieldToFilter('rule_id', $rule_id)
			->addFieldToFilter('customer_id', $customer_id);
	
			$values = $model->getFirstItem();
	
			if(!is_null($values->getId())){
				$id = $values->getId();
				$amount = $values->getAmount() - $base_grand_total;
				$amount = ($amount<0)?0:$amount;
					
				$data = array('rule_id'=> $rule_id,'customer_id'=> $customer_id, 'amount' => $amount, 'used_at' => now() );
				$model = Mage::getModel('ecomwisecreditplus/customers')->load( $id )->addData($data);
				$model->setId( $id )->save();
			}
		}
	}
	
} 