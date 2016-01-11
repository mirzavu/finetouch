<?php 
class Ecomwise_B2BOrderlist_IndexController extends Mage_Core_Controller_Front_Action {
	
	public function checkQtyAction(){		
		$prod_id = $this->getRequest()->getParam("prod_id");
		$qty = $this->getRequest()->getParam("qty");		
		if(isset($qty) && isset($prod_id)){				
			$product = Mage::getModel("catalog/product")->load($prod_id);			
			$stock_model = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product);			
			$available_qty = (int)$stock_model->getQty();
			$manage_stock = $stock_model->getManageStock();			
			if($available_qty > $qty || $manage_stock == 0){
				$message = Mage::Helper("b2borderlist")->__("Qty available");
				$response['available'] = 1;
			}else{
				$message = Mage::Helper("b2borderlist")->__("Qty not available");
				$response['available'] = 0;
			}						
			$response['status'] = 'success';
			$response['message'] = $message;			
			$this->getResponse()->clearHeaders()->setHeader('Content-type','application/json',true);
			$this->getResponse()->setBody(json_encode($response));
			return;		
		}		
	}
}