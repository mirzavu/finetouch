<?php 
class Ecomwise_B2BOrderlist_Helper_Cart extends Mage_Core_Helper_Abstract{
	
	private $itemsadded = array();
	private  $itemsnotadded = array();
	
	/**
	 * add simple prosucts in cart
	 * @param unknown_type $cart
	 * @param unknown_type $qtys
	 * @param unknown_type $origin
	 */
	public function addAllToCart($cart,$qtys,$data_options,$origin){
		
		foreach ($qtys as $key => $value){		
			if(intval($value)>0){
				$product=Mage::getModel('catalog/product')->load($key);	
				if($product->isSaleable()){
	
					try {
						if(!in_array($product->getId(), $cart->getProductIds()) || true){						
							
							// Adding custom options
							$options = array( 'qty' => $value,
												'product' =>  $product->getId()
							 );
							 //Zend_Debug::dump($options);die(); 
							
							if(isset($data_options[$product->getId()])){
								$pr_options = $data_options[$product->getId()];								
								$options['options'] = $pr_options;
							}
		
							
						//if($this->addToCart($product, $cart, 0, $value)){
						if($this->addToCart($product, $cart, 0, $options)){
							$this->itemsadded [] = $product->getName();
							
							}else{
								$this->itemsnotadded [] = $product->getName();
								
							}
					    }else{
								$items = $cart->getItems();
								foreach($items as $item){
									$data=$item->getData();
									$productId = $data['product_id'];
									$itemQty = $data['qty'];
									if($productId==$product->getId()){
										if($this->addToCart($product, $cart, $itemQty, $value)){
											 
											$this->itemsadded [] = $product->getName();
										}else{
											$this->itemsnotadded [] = $product->getName();
										}
									}
								}
							}
						}catch (Exception $ex) {
						Mage::getSingleton('core/session')->addError($ex->getMessage());
					}
				}
			}
			
		}
	
		return;
	}
	/**
	 * Add products in cart from sidebar
	 * @param unknown_type $cart
	 * @param unknown_type $qtys
	 */
	public function addAllToCartSidebar($cart, $qtys){
		$qtys_simple = $qtys['simple'];
		foreach ($qtys_simple as $itemId => $value){
			$keys = array_keys($value);
			$key = $keys[0];
			if(intval($value[$key]) > 0){
				$product = Mage::getModel('catalog/product')->load($key);
				if($product->isSaleable()){
					try {
						
						if(!in_array($product->getId(), $cart->getProductIds())){
							$cart->addProduct($product, $value[$key]);
						}
						else{
							$cart->removeItem($itemId);
							$cart->addProduct($product, $value[$key]);
						}
						
					}
					catch (Exception $ex) {
						Mage::getSingleton('checkout/session')->addError($ex->getMessage());
					}
				}
			}
		}
		
		$qtys_conf = $qtys['conf'];
		$qtyc = $qtys_conf['qty'] ;
		unset($qtys_conf['qty']);
		foreach($qtys_conf as  $itemId => $value){
			$keys = array_keys($value);
			$key = $keys[0];
			$options_array = $value[$key];
			$options = unserialize(array_shift($options_array));
			$options['qty'] = $qtyc[$itemId][$options['product']];
			if(intval($options['qty']) > 0){
				$product = Mage::getModel('catalog/product')->load($options['product']);
				if($product->isSaleable()){
					try {
						if(!in_array($product->getId(), $cart->getProductIds())){
							$cart->addProduct($product, $options);
						}else{
							$cart->removeItem($itemId);
							$cart->addProduct($product, $options);
						}
					}catch (Exception $ex) {
						Mage::getSingleton('checkout/session')->addError($ex->getMessage());
					}
				}
			}
		}
		$qtys_bundle = $qtys['bundle'];
		$qtyb = $qtys_bundle['qty'] ;
		unset($qtys_bundle['qty']);
		foreach($qtys_bundle as  $itemId => $value){
			$keys = array_keys($value);
			$key = $keys[0];
			$options_array = $value[$key];
			$options = unserialize(array_shift($options_array));
			$options['qty'] = $qtyb[$itemId][$options['product']];
			if(intval($options['qty']) > 0){
				$product = Mage::getModel('catalog/product')->load($options['product']);
				if($product->isSaleable()){
					try {
						if(!in_array($product->getId(), $cart->getProductIds())){
							$cart->addProduct($product, $options);
						}else{
							$cart->removeItem($itemId);
						    $cart->addProduct($product, $options);
						}
					}catch (Exception $ex) {
						Mage::getSingleton('checkout/session')->addError($ex->getMessage());
					}
				}
			}
		}
		return;
	}
	/**
	 * add configurable products in cart
	 * @param unknown_type $cart
	 * @param unknown_type $data_conf
	 */
	public function addAllToCartConf($cart, $data_conf, $data_options){
		
		
		
		
		$qtys_conf = $data_conf['qty'];
		foreach ($qtys_conf as $key => $value){
			if(intval($value) > 0){
				
				$product=Mage::getModel('catalog/product')->load($key);
				
		
				if($product->isSaleable()){
					
					$attributes  = $data_conf['super_attribute'][$product->getId()];
					$product_selected = $this->getSimpleProduct($product, $attributes);
					 if(!$product_selected){
						Mage::getSingleton('core/session')->addError($this->__("Invalid product options selected"));
						continue;
					} 
					
					$options = array();
					
					if(isset($attributes)){
						$options = array(
								'qty' => $value,
								'super_attribute' =>$attributes,
								'product' =>$product->getId()
						);
					}
					
					// Adding custom options
					if(isset($data_options[$product->getId()])){						
						$conf_options = $data_options[$product->getId()];
						$options['qty'] = $value;
						$options['product'] = $product->getId();
						$options['options'] = $conf_options;						
					}
				
					
					try {
						
						$item_in_cart = $this->checkifConfInCart($cart, $product_selected);
						if($item_in_cart !== false){	
	
							if($this->addToCart($product, $cart, $item_in_cart['qty'], $options)){
								$this->itemsadded [] = $product->getName();
							}else{
								$this->itemsnotadded [] = $product->getName();
							}
						
						}else{
							
							if($this->addToCart($product, $cart, 0, $options)){
								$this->itemsadded [] = $product->getName();
							}else{
								$this->itemsnotadded [] = $product->getName();
							}
							
						}
					
					}
					catch (Exception $ex) {
						Mage::getSingleton('core/session')->addError($ex->getMessage());
					}
				}
			}
		}
		return;
	}
	
