<?php 
class Ecomwise_B2BOrderlist_Helper_Data extends Mage_Core_Helper_Abstract{
	
	public function isActive(){
		
		$store_id = Mage::app()->getStore()->getId();
		if((bool) Mage::getStoreConfig('b2borderlist/parameters/active',$store_id))
			return true;
			
		return false;
	}	
	
	public function hidePrices(){
		
		if(!$this->isActive())
			return false;
		
		$storeId = Mage::app()->getStore()->getId();
			
		if(Mage::getSingleton('customer/session')->isLoggedIn())
		{
			$groupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
		}else {		
			$groupId = Mage_Customer_Model_Group::NOT_LOGGED_IN_ID;		
		}
			
		$customerGroup = Mage::getModel("customer/group")->load($groupId);
		
		if(!is_null($customerGroup->getId())){
		
			$hide_for_stores = $customerGroup->getHidePricesForStore();
			$hide_array = explode(",",$hide_for_stores);
			if(in_array("0", $hide_array) || in_array($storeId, $hide_array)){
				return true;				
			}
			
		}

		return false;
	}	
	
	public function hidePricesForOrder($customerId, $storeId){
	
		$groupId =  Mage_Customer_Model_Group::NOT_LOGGED_IN_ID;
		if($customerId != null && $customerId != ""){
			
			$customer = Mage::getModel("customer/customer")->load($customerId);
			if($customer->getId()){
				
				$groupId = $customer->getGroupId();
			}
		}
		$customerGroup = Mage::getModel("customer/group")->load($groupId);
	
		if(!is_null($customerGroup->getId())){
	
			$hide_for_stores = $customerGroup->getHidePricesForStore();
			$hide_array = explode(",",$hide_for_stores);
			if(in_array("0", $hide_array) || in_array($storeId, $hide_array)){
				return true;
			}				
		}	
		return false;
	}
	
	public function customerIsAllowed($category){
		if(!$this->isActive()){
			return true;
		}
	
		$groupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
		$group = Mage::getModel('customer/group')->load($groupId)->getData('customer_group_code');
	
		$allowedGroups=$category->getAllowedCustomerGroupB2b();
		if(!empty($allowedGroups)){
			if(!is_array($allowedGroups)){
				if(in_array('ALL', explode(',', $allowedGroups)) || in_array($group, explode(',', $allowedGroups))){
					return true;
				}
				else{
					return false;
				}
			}else{
				if(in_array('ALL', $allowedGroups) || in_array($group,$allowedGroups)){
					return true;
				}
				else{
					return false;
				}
			}
	    }
	    return false;
	}
	
	public function leadAway(){
		$destinationUrl = Mage::getBaseUrl().'ecomwise_not_allowed';
		$response = Mage::app()->getResponse();
		$response->setRedirect($destinationUrl, 301);
		$response->sendResponse();
	}
	
	public function isForceLoginOnActive(){
		$storeId = Mage::app()->getStore()->getId();
		if((bool) Mage::getStoreConfig('b2borderlist/parameters/forcelogin', $storeId) && $this->isActive())
			return true;
			
		return false;		
	}	
	
	public function processOrder($order) {
			
		$order->setBaseShippingAmount('');
		$order->setShippingTaxAmount('');
		$order->setSubtotalInvoiced('');
		$order->setSubtotalInclTax('');
		$order->setBaseTaxAmount('');
		$order->setBaseTaxInvoiced('');
		$order->setTaxAmount('');
		$order->setTaxInvoiced('');
		$order->setTotalInvoiced('');
		$order->setBaseSubtotal('');
		$order->setBaseSubtotalInvoiced('');
		$order->setBaseTotalPaid('');
		$order->setGrandTotal('');
		$order->setDiscountAmount('');
		$order->setDiscountInvoiced('');
		$order->setBaseGrandTotal('');
		$order->setSubtotal('');
		$order->setBaseDiscountAmount('');
		$order->setBaseDiscountInvoiced('');
		$order->setBaseShippingInvoiced('');
		$order->setBaseShippingTaxAmount('');
		$order->setBaseTotalInvoiced('');
		$order->setBaseTotalInvoicedCost('');
		$order->setShippingInvoiced('');
		$order->setBaseShippingDiscountAmount('');
		$order->setBaseSubtotalInclTax('');
		$order->setShippingDiscountAmount('');
		$order->setShippingInclTax('');
		$order->setBaseShippingInclTax('');
		$order->setShippingAmount(''); //creditmemo
		$order->setTotalPaid(''); //creditmemo
		$order->setPrice('');
		$order->setCodFee('');
		$order->setBaseCodFee('');
	
		$items = $order->getAllItems();
		foreach($items as $item) {
	
			$item->setBasePriceInclTax('');
			$item->setPrice('');
			$item->setBasePrice('');
			$item->setOriginalPrice('');
			$item->setBaseOriginalPrice('');
			$item->setRowTotal('');
			$item->setBaseRowTotal('');
			$item->setRowInvoiced('');
			$item->setBaseRowInvoiced('');
			$item->setPriceInclTax('');
			$item->setBasePriceInclTax('');
			$item->setRowTotalInclTax('');
			$item->setBaseRowTotalInclTax('');
			$item->setTaxAmount('');
			$item->setBaseTaxAmount('');
		}
			
		return $order;
	
	}
	
