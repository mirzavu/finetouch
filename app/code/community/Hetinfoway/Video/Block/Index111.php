<?php   
class Hetinfoway_Video_Block_Index extends Mage_Core_Block_Template{   

public function __construct()
    {
        parent::__construct();
        $collection = Mage::getModel('video/video')->getCollection();
        $this->setCollection($collection);
    }
 
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
 		if (!$this->hasData('video')) {
			$current_cat =$this->getRequest()->getParam('cat');
			
			$collection = Mage::getModel('video/video')->getCollection()->addFieldToFilter("category", $current_cat);
			$this->setCollection($collection);
			
			////////////////////////   per page setting get value from configuration setting  //////////////////////////////
			$_module_img_item_per_page = Mage::getStoreConfig('hetinfoway_video/hetinfoway_vgroup/video_img_item_per_page');
			if($_module_img_item_per_page==''){
				$per_page = array(10=>10,20=>20,30=>30,'all'=>'all');
			}else{
				$ar = explode(',',$_module_img_item_per_page);
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
		
        /*$pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
		$limit = explode(',', Mage::getStoreConfig('hetinfoway_video/hetinfoway_vgroup/list_per_page_values'));
		$limitArray = array();
		foreach($limit as $limitVal){
			$limitArray[$limitVal] = $limitVal;
		}
		$pager->setAvailableLimit($limitArray);
        $pager->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
        return $this;*/
		
    }
	
	public function getVideo()
    {
        return $this->_video;
    }
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getCollection()     
     {            
	 	$limit = $this->getRequest()->getParam("limit");
		if(!$limit){
        $limit        =    (Mage::getStoreConfig('hetinfoway_video/hetinfoway_vgroup/items_per_page'))?Mage::getStoreConfig('hetinfoway_video/hetinfoway_vgroup/items_per_page'):12;
		}
        $curr_page    =    1;
            
        if(Mage::app()->getRequest()->getParam('p'))
        {
            $curr_page    =    Mage::app()->getRequest()->getParam('p');
        }

        //Calculate Offset    
        $offset     =    ($curr_page - 1) * $limit;
        
        $collection    =    Mage::getModel('video/video')->getCollection();
                                                    
        $collection->getSelect()->limit($limit,$offset);
        
        return $collection;
    }
    
   
	public function getImage()     
    { 
        if (!$this->hasData('video')) {
			$current_img =$this->getRequest()->getParam('img');
            $this->setData('video', Mage::getModel('video/video')->getCollection()->addFieldToFilter("hetvideo_id", $current_img));
        }
        return $this->getData('video');
        
    }
	
	public function getCategoryById()     
    {   $current_cat =$this->getRequest()->getParam('cat');
        $this->setData('cat',Mage::getModel('video/category')->getCollection()->addFieldToFilter("category_id", $current_cat));
		
		$catcount = count($this->getData('cat'));
		if($catcount > 0 ):
			foreach ($this->getData('cat') as $catTitle):
				echo $catTitle->getTitle();
			endforeach;
		endif;
			
    }
	
	
	/**
	 * Resize Image proportionally and return the resized image url
	 *
	 * @param string $imageName         name of the image file
	 * @param integer|null $width       resize width
	 * @param integer|null $height      resize height
	 * @param string|null $imagePath    directory path of the image present inside media directory
	 * @return string               full url path of the image
	 */
	public function resizeImage($imageName, $width=NULL, $height=NULL, $imagePath=NULL)
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
	}




}
