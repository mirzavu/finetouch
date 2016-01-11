<?php 
class Ecomwise_B2BOrderlist_Model_Observer extends Varien_Object{
	
	public function forceLogin($observer){
	
		if(Mage::helper("b2borderlist")->isForceLoginOnActive()){
			if (    ! Mage::getSingleton('customer/session')->isLoggedIn() 
			     && ! Mage::getSingleton('admin/session')->isLoggedIn()
				 && ! Mage::app()->getStore()->isAdmin()
			     //&&  Mage::app()->getRequest()->getModuleName() !== 'storemanager'
			     &&  Mage::app()->getRequest()->getModuleName() !== 'api'
			     &&  Mage::app()->getRequest()->getControllerName() !== 'account') {
			     	 $session = Mage::getSingleton("customer/session");
	                 $session->setBeforeAuthUrl(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'home');
			     	 Mage::app()->getResponse()
			     	            ->setRedirect(Mage::helper('adminhtml')
			     	            ->getUrl("customer/account/login",  array('_type' => 'direct_link')));				
			  }		
	   }	   
	   return;
	}
	
	public function redirect($observer){

		if(!Mage::helper('b2borderlist')->isActive()){
			return;
		}
			
		Mage::helper('b2borderlist')->redirect();		
	}	
	
	public function prepareCustomreGroup($observer){
		
		//if(!Mage::helper("b2borderlist")->isActive())
		//	return;
		
		$data = $observer->getData();
		$model = $data['object'];
		$stores = Mage::app()->getRequest()->getParam('hide_prices_for_store');
		if($stores ){
			$stores = implode(",",$stores);
		}
		$model->setHidePricesForStore($stores);
		return;		
	}
	
	public function setOrderlistTemplates($observer){
		
		if(!Mage::helper("b2borderlist")->isActive()){
			return;
		}

		$store_id = Mage::app()->getStore()->getId();
		
		$controller = $observer->getAction();
		$layout  = $controller->getLayout();			

		$show_order_list = Mage::helper("b2borderlist")->showOrderList();			
		$listblock = $layout->getBlock("product_list");
		$searchblock = $layout->getBlock("search_result_list");
		
		if($listblock && $show_order_list){		
			if($toolbar = $listblock->getToolbarBlock()){				
				if( $toolbar->getCurrentMode() != 'order_list' ){
					return;
				}
			}
			if(!Mage::getStoreConfig("b2borderlist/categories/enabled")){				
				$listblock->setTemplate("b2borderlist/product_list.phtml");
			} else {					
				$current_cat = Mage::registry('current_category')->getId();					
				$selected_cat = Mage::getStoreConfig("b2borderlist/categories/categories");					
				$selected_cat = explode(',',$selected_cat);					
				if (in_array($current_cat, $selected_cat)) {					
					$listblock->setTemplate("b2borderlist/product_list.phtml");
				}
			}					
		}
		
		if($searchblock && $show_order_list){
				$layout->getBlock('head')->addJs('varien/product.js');
				$layout->getBlock('head')->addJs('b2borderlist/b2borderlist_configurable.js');
				$layout->getBlock('head')->addJs('b2borderlist/b2borderlist_bundle.js');
				$layout->getBlock('head')->addJs('b2borderlist/custom.js');		
			if($toolbar = $searchblock->getToolbarBlock()){				
				if( $toolbar->getCurrentMode() != 'order_list' ){
					return;
				}
			}
			if(!Mage::getStoreConfig("b2borderlist/categories/enabled")){				
				$searchblock->setTemplate("b2borderlist/product_list.phtml");
			} else {					
				$current_cat = Mage::registry('current_category')->getId();					
				$selected_cat = Mage::getStoreConfig("b2borderlist/categories/categories");					
				$selected_cat = explode(',',$selected_cat);					
				if (in_array($current_cat, $selected_cat)) {					
					$searchblock->setTemplate("b2borderlist/product_list.phtml");
				}
			}					
		}
		
		$cart_sidebar = $layout->getBlock("cart_sidebar");
		if($cart_sidebar && $show_order_list){
			if($cart_sidebar->getTemplate() == "checkout/cart/sidebar.phtml"){
				$cart_sidebar->setTemplate("b2borderlist/cart_sidebar.phtml");
			}
		}					
		
		return;
	}	
	
