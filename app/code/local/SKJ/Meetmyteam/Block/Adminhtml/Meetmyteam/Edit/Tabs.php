<?php

class SKJ_Meetmyteam_Block_Adminhtml_Meetmyteam_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('meetmyteam_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('meetmyteam')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('meetmyteam')->__('Item Information'),
          'title'     => Mage::helper('meetmyteam')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('meetmyteam/adminhtml_meetmyteam_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}