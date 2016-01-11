<?php
class Ecomwise_Creditlimitplus_Block_Adminhtml_Customer_Edit_Tab_Grid_Rules extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setDefaultDir('asc');
        $this->setId('customersGrid');
        $this->setSaveParametersInSession(false);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $customer_id = $this->getRequest()->getParam('id');
        Mage::register('customer_id', $customer_id);           
        $collection = Mage::getModel('ecomwisecreditplus/limits')->getCollection(); 
        $credit_limit_customers_table = Mage::getResourceModel('ecomwisecreditplus/limits_customers')->getMainTable();        
         $collection->getSelect()
        ->joinLeft(
        		array('eclc' => $credit_limit_customers_table),
        		'main_table.id = eclc.rule_id  AND eclc.customer_id=' . $customer_id,
        		array('eclc.customer_id as checked')
        );         
         $collection->setOrder('checked');
        $this->setCollection($collection);        
        return parent::_prepareCollection();        
    }

	protected function _prepareColumns(){		
	
		$this->addColumn('checked', array(
				'header_css_class' => 'a-center',
				'type'      => 'checkbox',
				'name'      => 'checked',
				'align'     => 'center',
				'index'     => 'id',
				'field_name' => 'credit_limit_id[]',		
				'values' => $this->getSelected(),				 
		));	
		
		$this->addColumn('id',
				array(
						'header'=> $this->__('ID'),
						'align' =>'right',
						'width' => '50px',
						'index' => 'id',
						'type'	=> 'number'
				)
		);
		
		$this->addColumn('name',
				array(
						'header'=> $this->__('Credit rule'),
						'index' => 'name',
				)
		);
		
		$this->addColumn('amount',
				array(
						'header'=> $this->__('Amount'),
						'index' => 'amount',
						'type'	=> 'number'
				)
		);
		
		$this->addColumn('start_date',
				array(
						'header'=> $this->__('Start Date'),
						'index' => 'from_date',
						'type'	=> 'date'
				)
		);
		
		$this->addColumn('end_date',
				array(
						'header'=> $this->__('End Date'),
						'index' => 'to_date',
						'type'	=> 'date'
				)
		);
		
		$this->addColumn('status',
				array(
						'header'=> $this->__('Status'),
						'index' => 'status',
						'width' => '80px',
						//'type'	=> 'date'
						'type' => 'options', 'options' => array( 1 => 'Active', 2 => 'Inactive'),
						'filter_condition_callback' => array($this, '_filterIsActiveConditionCallback'),
						//'renderer'  => 'Ecomwise_Creditlimitplus_Block_Credit_Rules_Grid_Renderers_Active',
				)
		);		
	}	

    public function getGridUrl()
    {
        $customerId = $this->getRequest()->getParam('id');
        return $this->getUrl('*/customer_grid/editrolegrid', array('id' => $customerId));
    }
    
    
	protected function _filterIsActiveConditionCallback($collection, $column)
	{
		if (!$value = $column->getFilter()->getValue()) {
			return $this;
		}	
		 
		if($value == 1){			 
			$condition = "main_table.status = 1";
		}else {			 
			$condition = "main_table.status = 2";
		}		 
		$this->getCollection()->getSelect()->where($condition);		 
		return $this;		 
	}  
    
    public function getSelected(){    	 
    	 
    	if ( $this->getRequest()->getParam('credit_rules') != "" ) {
    		return $this->getRequest()->getParam('credit_rules');
    	}     
    	
    	$customer_id = ($this->getRequest()->getParam('id') > 0)?$this->getRequest()->getParam('id'):Mage::registry('customer_id');
    	
    	$collection = Mage::getModel('ecomwisecreditplus/limits_customers')
    	->getCollection()
    	->addFieldToFilter("customer_id",  array('eq'=> $customer_id ) )
    	->addFieldToSelect('rule_id');    	
    	 
    	$rules = array();
    	foreach ($collection as $item) {
    		$rules[] = $item->getRuleId();
    	}
    	return $rules;
    }

}