	public function saveCustomerMappings($observer){	
		$data = $observer->getData();
		$rule = $data['data_object'];		
		$customers = $rule->getRuleCustomers() != "" ? explode("&", $rule->getRuleCustomers()) : array();
		
		$resurce = Mage::getSingleton('core/resource');
		$mapping_table = $resurce->getTableName('ecomwise_b2b_customerrule');
		$cw = Mage::getSingleton('core/resource')->getConnection('core_write');
		$rule_id = $rule->getId();
		
		if(!empty($customers)){	
			if($rule_id != null && $rule_id != '' ){
				$result = $cw->query("DELETE FROM " . $mapping_table . " WHERE rule_id = " . $rule_id . "");
				foreach($customers as $customerId){
					$customer = Mage::getModel("customer/customer")->load($customerId);
					if($customer->getId()){
						$result = $cw->query("INSERT INTO ".$mapping_table." (rule_id, customer_id, email, firstname, lastname) VALUES ('".$rule_id."','".$customerId."','".$customer->getEmail()."','". $customer->getFirstname()."','".$customer->getLastname()."')");
					}
				}
			}					
		}else{
			if($rule_id != null && $rule_id != ''){
				$result = $cw->query("DELETE FROM " . $mapping_table . " WHERE rule_id = " . $rule_id . "");
			}
		}
		
		return;
	}
	
	public function afterDeleteRule($observer){
		$data = $observer->getData();
		$rule = $data['data_object'];
	
		if($rule->getid()){
			$resurce = Mage::getSingleton('core/resource');
			$mapping_table = $resurce->getTableName('ecomwise_b2b_customerrule');
			$cw = Mage::getSingleton('core/resource')->getConnection('core_write');
			$reult = $cw->query("DELETE FROM ".$mapping_table." WHERE rule_id = ".$rule->getId()."");
				
		}
	}
	
	/**
	 *
	 * Event listener for "catalog_product_collection_load_after"
	 * It applies individual price rules to a product colection calculating them on run instead taking from database
	 *
	 */
	public function prepareCollection($observer){
						
		$data = $observer->getCollection();
		$customer_group = Mage::getSingleton('customer/session')->getCustomerGroupId();
		$storeId = Mage::app()->getStore()->getId();
		$webid =   Mage::getModel('core/store')->load($storeId)->getWebsiteId();
		
		foreach ($data as $product) {
			
			if($product->getTypeId() == "bundle"){
				
				$product->setMinPrice(null);
				$product->setMaxPrice(null);
					
			}else if($product->getTypeId() == "grouped" ){
				
				$assoc_products = $product->getTypeInstance(true)->getAssociatedProductIds($product);
				$assoc_prices = Mage::getResourceModel('catalogrule/rule')->getRulePrices(date("Y-m-d"), $webid, $customer_group, $assoc_products);
				
				if(is_array($assoc_prices) && count($assoc_prices)>0){
					$product->setMinimalPrice(min($assoc_prices));
				}
			}else{
				
				$final_price = Mage::getResourceModel('catalogrule/rule')
				->getRulePrice(date("Y-m-d"),$webid,$customer_group,$product->getId());
				
				
				if($final_price){
					
					$price = (float)$product->getPrice();
					
					$specialPrice = $product->getPriceModel()->calculateSpecialPrice($price, $product->getSpecialPrice(), $product->getSpecialFromDate(),
							$product->getSpecialToDate(), $product->getStore());

					
					$product->setFinalPrice($final_price);
					if($specialPrice < $final_price){
						$product->setFinalPrice($specialPrice);
					}
					
					$product->setMinimalPrice($final_price);
				}								
			}
		}
	   return;
	}
	
	public function saveCustomerGroupAttribute($observer){
		$category = $observer->getCategory();
		$data = $category->getAllowedCustomerGroupB2b();
		if(!empty($data)){
			if(in_array("ALL", $data)){
				$category->setData('allowed_customer_group_b2b', array("ALL"));
				return ;
			}
			
			foreach($data as $key => $val){
				if($val == "NONE"){
					unset($data[$key]);
				}
			}
			$category->setData('allowed_customer_group_b2b', $data);
		}
		
		return ;
	}	
}

