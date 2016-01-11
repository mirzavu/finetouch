<?php

class SKJ_Meetmyteam_Block_Adminhtml_Meetmyteam_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	

	/**
	 * Preparing global layout
	 *
	 * You can redefine this method in child classes for changin layout
	 *
	 * @return Mage_Core_Block_Abstract
	 */
	
	/*
	protected function _prepareLayout()
	{
		parent::_prepareLayout();
		if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
			$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
		}
	}
	*/
	
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('meetmyteam_form', array('legend'=>Mage::helper('meetmyteam')->__('Item information')));
           
      // Get category collection
      $optioncat[''] = 'Select category';
      
      $categoryCollection = Mage::getModel('meetmyteam/category')->getCollection()
      ->addFieldToFilter('status' , '1');
      ;
      
      foreach($categoryCollection as $category){
      	$optioncat[$category->getID()] = $category->getTitle();
      }
      
      $fieldset->addField('category', 'select', array(
      		'label'     => Mage::helper('meetmyteam')->__('category'),
      		'name'      => 'category',
      		'values'    => $optioncat,
      		'required'  => true,
      ));
           
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('meetmyteam')->__('Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));
      
      $fieldset->addField('description', 'text', array(
      		'label'     => Mage::helper('meetmyteam')->__('Designation'),
      		'class'     => 'required-entry',
      		'required'  => true,
      		'name'      => 'description',
      ));
       
      
      $fieldset->addField('filename', 'image', array(
          'label'     => Mage::helper('meetmyteam')->__('Image'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('meetmyteam')->__('Status'),
          'name'      => 'status',
			'required'  => true,
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('meetmyteam')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('meetmyteam')->__('Disabled'),
              ),
          ),
      ));
     
      
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('meetmyteam')->__('Content'),
          'title'     => Mage::helper('meetmyteam')->__('Content'),
          'style'     => 'width:700px; height:300px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
      
      /*
      $fieldset->addField('content', 'editor', array(
      		'name'      => 'content',
      		'label'     => Mage::helper('meetmyteam')->__('content'),
      		'title'     => Mage::helper('meetmyteam')->__('content'),
      		'style'     => 'width:100%;height:300px;',
      		'required'  => true,
      		'config'    => Mage::getSingleton('meetmyteam/wysiwyg_config')->getConfig()
      ));
      */
     
      if ( Mage::getSingleton('adminhtml/session')->getMeetmyteamData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getMeetmyteamData());
          Mage::getSingleton('adminhtml/session')->setMeetmyteamData(null);
      } elseif ( Mage::registry('meetmyteam_data') ) {
          $form->setValues(Mage::registry('meetmyteam_data')->getData());
      }
      return parent::_prepareForm();
  }
}
