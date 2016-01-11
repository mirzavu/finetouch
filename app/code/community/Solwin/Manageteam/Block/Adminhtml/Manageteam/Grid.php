<?php

class Solwin_Manageteam_Block_Adminhtml_Manageteam_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId("manageteamGrid");
        $this->setDefaultSort("manageteam_id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel("manageteam/manageteam")->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn("manageteam_id", array(
            "header" => Mage::helper("manageteam")->__("ID"),
            "align" => "right",
            "width" => "50px",
            "type" => "number",
            "index" => "manageteam_id",
        ));

        $this->addColumn("image", array(
            "header" => Mage::helper("manageteam")->__("Image"),
            "index" => "image",
            "width" => "100px",
            "renderer" => "Solwin_Manageteam_Block_Adminhtml_Renderer_Image",
        ));

        $this->addColumn("name", array(
            "header" => Mage::helper("manageteam")->__("Name"),
            "index" => "name",
        ));
        $this->addColumn("designation", array(
            "header" => Mage::helper("manageteam")->__("Designation"),
            "index" => "designation",
        ));
        $this->addColumn('status', array(
            'header' => Mage::helper('manageteam')->__('Status'),
            'index' => 'status',
            'type' => 'options',
            'options' => Solwin_Manageteam_Block_Adminhtml_Manageteam_Grid::getOptionArray6(),
        ));

        $this->addColumn("facebookurl", array(
            "header" => Mage::helper("manageteam")->__("Facebook Url"),
            "index" => "facebookurl",
        ));
        $this->addColumn("googleurl", array(
            "header" => Mage::helper("manageteam")->__("Google+ Url"),
            "index" => "googleurl",
        ));
        $this->addColumn("twitterurl", array(
            "header" => Mage::helper("manageteam")->__("Twitter Url"),
            "index" => "twitterurl",
        ));
        $this->addColumn("email", array(
            "header" => Mage::helper("manageteam")->__("Email"),
            "index" => "email",
        ));
        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        return $this->getUrl("*/*/edit", array("id" => $row->getId()));
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('manageteam_id');
        $this->getMassactionBlock()->setFormFieldName('manageteam_ids');
        $this->getMassactionBlock()->setUseSelectAll(true);
        $this->getMassactionBlock()->addItem('remove_manageteam', array(
            'label' => Mage::helper('manageteam')->__('Remove Manageteam'),
            'url' => $this->getUrl('*/adminhtml_manageteam/massRemove'),
            'confirm' => Mage::helper('manageteam')->__('Are you sure?')
        ));
        return $this;
    }

    static public function getOptionArray6() {
        $data_array = array();
        $data_array[0] = 'Enable';
        $data_array[1] = 'Disable';
        return($data_array);
    }

    static public function getValueArray6() {
        $data_array = array();
        foreach (Solwin_Manageteam_Block_Adminhtml_Manageteam_Grid::getOptionArray6() as $k => $v) {
            $data_array[] = array('value' => $k, 'label' => $v);
        }
        return($data_array);
    }

}
