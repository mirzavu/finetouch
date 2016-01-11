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
class Atlas_Bannerslider_Block_Adminhtml_Bannerslider_Grid_Renderer_Groupname extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {
        return $this->_getValue($row);
    }

    protected function _getValue(Varien_Object $row)
    {
        $val = $row->getData($this->getColumn()->getIndex());
        $val = explode(',', $val);
        $valid = $row->getBannersliderId();
        $bannerdelcol = Mage::getModel('bannerslider/bannerslidergrouprelation')->getCollection()->addFieldToFilter('banner_id', $valid)->addFieldToSelect('group_id')->distinct(TRUE);
        $groupnameselectcollection = array();
        foreach ($bannerdelcol as $key => $singlebannerdelcol) :
            $groupid[] = $singlebannerdelcol->getGroupId();
        endforeach;

        $collection = Mage::getModel('bannerslider/bannerslidergroup')->getCollection()->addFieldToFilter('bannerslidergroup_id', array('in' => $groupid));
        $colsize = $collection->getSize();
        $coldata = $collection->getData();
        $i = 0;
        $out = '';
        foreach ($coldata as $singledata)
        {
            if ($colsize > 1)
            {
                if ($i < 1)
                {
                    $out .= $singledata['name'];
                }
                else
                {
                    $out .= "," . $singledata['name'];
                }
            }
            else
            {
                $out = $singledata['name'];
            }
            $i++;
        }

        if (empty($out))
        {
            $out = "N/A";
        }
        return $out;
    }

}

?>