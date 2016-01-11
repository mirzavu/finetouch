<?php
class Ecomwise_Creditlimitplus_AccountController extends Mage_Core_Controller_Front_Action{
	
	public function init(){
		if(!Mage::getSingleton('customer/session')->isLoggedIn()){
			$this->_redirect("customer/account/login");
			return;
		}
	}
	
	public function indexAction(){		
		
		$this->init();
		
		$this->loadLayout();
		$this->getLayout()->getBlock('head')->setTitle($this->__('Credi Limits'));
		$this->renderLayout();
	}	
}