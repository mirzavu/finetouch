<?php
class Ecomwise_Creditlimitplus_Block_Credit_Rules_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct(){
		parent::__construct();
		$this->setId('creditRules');		
		$this->setDefaultDir('DESC');		
		$this->setSaveParametersInSession(true);	
	}	
	
	protected function _prepareCollection(){		
		$collection = Mage::getModel('ecomwisecreditplus/limits')->getCollection();//->addFieldToFilter('is_deleted', array('eq' => '0'));
		$this->setCollection($collection);		
		return parent::_prepareCollection();		
	}

	protected function _prepareColumns(){		
		
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
	
	public function getRowUrl($row){
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}

}