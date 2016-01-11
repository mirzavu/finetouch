<?php

class SKJ_Meetmyteam_Block_Adminhtml_Category_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('meetmyteam_form', array('legend'=>Mage::helper('meetmyteam')->__('Category information')));
		 
		$fieldset->addField('title', 'text', array(
				'label'     => Mage::helper('meetmyteam')->__('Category'),
				'class'     => 'required-entry',
				'required'  => true,
				'name'      => 'title',
		));


		$fieldset->addField('description', 'editor', array(
				'name'      => 'description',
				'label'     => Mage::helper('meetmyteam')->__('Description'),
				'title'     => Mage::helper('meetmyteam')->__('Description'),
				'style'     => 'width:700px; height:200px;',
				'wysiwyg'   => false,
				'required'  => false,
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
