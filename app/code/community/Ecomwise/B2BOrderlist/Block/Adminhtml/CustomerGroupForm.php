<?php 
class Ecomwise_B2BOrderlist_Block_Adminhtml_CustomerGroupForm extends Mage_Adminhtml_Block_Customer_Group_Edit_Form{
	
	
	protected function _prepareLayout()
	{
		parent::_prepareLayout();
		$form = new Varien_Data_Form();
		$customerGroup = Mage::registry('current_group');
	
		$fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('customer')->__('Group Information')));
	
		$validateClass = sprintf('required-entry validate-length maximum-length-%d',
				Mage_Customer_Model_Group::GROUP_CODE_MAX_LENGTH);
		$name = $fieldset->addField('customer_group_code', 'text',
				array(
						'name'  => 'code',
						'label' => Mage::helper('customer')->__('Group Name'),
						'title' => Mage::helper('customer')->__('Group Name'),
						'note'  => Mage::helper('customer')->__('Maximum length must be less then %s symbols', Mage_Customer_Model_Group::GROUP_CODE_MAX_LENGTH),
						'class' => $validateClass,
						'required' => true,
				)
		);
	
		if ($customerGroup->getId()==0 && $customerGroup->getCustomerGroupCode() ) {
			$name->setDisabled(true);
		}
	
		$fieldset->addField('tax_class_id', 'select',
				array(
						'name'  => 'tax_class',
						'label' => Mage::helper('customer')->__('Tax Class'),
						'title' => Mage::helper('customer')->__('Tax Class'),
						'class' => 'required-entry',
						'required' => true,
						'values' => Mage::getSingleton('tax/class_source_customer')->toOptionArray()
				)
		);
		
		$fieldset->addField('hide_prices_for_store', 'multiselect',
				array(
						'name'  => 'hide_prices_for_store[]',
						'label' => Mage::helper('customer')->__('Hide Prices In Stores'),
						'title' => Mage::helper('customer')->__('Hide Prices In Stores'),
						//'class' => 'required-entry',
						'required' => false,
						'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true)
				)
		);
	
		if (!is_null($customerGroup->getId())) {
			// If edit add id
			$form->addField('id', 'hidden',
					array(
							'name'  => 'id',
							'value' => $customerGroup->getId(),
					)
			);
		}
	
		if( Mage::getSingleton('adminhtml/session')->getCustomerGroupData() ) {
			$form->addValues(Mage::getSingleton('adminhtml/session')->getCustomerGroupData());
			Mage::getSingleton('adminhtml/session')->setCustomerGroupData(null);
		} else {
			$form->addValues($customerGroup->getData());
		}
	
		$form->setUseContainer(true);
		$form->setId('edit_form');
		$form->setAction($this->getUrl('*/*/save'));
		$this->setForm($form);
	}
} 