	public function processInvoice($invoice){
		$invoice->setBaseShippingAmount('');
		$invoice->setShippingTaxAmount('');
		$invoice->setSubtotalInvoiced('');
		$invoice->setSubtotalInclTax('');
		$invoice->setBaseTaxAmount('');
		$invoice->setBaseTaxInvoiced('');
		$invoice->setTaxAmount('');
		$invoice->setTaxInvoiced('');
		$invoice->setTotalInvoiced('');
		$invoice->setBaseSubtotal('');
		$invoice->setBaseSubtotalInvoiced('');
		$invoice->setBaseTotalPaid('');
		$invoice->setGrandTotal('');
		$invoice->setDiscountAmount('');
		$invoice->setDiscountInvoiced('');
		$invoice->setBaseGrandTotal('');
		$invoice->setSubtotal('');
		$invoice->setBaseDiscountAmount('');
		$invoice->setBaseDiscountInvoiced('');
		$invoice->setBaseShippingInvoiced('');
		$invoice->setBaseShippingTaxAmount('');
		$invoice->setBaseTotalInvoiced('');
		$invoice->setBaseTotalInvoicedCost('');
		$invoice->setShippingInvoiced('');
		$invoice->setBaseShippingDiscountAmount('');
		$invoice->setBaseSubtotalInclTax('');
		$invoice->setShippingDiscountAmount('');
		$invoice->setShippingInclTax('');
		$invoice->setBaseShippingInclTax('');
		$invoice->setShippingAmount(''); //creditmemo
		$invoice->setTotalPaid(''); //creditmemo
		$invoice->setPrice('');
		$invoice->setCodFee('');
		$invoice->setBaseCodFee('');
	
		foreach($invoice->getAllItems() as $item){
				
			$item->setBasePriceInclTax('');
			$item->setPrice('');
			$item->setBasePrice('');
			$item->setOriginalPrice('');
			$item->setBaseOriginalPrice('');
			$item->setRowTotal('');
			$item->setBaseRowTotal('');
			$item->setRowInvoiced('');
			$item->setBaseRowInvoiced('');
			$item->setPriceInclTax('');
			$item->setBasePriceInclTax('');
			$item->setRowTotalInclTax('');
			$item->setBaseRowTotalInclTax('');
			$item->setTaxAmount('');
			$item->setBaseTaxAmount('');
		}	
		return $invoice;	
	}
	
	public function getRulesForCustomer($customer_id){
	
		$rule_ids = array();
		$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
	
		$resurce = Mage::getSingleton('core/resource');
		$mapping_table = $resurce->getTableName('ecomwise_b2b_customerrule');
	
		$result_rule_id = $connection->fetchAll("select rule_id from ".$resurce->getTableName('ecomwise_b2b_customerrule')."
															where customer_id=?", $customer_id);
		foreach($result_rule_id as $row){
			$rule_ids[] = $row['rule_id'];
		}	
		return $rule_ids;
	}
	
	function redirect(){
		if(Mage::app()->getFrontController()->getRequest()->getRouteName() == "checkout"){
			return;
		}
		
		$customer = Mage::getModel('customer/customer')->load(Mage::getSingleton('customer/session')->getId());		
		
		
		if(!$customer->getEmail()){
			header("Location: ".Mage::getBaseUrl()."customer/account/login"); exit;
		}		 
		
		$landing = Mage::getStoreConfig('b2borderlist/parameters/landing');
		if(empty($landing)){
			return;
		}	
		$landing = explode('?', $landing);
		$landing[0] .= "?mode=order_list";		
		
		header("Location: ".Mage::getBaseUrl() . $landing[0]); exit;
		
	}
	
	public function showOrderList(){
		
		if(Mage::getSingleton('customer/session')->isLoggedIn()){
			$customer_group =  Mage::getSingleton('customer/session')->getCustomer()->getGroupId();
		}else{
			$customer_group =  0;
		}
			
		$groups = Mage::getStoreConfig('b2borderlist/parameters/groups');
		$groups = explode(',', $groups);
		$show_order_list = false;
		if(in_array($customer_group, $groups)){
			$show_order_list = true;
		}		
		return $show_order_list;		
	}	
	
} 
