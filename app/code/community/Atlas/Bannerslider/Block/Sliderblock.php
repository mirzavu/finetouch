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

class Atlas_Bannerslider_Block_Sliderblock extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
{

    public function _construct()
    {
        parent::_construct();
        $this->addData(array(
            'cache_lifetime' => 86400,
            'cache_tags' => array(Mage_Core_Model_Store::CACHE_TAG . 'atlas_bannner' . $this->getGroupid()),
            'cache_key' => 'atlas_bannner' . $this->getGroupid(),
        ));
    }

    public function sample()
    {
        //var_dump($this->getData());
        $groupid = $this->getGroupid();
        $store_id = Mage::app()->getStore()->getId();
        $store_id_arr = array();
        $store_id_arr[] = 0;
        $store_id_arr[] = $store_id;

        //check group 
        $groupstatus = Mage::getModel('bannerslider/bannerslidergroup')->load($groupid);
        $statusgrp = $groupstatus->getStatus();
        if ($statusgrp == 1)
        {
            //check group store id 
            $var1 = $groupstatus->getStoreId();
            $data1 = strpos($var1, $store_id);
            if ($data1 !== false || $var1 == 0)
            {
                //get assign banner id into current group
                $banneridcollection = Mage::getModel('bannerslider/bannerslidergrouprelation')->getCollection()->addFieldToFilter('group_id', $groupid)->addFieldToSelect('banner_id')->distinct(TRUE);
                foreach ($banneridcollection as $key => $singlebanneridcollection) :
                    $bannerid[] = $singlebanneridcollection->getBannerId();
                endforeach;
                // get banner collection
                if (Mage::getStoreConfig('bannerconfig/banneroption/banner_sort') == 1)
                {
                    if (Mage::getStoreConfig('bannerconfig/banneroption/banner_sort_type') == 1)
                    {
                        //$collection = Mage::getModel('bannerslider/bannerslider')->getCollection()->addFieldtoFilter('status', '1')->addFieldtoFilter('bannerslider_id', $bannerid)->addFieldtoFilter('store_id', $store_id_arr)->setOrder('sort', 'DESC');
                        $collection = Mage::getModel('bannerslider/bannerslider')->getCollection()->addFieldtoFilter('status', '1')->addFieldtoFilter('bannerslider_id', array('in' => $bannerid));
                        $collection->getSelect()->where('store_id = 0 OR store_id RLIKE ?', '[[:<:]]' . $store_id . '[[:>:]]')->order('sort', 'DESC');
                    }
                    else
                    {
                        //$collection = Mage::getModel('bannerslider/bannerslider')->getCollection()->addFieldtoFilter('status', '1')->addFieldtoFilter('bannerslider_id', $bannerid)->addFieldtoFilter('store_id', $store_id_arr)->setOrder('sort', 'ASC');	
                        $collection = Mage::getModel('bannerslider/bannerslider')->getCollection()->addFieldtoFilter('status', '1')->addFieldtoFilter('bannerslider_id', array('in' => $bannerid));
                        $collection->getSelect()->where('store_id = 0 OR store_id RLIKE ?', '[[:<:]]' . $store_id . '[[:>:]]')->order('sort', 'ASC');
                    }
                }
                else
                {

                    //$collection = Mage::getModel('bannerslider/bannerslider')->getCollection()->addFieldtoFilter('status', '1')->addFieldtoFilter('bannerslider_id', $bannerid)->addFieldtoFilter('store_id', $store_id_arr);
                    $collection = Mage::getModel('bannerslider/bannerslider')->getCollection()->addFieldtoFilter('status', '1')->addFieldtoFilter('bannerslider_id', array('in' => $bannerid));
                    $collection->getSelect()->where('store_id = 0 OR store_id RLIKE ?', '[[:<:]]' . $store_id . '[[:>:]]');
                    //$collection->printLogQuery(true);
                }
            }
        }
        else
        {
            $collection = '';
        }
        return $collection;
    }

}

?>