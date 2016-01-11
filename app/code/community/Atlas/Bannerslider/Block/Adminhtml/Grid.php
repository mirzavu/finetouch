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
class Atlas_Bannerslider_Block_Adminhtml_Grid extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {

        $this->_controller = 'adminhtml_bannerslider';
        $this->_blockGroup = 'bannerslider';
        $this->_headerText = 'Banner Slider Managemnet';
        $this->_addButtonLabel = 'Add New Image';
        parent::__construct();
    }

}

?>