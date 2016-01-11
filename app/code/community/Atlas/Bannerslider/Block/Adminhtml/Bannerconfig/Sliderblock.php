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
class Atlas_Bannerslider_Block_Sliderblock extends Mage_Core_Block_Template
{

    public function methoddblock()
    {

        $collection = Mage::getModel('bannerslider/bannerslider')->getCollection()
                ->addFieldToFilter('status', '1');

        return $collection;
    }

}

?>