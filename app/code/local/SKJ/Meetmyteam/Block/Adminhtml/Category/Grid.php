<?php

class SKJ_Meetmyteam_Block_Adminhtml_Category_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
			

		parent::__construct();
		$this->setId('meetmyteamGrid');
		$this->setDefaultSort('meetmyteam_cat_id');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection()
	{
		$collection = Mage::getModel('meetmyteam/category')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns()
	{
		$this->addColumn('meetmyteam_cat_id', array(
				'header'    => Mage::helper('meetmyteam')->__('ID'),
				'align'     =>'right',
				'width'     => '50px',
				'index'     => 'meetmyteam_cat_id',
		));

		$this->addColumn('title', array(
				'header'    => Mage::helper('meetmyteam')->__('Category'),
				'align'     =>'left',
				'index'     => 'title',
		));


		$this->addColumn('status', array(
				'header'    => Mage::helper('meetmyteam')->__('Status'),
				'align'     => 'left',
				'width'     => '80px',
				'index'     => 'status',
				'type'      => 'options',
				'options'   => array(
						1 => 'Enabled',
						2 => 'Disabled',
				),
		));			

		$this->addColumn('action',
				array(
						'header'    => Mage::helper('newsletter')->__('Action'),
						'index'     =>'meetmyteam_cat_id',
						'sortable' =>false,
						'filter'   => false,
						'no_link' => false,
						'width'	   => '170px',
						'renderer'=> new SKJ_Meetmyteam_Block_Adminhtml_Renderer_Preview(),
				));




		$this->addExportType('*/*/exportCsv', Mage::helper('meetmyteam')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('meetmyteam')->__('XML'));
			
		return parent::_prepareColumns();
	}

	protected function _prepareMassaction()
	{
		$this->setMassactionIdField('meetmyteam_cat_id');
		$this->getMassactionBlock()->setFormFieldName('meetmyteam');

		$this->getMassactionBlock()->addItem('delete', array(
				'label'    => Mage::helper('meetmyteam')->__('Delete'),
				'url'      => $this->getUrl('*/*/massDelete'),
				'confirm'  => Mage::helper('meetmyteam')->__('Are you sure?')
		));

		$statuses = Mage::getSingleton('meetmyteam/status')->getOptionArray();

		array_unshift($statuses, array('label'=>'', 'value'=>''));
		$this->getMassactionBlock()->addItem('status', array(
				'label'=> Mage::helper('meetmyteam')->__('Change status'),
				'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
				'additional' => array(
						'visibility' => array(
								'name' => 'status',
								'type' => 'select',
								'class' => 'required-entry',
								'label' => Mage::helper('meetmyteam')->__('Status'),
								'values' => $statuses
						)
				)
		));
		return $this;
	}

	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}

}
