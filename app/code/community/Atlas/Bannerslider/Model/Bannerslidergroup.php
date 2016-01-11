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
class Atlas_Bannerslider_Model_Bannerslidergroup extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('bannerslider/bannerslidergroup');
    }

    public function toOptionArray()
    {
        return $this->getAttributeSetList();
    }

    public function getAttributeSetList()
    {
        $collection = $this->getCollection()->addFieldToFilter("status", 1);
        $data = $collection->getData();
        $result = array();
        $result[] = array('value' => '', 'label' => 'Please choose Group');
        foreach ($data as $value)
            $result[] = array('value' => $value['bannerslidergroup_id'], 'label' => $value['name']);
        return $result;
    }

}

?>