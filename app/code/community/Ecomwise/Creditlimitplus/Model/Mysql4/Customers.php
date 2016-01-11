<?php
class Ecomwise_Creditlimitplus_Model_Mysql4_Customers extends Mage_Core_Model_Mysql4_Abstract {

	protected function _construct(){
		$this->_init('ecomwisecreditplus/customers', 'id');
	}	
}