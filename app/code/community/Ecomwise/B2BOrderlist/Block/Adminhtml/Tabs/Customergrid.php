<?php 
class Ecomwise_B2BOrderlist_Block_Adminhtml_Tabs_Customergrid 
		extends Mage_Adminhtml_Block_Widget_Grid  
			implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
	
	
	public function getTabLabel(){
		return Mage::helper('catalogrule')->__('Individual Customers');
	}
	
	public function getTabTitle(){
		return Mage::helper('catalogrule')->__('Individual Customers');
	}
	
	public function canShowTab(){
		return true;
	}
	
	public function isHidden(){
		return false;
	}
				
	public function __construct(){
		parent::__construct();
		
		$this->setId('main_customergrid');
		$this->setUseAjax(true);
		$this->setDefaultSort('entity_id');
		$this->setDefaultFilter(array('in_customer' => 1));
		$this->setSaveParametersInSession(true);		
	}
		
	protected function _addColumnFilterToCollection($column){
		// Set custom filter for in category flag
		if ($column->getId() == 'in_customer') {
			$customerIds = $this->_getSelectedCustomers();
			if (empty($customerIds)) {
				$customerIds = 0;
			}
			if ($column->getFilter()->getValue()) {
				$this->getCollection()->addFieldToFilter('entity_id', array('in'=>$customerIds));
			}else if(!empty($customerIds)) {
				$this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$customerIds));
			}
				
		} else {
			parent::_addColumnFilterToCollection($column);
		}
		return $this;
	}
		
	protected function _prepareCollection(){
		$rule_id = Mage::registry("current_promo_catalog_rule");
		if ($rule_id && $rule_id->getId()) {		
			$this->setDefaultFilter(array('in_customer'=>1));
		}
						
		$collection = Mage::getResourceModel('customer/customer_collection')
			->addNameToSelect()
			->addAttributeToSelect('email')
			->addAttributeToSelect('created_at')
			->addAttributeToSelect('group_id');
		
		$this->setCollection($collection);
		
		return parent::_prepareCollection();
	}
	
	protected function _prepareColumns(){		
		$this->addColumn('in_customer', array(
				'header_css_class' => 'a-center',
				'width'     => '50px',
				'type'      => 'checkbox',
				'name'      => 'in_customer',
				'values'    => $this->_getSelectedCustomers(), 
				'align'     => 'center',
				//'field_name' => 'rule_customers[]',
				'index'     => 'entity_id'
		));
		
		$this->addColumn('entity_id', array(
				'header'    => Mage::helper('customer')->__('ID'),
				'width'     => '50px',
				'index'     => 'entity_id',
				'id'        => 'entity_id',
				'type'      => 'number',
		));
	
		$this->addColumn('fullname', array(
				'header'    => Mage::helper('customer')->__('Name'),
				'index'     => 'name'
		));
		$this->addColumn('email', array(
				'header'    => Mage::helper('customer')->__('Email'),
				'width'     => '150',
				'index'     => 'email'
		));
	
		$groups = Mage::getResourceModel('customer/group_collection')
		->addFieldToFilter('customer_group_id', array('gt'=> 0))
		->load()
		->toOptionHash();
	
		$this->addColumn('group', array(
				'header'    =>  Mage::helper('customer')->__('Group'),
				'width'     =>  '100',
				'index'     =>  'group_id',
				'type'      =>  'options',
				'options'   =>  $groups,
		));
		
		return parent::_prepareColumns();
	}
	
	protected function _getSelectedCustomers(){
        $customers = $this->getRuleCustomers();
        if (!is_array($customers)) {
            $customers = $this->getSelectedRuleCustomers();
        }
        return $customers;
    }
   		
	public function getSelectedRuleCustomers(){
		$rule_id = Mage::app()->getRequest()->getParam('id');
		$customers = array();
		
		if($rule_id){
			$customers = Mage::getModel('b2borderlist/customerrules')->getCustomersForRule($rule_id);
		}
		
		return $customers;
	}
	
	public function getGridUrl(){
		return $this->_getData('grid_url') ? $this->_getData('grid_url') : $this->getUrl('*/*/grid', array('_current'=> true));
	}
		
} 