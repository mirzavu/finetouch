<?php 

class Ecomwise_B2BOrderlist_Adminhtml_CustomerruleController extends Mage_Adminhtml_Controller_Action{
	
	public function gridAction(){
		$this->loadLayout();
        $this->getLayout()->getBlock('promo.rule.individual.customers')
            ->setRuleCustomers($this->getRequest()->getPost('rule_customers', null));
        $this->renderLayout();
	}
	
	public function individualAction(){
		$this->loadLayout();
        $this->getLayout()->getBlock('promo.rule.individual.customers')
            ->setRuleCustomers($this->getRequest()->getPost('rule_customers', null));
        $this->renderLayout();
	}
	
}