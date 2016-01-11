<?php

class Solwin_Manageteam_Block_Index extends Mage_Core_Block_Template {

    public function __construct() {
        parent::__construct();
        $collection = Mage::getModel('manageteam/manageteam')->getCollection();
        $this->setCollection($collection);
    }

    protected function _prepareLayout() {
        parent::_prepareLayout();

        $pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
        $pager->setAvailableLimit(array(8 => 8, 12 => 12, 24 => 24, 'all' => 'all'));
        $pager->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
        return $this;
    }

    public function getPagerHtml() {
        return $this->getChildHtml('pager');
    }

}
