<?php

class Solwin_Manageteam_Block_Adminhtml_Manageteam_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId("manageteam_tabs");
        $this->setDestElementId("edit_form");
        $this->setTitle(Mage::helper("manageteam")->__("Item Information"));
    }

    protected function _beforeToHtml() {
        $this->addTab("form_section", array(
            "label" => Mage::helper("manageteam")->__("Item Information"),
            "title" => Mage::helper("manageteam")->__("Item Information"),
            "content" => $this->getLayout()->createBlock("manageteam/adminhtml_manageteam_edit_tab_form")->toHtml(),
        ));
        return parent::_beforeToHtml();
    }

}
