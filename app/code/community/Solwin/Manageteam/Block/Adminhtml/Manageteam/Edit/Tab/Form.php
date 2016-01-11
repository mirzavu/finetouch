<?php

class Solwin_Manageteam_Block_Adminhtml_Manageteam_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {

        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset("manageteam_form", array("legend" => Mage::helper("manageteam")->__("Item information")));


        $fieldset->addField("name", "text", array(
            "label" => Mage::helper("manageteam")->__("Name"),
            "class" => "required-entry",
            "required" => true,
            "name" => "name",
        ));

        $fieldset->addField('image', 'image', array(
            'label' => Mage::helper('manageteam')->__('Image'),
            'name' => 'image',
            'note' => '(*.jpg, *.png, *.gif)',
        ));
        $fieldset->addField("designation", "text", array(
            "label" => Mage::helper("manageteam")->__("Designation"),
            "name" => "designation",
        ));

        $fieldset->addField("description", "editor", array(
            "label" => Mage::helper("manageteam")->__("Description"),
            "name" => "description",
            'style' => 'height:15em',
            'config' => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
            'wysiwyg' => true,
        ));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('manageteam')->__('Status'),
            'values' => Solwin_Manageteam_Block_Adminhtml_Manageteam_Grid::getValueArray6(),
            'name' => 'status',
        ));
        $fieldset->addField("facebookurl", "text", array(
            "label" => Mage::helper("manageteam")->__("Facebook Url"),
            "name" => "facebookurl",
        ));

        $fieldset->addField("googleurl", "text", array(
            "label" => Mage::helper("manageteam")->__("Google+ Url"),
            "name" => "googleurl",
        ));

        $fieldset->addField("twitterurl", "text", array(
            "label" => Mage::helper("manageteam")->__("Twitter Url"),
            "name" => "twitterurl",
        ));

        $fieldset->addField("email", "text", array(
            "label" => Mage::helper("manageteam")->__("Email"),
            "name" => "email",
        ));


        if (Mage::getSingleton("adminhtml/session")->getManageteamData()) {
            $form->setValues(Mage::getSingleton("adminhtml/session")->getManageteamData());
            Mage::getSingleton("adminhtml/session")->setManageteamData(null);
        } elseif (Mage::registry("manageteam_data")) {
            $form->setValues(Mage::registry("manageteam_data")->getData());
        }
        return parent::_prepareForm();
    }

}
