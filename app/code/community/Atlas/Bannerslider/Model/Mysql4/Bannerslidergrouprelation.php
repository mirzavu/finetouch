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
class Atlas_Bannerslider_Model_Mysql4_Bannerslidergrouprelation extends Mage_Core_Model_Mysql4_Abstract
{

    public function _construct()
    {
        //parent::_construct();
        $this->_init('bannerslider/bannerslidergrouprelation', 'bannerslidergroup_relation_id');
    }

}

?>
