<?php

/**
 * Atlas Extensions
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Atlas Commercial License
 * that is available through the world-wide-web at this URL:
 *
 * @copyright   Copyright (c) 2015 Atlas Extensions
 * @license     Commercial
 */
class Atlas_Bannerslider_Block_Adminhtml_Bannerslidergroup_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('test_form', array('legend' => 'Group Details'));

        $fieldset->addField('name', 'text', array(
            'label' => 'Name',
            'class' => 'input-text required-entry validate-length maximum-length-25',
            'required' => true,
            'name' => 'name',
        ));


        if (!Mage::app()->isSingleStoreMode())
        {
            $fieldset->addField('store_id', 'multiselect', array(
                'name' => 'stores[]',
                'label' => 'Store View',
                'title' => 'Store View',
                'required' => true,
                'values' => Mage::getSingleton('adminhtml/system_store')
                        ->getStoreValuesForForm(false, true),
            ));
        }
        else
        {
            $fieldset->addField('store_id', 'hidden', array(
                'name' => 'stores[]',
                'value' => Mage::app()->getStore(true)->getId()
            ));
        }


        $fieldset->addField('status', 'select', array(
            'label' => 'Status',
            'class' => 'required-entry',
            'name' => 'status',
            'required' => true,
            'values' => array(
                array(
                    'value' => 1,
                    'label' => 'Enable',
                ),
                array(
                    'value' => 0,
                    'label' => 'Disable',
                ),
            ),
        ));



        if (Mage::registry('test_data'))
        {
            $form->setValues(Mage::registry('test_data')->getData());
        }
        return parent::_prepareForm();
    }

}

?>