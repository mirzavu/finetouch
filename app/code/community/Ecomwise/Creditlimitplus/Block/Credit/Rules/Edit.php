<?php 
class Ecomwise_Creditlimitplus_Block_Credit_Rules_Edit extends Mage_Adminhtml_Block_Widget_Form_Container{
	
   public function __construct()
   {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'ecomwisecreditplus';
        $this->_controller = 'credit_rules';
        $this->_updateButton('save', 'label','Save Reference');
       /*  $this->_updateButton('delete', 'label', 'delete reference'); */        
       // $this->removeButton('delete');        
    }
       
    public function getHeaderText()
    {
        if( Mage::registry('creditlimit')&&Mage::registry('creditlimit')->getId())
         {             
         	return Mage::helper('ecomwisecreditplus')->__('Edit Rule');
         }
         else
         {
             return Mage::helper('ecomwisecreditplus')->__('Create Rule');
         }
    }
}