	/**
	 * add bundle products in cart
	 * @param unknown_type $cart
	 * @param unknown_type $data
	 */
	public function addAllToCartBundle($cart, $data){
		foreach($data as $pid => $pdata){
			if(intval($pdata['qty']) > 0 ){
					$product = Mage::getModel("catalog/product")->load($pid);
					if($product->isSaleable()){
		             	$pdata['product'] = $pid;
                        $pdata['related_product'] = null;
                        $pdata['options'] = null;
                        
                        $cartCandidates = $product->getTypeInstance(true) ->prepareForCartAdvanced(new Varien_Object($pdata), $product);
                        $candidate_errors = array();
                        if (is_string($cartCandidates)) {
                        	Mage::getSingleton('core/session')->addError($this->__("Product %s is not added to cart: %s", $product->getName(), $cartCandidates));
                        	$this->itemsnotadded [] = $product->getName();
                        	continue;
                        }
                        if(!is_array($cartCandidates)){
                        	$cartCandidates = (array) $cartCandidates;
                        }
						
                        foreach($cartCandidates as $candidate){
                        	if($candidate->getTypeId() == "bundle"){
                        		continue;
                        	}
                        	$req_qty = $candidate->getCartQty();
                        	$placed_qty  = $this->getQtyForProdcut($cart, $candidate->getId());
                        	if(!$candidate->getStockItem()->checkQty($req_qty*$pdata['qty'] + $placed_qty)){
                        		$candidate_errors [] = $this->__("The requested quantity for %s is not available.", $candidate->getName());
                        	}
                        }
                        if(empty($candidate_errors)){
	                        try{
								$cart->addProduct($product, $pdata);
								$this->itemsadded [] = $product->getName();
			  			 	}catch(Exception $ex){
			   					Mage::getSingleton('core/session')->addError($this->__("Product %s is not added to cart: %s", $product->getName(), $ex->getMessage()));
			   					$this->itemsnotadded [] = $product->getName();
			  				}
                        }else{
                        	
                        	foreach($candidate_errors as $error){
                        		Mage::getSingleton('core/session')->addError($error);
                        	}
                        	
                        	$this->itemsnotadded [] = $product->getName();
                        }
						
					}
				}
						
		   }
		return;
	}
	
