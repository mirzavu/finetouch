<?php
class Hetinfoway_Video_Block_Adminhtml_Video_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{
				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("video_form", array("legend"=>Mage::helper("video")->__("Video information")));
						
						 $_cats = Mage::getModel('video/category')->getCollection(); 
						  foreach($_cats as $item)
						  { 
								if($item->getParent == NULL){
									$_categories[] = array(
												'value'     => $item->getCategoryId(),
												'label'     => $item->getTitle(),
											);
								}
						  }
						 
						 $fieldset->addField('category', 'select', array(
							  'label'     => Mage::helper('video')->__('Category'),
							  'class'     => 'required-entry',
							  'required'  => true,
							  'name'      => 'category',
							  'values'    => $_categories,
						  ));
				
						$fieldset->addField("vname", "text", array(
						"label" => Mage::helper("video")->__("Video Title"),
						"name" => "vname",
						));
					
						$fieldset->addField("vurl", "text", array(
						"label" => Mage::helper("video")->__("Video URL"),
						"name" => "vurl",
						));
					
						$fieldset->addField("vcode", "text", array(
						"label" => Mage::helper("video")->__("Video Code"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "vcode",
						));
						$fieldset->addField('vimage', 'image', array(
						'label' => Mage::helper('video')->__('Image'),
						'name' => 'vimage',
						'note' => '(*.jpg, *.png, *.gif)',
						));	
						/*$fieldset->addField("vcontent", "textarea", array(
						"label" => Mage::helper("video")->__("Content"),
						"name" => "vcontent",
						));
					
						$fieldset->addField("vmeta_keywords", "textarea", array(
						"label" => Mage::helper("video")->__("Meta Keywords"),
						"name" => "vmeta_keywords",
						));
					
						$fieldset->addField("vmeta_description", "textarea", array(
						"label" => Mage::helper("video")->__("Meta Descriptions"),
						"name" => "vmeta_description",
						));*/
									
						 $fieldset->addField('vfeatured', 'select', array(
						'label'     => Mage::helper('video')->__('Featured'),
						'values'   => Hetinfoway_Video_Block_Adminhtml_Video_Grid::getValueArray6(),
						'name' => 'vfeatured',
						));				
						
						$fieldset->addField('status', 'select', array(
						  'label'     => Mage::helper('video')->__('Status'),
						  'name'      => 'status',
						  'values'    => array(
							  array(
								  'value'     => 1,
								  'label'     => Mage::helper('video')->__('Enabled'),
							  ),

							  array(
								  'value'     => 2,
								  'label'     => Mage::helper('video')->__('Disabled'),
							  ),
						  ),
					  ));
						
						
						$fieldset->addField("vsort_order", "text", array(
						"label" => Mage::helper("video")->__("Sort Order"),
						"name" => "vsort_order",
						));
					

				if (Mage::getSingleton("adminhtml/session")->getVideoData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getVideoData());
					Mage::getSingleton("adminhtml/session")->setVideoData(null);
				} 
				elseif(Mage::registry("video_data")) {
				    $form->setValues(Mage::registry("video_data")->getData());
				}
				return parent::_prepareForm();
		}
}
