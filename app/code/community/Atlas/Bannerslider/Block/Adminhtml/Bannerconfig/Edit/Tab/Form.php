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
class Atlas_Bannerslider_Block_Adminhtml_Bannerconfig_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('test_form', array('legend' => 'Image Details'));
        $fieldset->addField('img', 'image', array(
            'label' => 'Image',
            'class' => 'required-entry',
            'required' => true,
            'name' => 'img',
        ));
        $fieldset->addField('title', 'text', array(
            'label' => 'Title',
            'class' => 'required-entry',
            'required' => true,
            'name' => 'title',
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

        $fieldset->addField('description', 'text', array(
            'label' => 'Description',
            'class' => 'required-entry',
            'required' => true,
            'name' => 'description',
        ));
        $fieldset->addField('url', 'text', array(
            'label' => 'Url',
            'class' => 'required-entry',
            'required' => true,
            'name' => 'url',
        ));

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