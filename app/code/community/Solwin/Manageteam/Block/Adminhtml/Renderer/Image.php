<?php

class Solwin_Manageteam_Block_Adminhtml_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $mediaurl = '';
        $value = $row->getData($this->getColumn()->getIndex());
        if ($value) {
            $mediaurl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . $value;
        } else {
            $mediaurl = $this->getSkinUrl() . 'images/manageteam/manageteam.jpg';
        }
        return '<p style="text-align:center;padding-top:5px;"><img src="' . $mediaurl . '"  style="width:80px;height:80px;text-align:center;"/></p>';
    }

}
