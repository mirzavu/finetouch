<?php
class Hetinfoway_Video_Block_Adminhtml_Category_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('category_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('video')->__('Category Information'));
  }
  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('video')->__('Category Information'),
          'title'     => Mage::helper('video')->__('Category Information'),
          'content'   => $this->getLayout()->createBlock('video/adminhtml_category_edit_tab_form')->toHtml(),
      ));
      return parent::_beforeToHtml();
  }
}
