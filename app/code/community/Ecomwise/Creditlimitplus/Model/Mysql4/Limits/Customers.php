<?php
class Ecomwise_Creditlimitplus_Model_Mysql4_Limits_Customers extends Mage_Core_Model_Mysql4_Abstract {

	protected function _construct(){
		$this->_init('ecomwisecreditplus/creditlimits_customers', 'id');
	}	
	
	public function deleteByRuleId($rule_id)
	{
		$table = $this->getMainTable();
		$where = array();
		$where[] =  $this->_getWriteAdapter()->quoteInto('rule_id = ?',$rule_id);
		//$where[] =  $this->_getWriteAdapter()->quoteInto("customer_id = ? ", $uid);
		$result = $this->_getWriteAdapter()->delete($table,$where);
		return $result;
	}
	
	public function deleteByCustomerId($customer_id)
	{
		$table = $this->getMainTable();
		$where = array();
		$where[] =  $this->_getWriteAdapter()->quoteInto('customer_id = ?',$customer_id);
		//$where[] =  $this->_getWriteAdapter()->quoteInto("customer_id = ? ", $uid);
		$result = $this->_getWriteAdapter()->delete($table,$where);
		return $result;
	}
	
}