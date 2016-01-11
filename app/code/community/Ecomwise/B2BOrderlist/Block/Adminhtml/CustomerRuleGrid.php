<?php
/**
 *Catolog promotions grid override. 
 * individual customers added
 * 
 */
class Ecomwise_B2BOrderlist_Block_Adminhtml_CustomerRuleGrid extends Mage_Adminhtml_Block_Widget_Grid{
	
	 public function __construct(){
        parent::__construct();
        $this->setId('promo_catalog_grid');
        $this->setDefaultSort('name');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        
    	$resurce = Mage::getSingleton('core/resource');
    	$table_name = $resurce->getTableName('ecomwise_b2b_customerrule');
    	$collection = Mage::getResourceModel('catalogrule/rule_collection');
    	$collection->getSelect()->joinLeft(array('customerrule' => $table_name), 'main_table.rule_id = customerrule.rule_id', array('email'));
    	$collection->getSelect()->group('main_table.rule_id');
    	//Mage::log($collection->getData(),null,'mm.log');
    	$this->setCollection($collection);
        Mage::register("catalogrule_collection", $collection);
       
        return parent::_prepareCollection();
    	
    	
    }

     protected function _prepareColumns(){
        $this->addColumn('rule_id', array(
            'header'    => Mage::helper('catalogrule')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'rule_id',
        	'filter_index'=>'main_table.rule_id',
        ));

        $this->addColumn('name', array(
            'header'    => Mage::helper('catalogrule')->__('Rule Name'),
            'align'     =>'left',
            'index'     => 'name',
        ));
        
        $this->addColumn('from_date', array(
            'header'    => Mage::helper('catalogrule')->__('Date Start'),
            'align'     => 'left',
            'width'     => '120px',
            'type'      => 'date',
            'index'     => 'from_date',
        ));

        $this->addColumn('to_date', array(
            'header'    => Mage::helper('catalogrule')->__('Date Expire'),
            'align'     => 'left',
            'width'     => '120px',
            'type'      => 'date',
            'default'   => '--',
            'index'     => 'to_date',
        ));

        $this->addColumn('is_active', array(
            'header'    => Mage::helper('catalogrule')->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'is_active',
            'type'      => 'options',
            'options'   => array(
                1 => 'Active',
                0 => 'Inactive',
            ),
        ));
        
        
         $resurce_model = Mage::getModel('b2borderlist/customerrules');
         $customers = $resurce_model->get_customers();
        
       $this->addColumn('email', array(
            'header'    => Mage::helper('salesrule')->__('Individual Customers'),
            'align'     => 'left',
            'width'     => '260px',
            'index'     => 'email',
            'renderer'  => 'b2borderlist/renderers_gridCatalogCustomer',
           // 'filter'    => 'customerpricerules/filters_gridCatalogCustomer',
          //  'options'   => $customers,
        ));

        return parent::_prepareColumns();
    }
    
    public function getRowUrl($row){
    	return $this->getUrl('*/*/edit', array('id' => $row->getRuleId()));
    }

}