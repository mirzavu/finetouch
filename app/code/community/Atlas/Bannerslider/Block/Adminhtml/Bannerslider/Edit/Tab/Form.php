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
class Atlas_Bannerslider_Block_Adminhtml_Bannerslider_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('test_form', array('legend' => 'Image Details'));

        $collectiongroupname = Mage::getModel('bannerslider/bannerslidergroup')->getCollection();

        $groupnamecollection = array();
        foreach ($collectiongroupname as $key => $singlegroupname) :
            //$groupnamecollectionval[] = ;
            $groupnamecollection[$key]['value'] = $singlegroupname->getBannerslidergroupId();
            $groupnamecollection[$key]['label'] = $singlegroupname->getName();
        endforeach;

        $currentbannerId = $this->getRequest()->getParam('id');

        $bannerdelcol = Mage::getModel('bannerslider/bannerslidergrouprelation')->getCollection()->addFieldToFilter('banner_id', $currentbannerId)->addFieldToSelect('group_id')->distinct(TRUE);

        $groupnameselectcollection = array();
        foreach ($bannerdelcol as $key => $singlebannerdelcol) :
            //$groupnamecollectionval[] = ;

            $groupnameselectcollection[] = $singlebannerdelcol->getGroupId();
            //$groupnameselectcollection[$key]['label'] = $singlebannerdelcol->getName();
        endforeach;
        //var_dump($groupnameselectcollection);
        //var_dump($bannerdelcol);
        //      exit;
        //       $bannerdelcol->printLogQuery(true);	
//        exit;
        $bannerdelcoldata = $bannerdelcol->getData();
        $fieldset->addField('img', 'image', array(
            'label' => 'Image',
            'required' => true,
            'name' => 'img',
        ));
        $fieldset->addField('title', 'text', array(
            'label' => 'Title',
            'class' => 'input-text required-entry validate-length maximum-length-25',
            'required' => true,
            'name' => 'title',
        ));


        $fieldset->addField('group_id', 'multiselect', array(
            'name' => 'groups[]',
            'label' => 'Group',
            'title' => 'Group',
            'required' => true,
            'class' => 'required-entry maximum-length-25',
            'values' => $groupnamecollection,
            'required' => true,
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
        $fieldset->addField('url', 'text', array(
            'label' => 'Url',
            'name' => 'url',
        ));
        $fieldset->addField('sort', 'text', array(
            'label' => 'Sort',
            'name' => 'sort',
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

        Mage::registry('test_data')->setGroupId($groupnameselectcollection);


        if (Mage::registry('test_data'))
        {
            $form->setValues(Mage::registry('test_data')->getData());
        }
        return parent::_prepareForm();
    }

}

?>