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
class Atlas_Bannerslider_Block_Adminhtml_Bannerconfig_Edit extends
Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        //vwe assign the same blockGroup as the Grid Container
        $this->_blockGroup = 'bannerconfig';
        //and the same controller
        $this->_controller = 'adminhtml_bannerconfig';
        //define the label for the save and delete button
        $this->_updateButton('save', 'label', 'Save Image');
    }

    /* Here, we're looking if we have transmitted a form object,
      to update the good text in the header of the page (edit or add) */

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