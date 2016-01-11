<?php 

class Ecomwise_B2BOrderlist_Model_Customerrules extends Mage_Eav_Model_Entity_Attribute{
	
	public function get_customer_groups(){
		
		$notlogged = false;
		
	    $customer_groups = Mage::getResourceModel('customer/group_collection')
            ->load()->toOptionArray();

        
        foreach ($customer_groups as $group) {
            if ($group['value']==0) {
                $notlogged = true;
            }
        }
        if (!$notlogged) {
            array_unshift($customer_groups, array('value'=>0, 'label'=>Mage::helper('salesrule')->__('NOT LOGGED IN')));
        }
        
        
        $customer_groups_array = array();
            
        if ($customer_groups){
             foreach ($customer_groups as $group){
                    $customer_groups_array[$group['value']] = $group['label'];
             }
        }
            
        return $customer_groups_array;
    }
	
	public function get_customers(){
		
		$customers = array();
		
		$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
		$resurce = Mage::getSingleton('core/resource');
		$customer_to_rule_table = $resurce->getTableName('ecomwise_b2b_customerrule');        

      
        $query    = $connection  ->select()
                                 ->from($customer_to_rule_table, array('customer_id'))
                                 ->group('customer_id');
        
        $customer_rows = $connection->fetchCol($query);       
      
        if ($customer_rows) {
            $customer_collection = Mage::getResourceModel('customer/customer_collection')
                ->addNameToSelect()
                ->addAttributeToFilter('entity_id', $customer_rows)
                ->load();
                                    
            foreach ($customer_collection as $customer){
                $name = $customer->getName() . ' - ' . $customer->getEmail();
                $customers[$customer->getId()] = $name;
            }
        }
        
        return $customers;
    
    }
    
  
	public function getCustomersForRule($rule_id){
		$resurce = Mage::getSingleton('core/resource');
		$mapping_table = $resurce->getTableName('ecomwise_b2b_customerrule');
		$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
		
		$result = $connection->query('SELECT customer_id FROM '.$mapping_table.'
						                       WHERE rule_id = '.$rule_id.'');
		
		$customer_rows = $result->fetchAll(PDO::FETCH_COLUMN);
		
		//Zend_Debug::dump($customer_rows);
		return $customer_rows;
		
	}
	
	
}