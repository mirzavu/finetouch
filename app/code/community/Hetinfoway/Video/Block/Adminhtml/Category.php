<?php
 
class Hetinfoway_Video_Block_Adminhtml_Category extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_category';
    $this->_blockGroup = 'video';
    $this->_headerText = Mage::helper('video')->__('Video Category Manager');
    $this->_addButtonLabel = Mage::helper('video')->__('Add Category');
    parent::__construct();
  }
  
  
}
