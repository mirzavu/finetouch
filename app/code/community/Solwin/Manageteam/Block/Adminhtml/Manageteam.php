<?php


class Solwin_Manageteam_Block_Adminhtml_Manageteam extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_manageteam";
	$this->_blockGroup = "manageteam";
	$this->_headerText = Mage::helper("manageteam")->__("Manage Team");
	$this->_addButtonLabel = Mage::helper("manageteam")->__("Add New Member");
	parent::__construct();
	
	}

}