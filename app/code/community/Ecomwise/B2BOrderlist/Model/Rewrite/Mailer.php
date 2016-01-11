<?php 
class Ecomwise_B2BOrderlist_Model_Rewrite_Mailer extends Mage_Core_Model_Email_Template_Mailer{
	
	
	public function send(){
		
		$this->processTemplateParams();
		parent::send();
		return $this;
	}
	
	
	public function processTemplateParams()
	{
		
		$storeId = $this->getStoreId();
		$templateParams = $this->getTemplateParams();
	
		//compare template id to work out what we are currently sending
		switch ( $this->getTemplateId()) {
	
			//Order
			case Mage::getStoreConfig(Mage_Sales_Model_Order::XML_PATH_EMAIL_TEMPLATE, $storeId):
			case Mage::getStoreConfig(Mage_Sales_Model_Order::XML_PATH_EMAIL_GUEST_TEMPLATE, $storeId):
			case Mage::getStoreConfig(Mage_Sales_Model_Order::XML_PATH_UPDATE_EMAIL_TEMPLATE, $storeId):
			case Mage::getStoreConfig(Mage_Sales_Model_Order::XML_PATH_UPDATE_EMAIL_GUEST_TEMPLATE, $storeId):

				$order = $templateParams['order'];
				$customerId = $order['customer_id'];
				if($order && Mage::helper('b2borderlist')->hidePricesForOrder($customerId, $storeId)){
					$order = Mage::helper('b2borderlist')->processOrder($order);
					$templateParams['order'] = $order;
					$this->setTemplateParams($templateParams);
				}
				
					
			break;
				//Invoice
			case Mage::getStoreConfig(Mage_Sales_Model_Order_Invoice::XML_PATH_EMAIL_TEMPLATE, $storeId):
			case Mage::getStoreConfig(Mage_Sales_Model_Order_Invoice::XML_PATH_EMAIL_GUEST_TEMPLATE, $storeId):
			case Mage::getStoreConfig(Mage_Sales_Model_Order_Invoice::XML_PATH_UPDATE_EMAIL_TEMPLATE, $storeId):
			case Mage::getStoreConfig(Mage_Sales_Model_Order_Invoice::XML_PATH_UPDATE_EMAIL_GUEST_TEMPLATE, $storeId):
				
				$invoice = $templateParams['invoice'];
				$order = $templateParams['order'];
				$customerId = $order['customer_id'];
				if($invoice && Mage::helper('b2borderlist')->hidePricesForOrder($customerId, $storeId)){
					$invoice = Mage::helper('b2borderlist')->processInvoice($invoice);
					$templateParams['invoice'] = $invoice;
					$this->setTemplateParams($templateParams);
				}
				
				break;
	
				
				//Creditmemo
			case Mage::getStoreConfig(Mage_Sales_Model_Order_Creditmemo::XML_PATH_EMAIL_TEMPLATE, $storeId):
			case Mage::getStoreConfig(Mage_Sales_Model_Order_Creditmemo::XML_PATH_EMAIL_GUEST_TEMPLATE, $storeId):
			case Mage::getStoreConfig(Mage_Sales_Model_Order_Creditmemo::XML_PATH_UPDATE_EMAIL_TEMPLATE, $storeId):
			case Mage::getStoreConfig(Mage_Sales_Model_Order_Creditmemo::XML_PATH_UPDATE_EMAIL_GUEST_TEMPLATE, $storeId):

				$memo = $templateParams['creditmemo'];
				$order = $templateParams['order'];
				$customerId = $order['customer_id'];
				if($memo && Mage::helper('b2borderlist')->hidePricesForOrder($customerId, $storeId)){
					$memo = Mage::helper('b2borderlist')->processOrder($memo);
					$templateParams['creditmem0'] = $memo;
					$this->setTemplateParams($templateParams);
				
				}
		   	 	break;		
		}
	}
} 