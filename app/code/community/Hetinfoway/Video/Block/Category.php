<?php
class Hetinfoway_Video_Block_Category extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		parent::_prepareLayout();
		
		if (!$this->hasData('category')) {
			$collection = Mage::getModel('video/category')->getCollection()->addFieldToFilter("status", 1);
			$this->setCollection($collection);
				
			////////////////////////   per page setting get value from configuration setting  //////////////////////////////
			$_module_cat_per_page = Mage::getStoreConfig('hetinfoway_video/hetinfoway_vgroup/video_cat_per_page');
			if($_module_cat_per_page==''){
				$per_page = array(10=>10,20=>20,30=>30,'all'=>'all');
			}else{
				$ar = explode(',',$_module_cat_per_page);
				for($i=0; $i<count($ar); $i++){
					$val = $ar[$i];
					$per_page[$val] = $val;
				}
				$per_page['all'] = 'all';
			}
			////////////////////////   per page setting get value from configuration setting  //////////////////////////////
				
			$pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
			$pager->setAvailableLimit($per_page);
			$pager->setCollection($this->getCollection());
			$this->setChild('pager', $pager);
			$this->getCollection()->load();
			return $this;
		}
    }
    

	public function getPagerHtml()
	{
		return $this->getChildHtml('pager');
	}
	
	/*public function resizeImage($imageName, $width=NULL, $height=NULL, $imagePath=NULL)
	{      
		$imagePath = str_replace("/", DS, $imagePath);
		$imagePathFull = Mage::getBaseDir('media') . DS . $imagePath . DS . $imageName;
		 
		if($width == NULL && $height == NULL) {
			$width = 100;
			$height = 100;
		}
		$resizePath = $width . 'x' . $height;
		$resizePathFull = Mage::getBaseDir('media') . DS . $imagePath . DS . $resizePath . DS . $imageName;
				 
		if (file_exists($imagePathFull) && !file_exists($resizePathFull)) {
			$imageObj = new Varien_Image($imagePathFull);
			$imageObj->constrainOnly(TRUE);
			$imageObj->keepAspectRatio(TRUE);
			$imageObj->resize($width,$height);
			$imageObj->save($resizePathFull);
		}
				 
		$imagePath=str_replace(DS, "/", $imagePath);
		$img = Mage::getBaseUrl("media") . $imagePath . "/" . $resizePath . "/" . $imageName;
		return str_replace(DS, "/", $img);
	}*/

}