	private function getQtyForProdcut($cart, $pid){
		$qty = 0;
		foreach($cart->getItems() as $item){
			if($item->getProduct()->getId() == $pid){
				$qty += $item->getQty();
			}
		}
		
		return $qty;
	} 
	private function addToCart($product, $cart, $qtyAlreadyInCart, $qty){		
		$qty_added = is_array($qty)? $qty['qty'] : $qty;
		if($product->getTypeId() == "configurable"){
			$simple_prodcut = $this->getSimpleProduct($product, $qty['super_attribute']);
			$availability = $simple_prodcut->getStockItem()->checkQty($qtyAlreadyInCart + $qty_added);
		}else{
			$availability = $product->getStockItem()->checkQty($qtyAlreadyInCart + $qty_added);
		}
		//echo $availability;die;
		if($availability){
		   try{    	
		   	
				$cart->addProduct($product, $qty);
		   }catch(Exception $ex){
		   		Mage::getSingleton('core/session')->addError($this->__("Product %s is not added to cart: %s", $product->getName(), $ex->getMessage()));
		   		return false;
		   }
		}else{
			Mage::getSingleton('core/session')->addError($this->__("The requested quantity for %s is not available.", $product->getName()));
			return false;
		}
		return true;
	}
	
	public function addReportMessages(){
		if(count($this->itemsnotadded) == 1){
			$strnotadded = $this->__("Product: %s is not added to cart.", $this->itemsnotadded[0]);
		}else if(count($this->itemsnotadded) > 1){
			$strnotadded = $this->__("Products: %s are not added to cart.", implode(", ", $this->itemsnotadded));
		}
		
		if(count($this->itemsadded) == 1){
			$stradded = $this->__("Product: %s is added to cart.", $this->itemsadded[0]);
		}else if(count($this->itemsadded) > 1){
			$stradded = $this->__("Products: %s are added to cart.", implode(", ",$this->itemsadded));
		}
		
		if(count($this->itemsnotadded) > 0){
			Mage::getSingleton('core/session')->addError($strnotadded);
		}
		
		if(count($this->itemsadded) > 0 /*&& !$inCart*/){
			Mage::getSingleton('core/session')->addSuccess($stradded);
		}
		return ;
	}
	
	private function getSimpleProduct($product, $options){
	
		return Mage::getModel("catalog/product_type_configurable")->getProductByAttributes($options, $product);
	}
	
	
	private function checkifConfInCart($cart, $product_selected){
		$items = $cart->getItems();
		foreach($items as $item){
			$item_product = $item->getProduct();
			if($item_product->getTypeId() != "configurable"){
				continue;
			}
			$item_option = $item->getOptionByCode('simple_product');
			if($product_selected->getId() == $item_option->getProductId()){
				return $item;
			}
			
			return false;
		}
	}
	
}
