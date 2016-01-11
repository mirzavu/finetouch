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
class Atlas_Bannerslider_Block_Adminhtml_Bannerslider_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'bannerslider';
        $this->_controller = 'adminhtml_bannerslider';
        $this->_updateButton('save', 'label', 'Save Image');
        $this->_updateButton('delete', 'label', 'Delete Image');
        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
                ), -100);
        $this->_formScripts[] = "

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }

           if (!document.getElementById('img_image')) {
            document.getElementById('img').classList.add('required-entry');
           }

        ";
    }

    public function getHeaderText()
    {
        if (Mage::registry('test_data') && Mage::registry('test_data')->getId())
        {
            return 'Update Image banner ' . $this->htmlEscape(
                            Mage::registry('test_data')->getTitle()) . '<br />';
        }
        else
        {
            return 'Add New Image';
        }
    }

}

?>