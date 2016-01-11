<?php
class Hetinfoway_Video_Block_Adminhtml_Video_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("videoGrid");
				$this->setDefaultSort("hetvideo_id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}
		
		protected function _prepareCollection()
		{
				$collection = Mage::getModel("video/video")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("hetvideo_id", array(
				"header" => Mage::helper("video")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "hetvideo_id",
				));
                
				$this->addColumn("vname", array(
				"header" => Mage::helper("video")->__("Video Title"),
				"index" => "vname",
				));
				$this->addColumn("vurl", array(
				"header" => Mage::helper("video")->__("Video URL"),
				"index" => "vurl",
				));
				$this->addColumn("vcode", array(
				"header" => Mage::helper("video")->__("Video Code"),
				"index" => "vcode",
				));
				///////////////////////////////////Category////////////////////////////////////
				  $_cats = Mage::getModel('video/category')->getCollection(); 
				  foreach($_cats as $item)
				  { 
						if($item->getParent == NULL){
							$_categories[$item->getCategoryId()] = $item->getTitle();
						}
				  }
				  $this->addColumn('category', array(
					  'header'    => Mage::helper('video')->__('Category'),
					  'align'     => 'left',
					  'width'     => '80px',
					  'index'     => 'category',
					  'type'      => 'options',
					  'options'   => $_categories,
				  ));
				  ///////////////////////////////////Category////////////////////////////////////
				  
				/*$this->addColumn("vcontent", array(
				"header" => Mage::helper("video")->__("Content"),
				"index" => "vcontent",
				));*/
						$this->addColumn('vfeatured', array(
						'header' => Mage::helper('video')->__('Featured'),
						'index' => 'vfeatured',
						'type' => 'options',
						'options'=>Hetinfoway_Video_Block_Adminhtml_Video_Grid::getOptionArray6(),				
						));
						
						$this->addColumn('status', array(
						  'header'    => Mage::helper('video')->__('Status'),
						  'align'     => 'left',
						  'width'     => '80px',
						  'index'     => 'status',
						  'type'      => 'options',
						  'options'   => array(
							  1 => 'Enabled',
							  2 => 'Disabled',
						  ),
					  ));
						
				$this->addColumn("vsort_order", array(
				"header" => Mage::helper("video")->__("Sort Order"),
				"index" => "vsort_order",
				));
					$this->addColumn('vcreated_time', array(
						'header'    => Mage::helper('video')->__('Created Time'),
						'index'     => 'vcreated_time',
						'type'      => 'datetime',
					));
					$this->addColumn('vupdate_time', array(
						'header'    => Mage::helper('video')->__('Update Time'),
						'index'     => 'vupdate_time',
						'type'      => 'datetime',
					));
					
					
					$this->addColumn('action',
            array(
                'header'    =>  Mage::helper('video')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('video')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
        
			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('hetvideo_id');
			$this->getMassactionBlock()->setFormFieldName('video');
			$this->getMassactionBlock()->addItem('delete', array(
				 'label'    => Mage::helper('video')->__('Delete'),
				 'url'      => $this->getUrl('*/*/massDelete'),
				 'confirm'  => Mage::helper('video')->__('Are you sure?')
				));
				
				$statuses = Mage::getSingleton('video/status')->getOptionArray();

				array_unshift($statuses, array('label'=>'', 'value'=>''));
				$this->getMassactionBlock()->addItem('status', array(
					 'label'=> Mage::helper('video')->__('Change status'),
					 'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
					 'additional' => array(
							'visibility' => array(
								 'name' => 'status',
								 'type' => 'select',
								 'class' => 'required-entry',
								 'label' => Mage::helper('video')->__('Status'),
								 'values' => $statuses
							 )
					 )
				));       
			return $this;
		}
		static public function getOptionArray6()
		{
            $data_array=array(); 
			$data_array[0]='Yes';
			$data_array[1]='No';
            return($data_array);
		}
		static public function getValueArray6()
		{
            $data_array=array();
			foreach(Hetinfoway_Video_Block_Adminhtml_Video_Grid::getOptionArray6() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);
		}
}
