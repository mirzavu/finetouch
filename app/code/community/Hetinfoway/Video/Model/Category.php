<?php 
class Hetinfoway_Video_Model_Category extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('video/category');
    }
	public function getCollection() {
        return Mage::getResourceModel('video/category_collection');
    }
}
