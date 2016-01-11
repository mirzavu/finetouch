<?php
class Hetinfoway_Video_Block_Adminhtml_Category_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('category_form', array('legend'=>Mage::helper('video')->__('Category information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('video')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));
	  
 
      $fieldset->addField('filename', 'image', array(
          'label'     => Mage::helper('video')->__('Image'),
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('video')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('video')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('video')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('video')->__('Content'),
          'title'     => Mage::helper('video')->__('Content'),
          'style'     => 'width:700px; height:200px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getCategoryData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getCategoryData());
          Mage::getSingleton('adminhtml/session')->setCategoryData(null);
      } elseif ( Mage::registry('category_data') ) {
          $form->setValues(Mage::registry('category_data')->getData());
      }
      return parent::_prepareForm();
  }
}
