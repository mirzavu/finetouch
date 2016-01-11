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
class Atlas_Bannerslider_Model_Mysql4_Bannerslider_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('bannerslider/bannerslider');
    }

    public function addStoreFilter($store, $withAdmin = true)
    {

        if ($store instanceof Mage_Core_Model_Store)
        {
            $store = array($store->getId());
        }

        if (!is_array($store))
        {
            $store = array($store);
        }

        $this->addFilter('store_id', array('in' => $store));

        return $this;
    }

}

?>
