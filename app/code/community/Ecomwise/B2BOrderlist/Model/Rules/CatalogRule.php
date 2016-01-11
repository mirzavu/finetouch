<?php 
class Ecomwise_B2BOrderlist_Model_Rules_CatalogRule extends Mage_CatalogRule_Model_Rule{
	 /**
	  * 
	  * Function calculates price rules for product. It get all rules for 
	  * product and calculates its price.
	  * It is used by configurable products
	  * @see Mage_CatalogRule_Model_Rule::calcProductPriceRule()
	  */
	 public function calcProductPriceRule(Mage_Catalog_Model_Product $product, $price){
	 	
        $priceRules      = null;
        $productId       = $product->getId();
        $storeId         = $product->getStoreId();
        $websiteId       = Mage::app()->getStore($storeId)->getWebsiteId();
        $customerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
        $dateTs          = Mage::app()->getLocale()->storeTimeStamp($storeId);
        $cacheKey        = date('Y-m-d', $dateTs)."|$websiteId|$customerGroupId|$productId|$price";
        $customer_id = Mage::getSingleton('customer/session')->getCustomerId();
        if($customer_id == null){
		 	 if ($_SESSION && isset($_SESSION['adminhtml_quote']) && isset($_SESSION['adminhtml_quote']['customer_id']) && $_SESSION['adminhtml_quote']['customer_id'])
                {
                    $customer_id = $_SESSION['adminhtml_quote']['customer_id'];
                }
		 
		 }

		$rule_ids = Mage::helper("b2borderlist")-> getRulesForCustomer($customer_id);
        
        $product_rule_data = $this->getResource()->_getRuleProductsStmt($time, $time, $productId, $websiteId)->fetchAll();
        //filter hihest discount rule for customer
        $product_rule_data = Mage::getResourceModel('catalogrule/rule')->filterHighestDiscount($product_rule_data, $rule_ids, $customer_id , $price, $customerGroupId);
        
	 		$time = strtotime($dateTs);  

        $priceRules = $price;
        $ind_rules = array();
            foreach ($product_rule_data as $data){
                $rule_id = $data['rule_id'];
            	
                if(Mage::getResourceModel('catalogrule/rule')->isInd($rule_id,$customer_id)){
            	      
            	    if(in_array($rule_id, $rule_ids)){
            	    	
            	    	if(!in_array($rule_id,$ind_rules)){
            	    		 
            	        $product = Mage::getModel('catalog/product')->load($pid);
            	       
            			$priceRules = Mage::helper('catalogrule')->calcPriceRule(
                        $data['action_operator'],
                        $data['action_amount'],
                        $priceRules );
                        $ind_rules[] = $rule_id;
                     if ($data['action_stop']) {
                    	  break;
            	       }
            	      }
            	    }
            	}else{
            		
            	    if($customerGroupId == $data['customer_group_id']){
              	        $priceRules = Mage::helper('catalogrule')->calcPriceRule(
                        $data['action_operator'],
                        $data['action_amount'],
                        $priceRules );
                    
                        if ($data['action_stop']) {
                    	  break;
                    }
            	}
            
               
            }          
		   
        }
		return  $priceRules;
    }
}