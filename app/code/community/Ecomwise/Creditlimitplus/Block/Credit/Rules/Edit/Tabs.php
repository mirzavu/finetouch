<?php
class Ecomwise_Creditlimitplus_Block_Credit_Rules_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('credit_limit_plus');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('ecomwisecreditplus')->__('Limits'));
    }
}
