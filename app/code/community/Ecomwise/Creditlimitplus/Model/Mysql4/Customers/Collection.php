<?php 
class Ecomwise_Creditlimitplus_Model_Mysql4_Customers_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract{

	protected function _construct(){
		parent::_construct();
		$this->_init('ecomwisecreditplus/customers');
	}
}