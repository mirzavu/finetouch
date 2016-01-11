<?php
require_once("Mage/Checkout/controllers/CartController.php");

class Ecomwise_B2BOrderlist_CartController extends Mage_Checkout_CartController
{
	public function addsidebarAction(){	
		$cart = Mage::getSingleton('checkout/cart');
		$qtys = $this->getRequest()->getParam('qty-sidebar');
		Mage::helper('b2borderlist/cart')->addAllToCartSidebar($cart, $qtys);
		$cart->save();
		Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
		$this->_redirectUrl(Mage::helper('checkout/url')->getCheckoutUrl());	 
		
	}
	
	public function productoptionsAction(){
		$pid = $this->getRequest()->getParam("productId");
		
		if(empty($pid)){
			$result = array("status" => "error",   "message" => $this->__("Invalid data"));
			echo $this->__("invalid Data");
			exit();
		}
		$product = Mage::getModel("catalog/product")->load($pid);
		
		Mage::register('product', $product);
		
		if($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE){
			$content = $this->getConfigrableOptionBlock($product);
		}
		echo "<div class='product-children-block'>".$content."</div>"; return;
	}
	
	
	public function additemsAction(){
		
		$data = $this->getRequest()->getParams();		
		$cart = Mage::getSingleton('checkout/cart');
		//$cart->init();
		
		
		//Zend_Debug::dump( $data['conf'] );die;
		
		if(count($data['super_group']) > 0 ){
			$data = $this->mergeGrouped($data);
		}
		if(count($data['qty']) > 0){
			Mage::helper('b2borderlist/cart')->addAllToCart($cart,$data['qty'],$data['options'],$this->getRequest()->getParam('origin'));
		}
		if(isset($data['conf'])){
			Mage::helper('b2borderlist/cart')->addAllToCartConf($cart, $data['conf'],$data['options'],$this->getRequest()->getParam('origin'));
		}
		
		if(isset($data['bundle'])){ 
			Mage::helper('b2borderlist/cart')->addAllToCartBundle($cart, $data['bundle'],$this->getRequest()->getParam('origin'));
			
		}
		
		$cart->save();
		Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
		Mage::helper('b2borderlist/cart')->addReportMessages();
		
	
		$this->_redirectReferer();
		
	}
	
	private function mergeGrouped($data){
		foreach($data['super_group'] as $group_id => $qtys){
			foreach($qtys as $pid=> $qty){
				if((intval($qty) > 0 )){
					if(!empty($data[qty][$pid])){
						$data[qty][$pid] = intval($data[qty][$pid]) + intval($qty);
					}else{
						$data[qty][$pid] = intval($qty);
					}
				}
			}
		}
		unset($data['super_group']);
		return $data;
	}
}