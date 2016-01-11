<?php

class SKJ_Meetmyteam_Block_Adminhtml_Meetmyteam_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'meetmyteam';
        $this->_controller = 'adminhtml_meetmyteam';
        
        $this->_updateButton('save', 'label', Mage::helper('meetmyteam')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('meetmyteam')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('meetmyteam_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'meetmyteam_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'meetmyteam_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('meetmyteam_data') && Mage::registry('meetmyteam_data')->getId() ) {
            return Mage::helper('meetmyteam')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('meetmyteam_data')->getTitle()));
        } else {
            return Mage::helper('meetmyteam')->__('Add Item');
        }
    }
}