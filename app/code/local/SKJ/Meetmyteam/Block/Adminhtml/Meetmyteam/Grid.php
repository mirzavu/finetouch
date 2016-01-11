<?php

class SKJ_Meetmyteam_Block_Adminhtml_Meetmyteam_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('meetmyteamGrid');
		$this->setDefaultSort('meetmyteam_id');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection()
	{
		$collection = Mage::getModel('meetmyteam/meetmyteam')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns()
	{
		
		$catCollection = Mage::getModel('meetmyteam/category')->getCollection();
		$catCollection->addFieldToFilter('status',Array('eq'=>1));
		
		foreach($catCollection as $cat)
		{
			$key = $cat['meetmyteam_cat_id'];
			$categoryOption[$key] = $cat->getTitle();
		}
		
		$this->addColumn('meetmyteam_id', array(
				'header'    => Mage::helper('meetmyteam')->__('ID'),
				'align'     =>'right',
				'width'     => '50px',
				'index'     => 'meetmyteam_id',
		));

		$this->addColumn('title', array(
				'header'    => Mage::helper('meetmyteam')->__('Name'),
				'align'     =>'left',
				'index'     => 'title',
		));


		$this->addColumn('description', array(
				'header'    => Mage::helper('meetmyteam')->__('Designation'),
				'width'     => '250px',
				'index'     => 'description',
		));

		$this->addColumn('filename', array(
				'header'    => Mage::helper('meetmyteam')->__('Image'),
				'width'     => '200px',
				'index'     => 'meetmyteam_id',
				'renderer'=> new SKJ_Meetmyteam_Block_Adminhtml_Renderer_Image(),
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

		$this->addColumn('category', array(
				'header'    => Mage::helper('customer')->__('Category'),
				'width'     => '200px',
				'index'     => 'category',
				'type'  	=> 'options',
				'options' 	=> $categoryOption,
		));
		 
		 
		$this->addColumn('action',
				array(
						'header'    =>  Mage::helper('meetmyteam')->__('Action'),
						'width'     => '100',
						'type'      => 'action',
						'getter'    => 'getId',
						'actions'   => array(
								array(
										'caption'   => Mage::helper('meetmyteam')->__('Edit'),
										'url'       => array('base'=> '*/*/edit'),
										'field'     => 'id'
								)
						),
						'filter'    => false,
						'sortable'  => false,
						'index'     => 'stores',
						'is_system' => true,
				));

		$this->addExportType('*/*/exportCsv', Mage::helper('meetmyteam')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('meetmyteam')->__('XML'));
		 
		return parent::_prepareColumns();
	}

	protected function _prepareMassaction()
	{
		$this->setMassactionIdField('meetmyteam_id');
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
