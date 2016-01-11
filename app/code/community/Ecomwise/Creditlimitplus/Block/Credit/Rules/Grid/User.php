<?php
class Ecomwise_Creditlimitplus_Block_Credit_Rules_Grid_User extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setDefaultDir('asc');
        $this->setId('customersGrid');
        $this->setSaveParametersInSession(false);
        $this->setDefaultFilter(array('in_role_users'=>1));
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $roleId = $this->getRequest()->getParam('id');
        Mage::register('RID', $roleId);
        $collection = Mage::getModel('customer/customer')/*->load()*/->getCollection()        					
        ->addAttributeToSelect(array('firstname', 'lastname', 'email'))
        ;
        $credit_limit_customers_table = Mage::getResourceModel('ecomwisecreditplus/limits_customers')->getMainTable();
        if($roleId){        
	        $collection->getSelect()
	        ->joinLeft(
	        		array('eclc' => $credit_limit_customers_table),
	        		'e.entity_id = eclc.customer_id AND eclc.rule_id=' . $roleId,
	        		array('eclc.customer_id as checked')
	        );	        
        }
        $collection->setOrder('checked');         
        $this->setCollection($collection);       
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('checked', array(
             'header_css_class' => 'a-center',
            'type'      => 'checkbox',
            'name'      => 'checked',
            'align'     => 'center',
            'index' => 'entity_id',
        	'values'    => $this->getSelected(),       		
        	'field_name' => 'checked[]',        		
        ));

        $this->addColumn('user_id', array(
            'header'    =>Mage::helper('adminhtml')->__('User ID'),
            'width'     =>5,
            'align'     =>'left',
            'sortable'  =>true,
            'index'     =>'entity_id'
        ));

        $this->addColumn('firstname', array(
            'header'    =>Mage::helper('adminhtml')->__('First Name'),
            'align'     =>'left',
            'index'     =>'firstname'
        ));

        $this->addColumn('lastname', array(
            'header'    =>Mage::helper('adminhtml')->__('Last Name'),
            'align'     =>'left',
            'index'     =>'lastname'
        ));

        $this->addColumn('email', array(
            'header'    =>Mage::helper('adminhtml')->__('Email'),
            'width'     =>40,
            'align'     =>'left',
            'index'     =>'email'
        ));        
        
        $this->addColumn('status_customers', array(
        		'header'    =>Mage::helper('adminhtml')->__('Status'),
        		'width'     =>40,
        		'align'     =>'left',
        		'index'     =>'checked',
        		'renderer'  => 'Ecomwise_Creditlimitplus_Block_Credit_Rules_Grid_Renderers_Active',        		
        		'type' => 'options', 'options' => array( 1 => 'Active', 2 => 'Inactive'),        		
        		'filter_condition_callback' => array($this, '_filterIsActiveConditionCallback'),
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        $roleId = $this->getRequest()->getParam('id');
        return $this->getUrl('*/*/editrolegrid', array('id' => $roleId));
    }
    
    
    protected function _filterIsActiveConditionCallback($collection, $column)
    {
    	if (!$value = $column->getFilter()->getValue()) {
    		return $this;
    	}      	
    	if($value == 1){    	
    		$condition = "eclc.customer_id IS NOT NULL";
    	}else {    	
    		$condition = "eclc.customer_id IS NULL";
    	}  	
    	$this->getCollection()->getSelect()->where($condition);    	
    	return $this;   	
    }
    
    public function getSelected(){
    	    	
    	if ( $this->getRequest()->getParam('customers') != "" ) {
    		return $this->getRequest()->getParam('customers');
    	}
    	
    	$roleId = ($this->getRequest()->getParam('id') > 0)?$this->getRequest()->getParam('id'):Mage::registry('RID');    	
    	$collection = Mage::getModel('ecomwisecreditplus/limits_customers')
    	->getCollection()
    	->addFieldToFilter("rule_id",  array('eq'=> $roleId ) )
    	->addFieldToSelect('customer_id');    	    	
    	
    	$users = array();    	
    	foreach ($collection as $item) {
    		$users[] = $item->getCustomerId();
    	}    	
    	return $users;
    }    

}

