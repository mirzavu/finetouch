<?php 
class Ecomwise_B2BOrderlist_Block_Catalog_Product_Tierprices extends Mage_Core_Block_Template {
	
	public function getMinTirePrice(){
		if($this->getProduct()){
			$product = $this->getProduct();			
			$tier_price_array = $product->getTierPrice();			
			$min_arr = array();			
			foreach ($tier_price_array as $tier_price){			
				array_push($min_arr, $tier_price['price'] );				
			}				
			return min( $min_arr );			
		}			
	}	
}