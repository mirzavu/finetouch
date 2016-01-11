<?php

class SKJ_Meetmyteam_Block_Adminhtml_Category extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_category';
    $this->_blockGroup = 'meetmyteam';
    $this->_headerText = Mage::helper('meetmyteam')->__('My team category Manager');
    $this->_addButtonLabel = Mage::helper('meetmyteam')->__('Add Item');
    parent::__construct();
  }
}