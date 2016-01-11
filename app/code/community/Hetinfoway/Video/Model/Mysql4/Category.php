<?php
class Hetinfoway_Video_Model_Mysql4_Category extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init("video/category", "category_id");
    }
}
