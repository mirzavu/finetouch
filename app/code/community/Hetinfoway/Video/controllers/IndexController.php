<?php
class Hetinfoway_Video_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
      $_module_enabaled = Mage::getStoreConfig('hetinfoway_video/hetinfoway_vgroup/video_enable');
      
      if($_module_enabaled==1){
	  $this->loadLayout();   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("Het Video Category"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
		   ));
      $breadcrumbs->addCrumb("het video", array(
                "label" => $this->__("Het Video"),
                "title" => $this->__("Het Video")
		   ));
      $this->renderLayout(); 
      }else{
			$this->getResponse()->setHeader('HTTP/1.1','404 Not Found');
			$this->getResponse()->setHeader('Status','404 File not found');
			$this->_forward('defaultNoRoute');
		}
    }
}
