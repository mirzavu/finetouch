<?php
class Ecomwise_B2BOrderlist_Block_Renderers_GridCatalogCustomer extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
    
	public function render(Varien_Object $row){

		$customers = array();
		$resource = Mage::getSingleton('core/resource');
		$cr = Mage::getSingleton('core/resource')->getConnection('core_read');
		$cw = Mage::getSingleton('core/resource')->getConnection('core_write');
		$table = $resource->getTableName('ecomwise_b2b_customerrule');

		$query = $cr ->select()
		->from($table, array('*'))
		->where('rule_id = ?', $row->getData('rule_id'));
		
		$customer_rows = $cr->fetchAll($query);
		$customers = array();
		if($customer_rows) {
			foreach ($customer_rows as $customer){
				$customerName =   $customer['firstname']." ".$customer['lastname']." - ".$customer['email'];
				$customers [] = $customerName;
			}
		}
		
		
		return implode("<br/>", $customers);
    }

} 