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
class Atlas_Bannerslider_Block_Adminhtml_Bannerconfig_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('bannerconfig_tabs');
        $this->setDestElementId('config_form');
        $this->setTitle('Banner Slider Configuration');
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label' => 'Configuration',
            'title' => 'Configuration',
            'content' => $this->getLayout()
                    ->createBlock('bannerslider/adminhtml_bannerconfig_edit_tab_form')
                    ->toHtml()
        ));
        return parent::_beforeToHtml();
    }

}

?>