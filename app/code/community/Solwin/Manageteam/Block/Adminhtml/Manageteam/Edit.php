<?php

class Solwin_Manageteam_Block_Adminhtml_Manageteam_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {

        parent::__construct();
        $this->_objectId = "manageteam_id";
        $this->_blockGroup = "manageteam";
        $this->_controller = "adminhtml_manageteam";
        $this->_updateButton("save", "label", Mage::helper("manageteam")->__("Save Item"));
        $this->_updateButton("delete", "label", Mage::helper("manageteam")->__("Delete Item"));

        $this->_addButton("saveandcontinue", array(
            "label" => Mage::helper("manageteam")->__("Save And Continue Edit"),
            "onclick" => "saveAndContinueEdit()",
            "class" => "save",
                ), -100);

        $this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
    }

    protected function _prepareLayout() {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }

    public function getHeaderText() {
        if (Mage::registry("manageteam_data") && Mage::registry("manageteam_data")->getId()) {

            return Mage::helper("manageteam")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("manageteam_data")->getId()));
        } else {

            return Mage::helper("manageteam")->__("Add Item");
        }
    }

}
