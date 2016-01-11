<?php
class Hetinfoway_Video_CategoryController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
		$_module_enabaled = Mage::getStoreConfig('hetinfoway_video/hetinfoway_vgroup/video_enable');
		$url = Mage::getStoreConfig('hetinfoway_video/hetinfoway_vgroup/page_url');
		
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
						"title" => $this->__("Het Video"),
						"link"  => Mage::getBaseUrl().$url
				   ));     
			$current_cat =$this->getRequest()->getParam('cat');
			$collection = Mage::getModel('video/category')->load($current_cat)->getData();
			$this->getLayout()->getBlock("head")->setTitle($this->__($collection["title"]));
			$breadcrumbs->addCrumb($collection["title"], array('label'=>$collection["title"], 'title'=>$collection["title"]));
	  	   
			$this->renderLayout();
		}else{
			$this->getResponse()->setHeader('HTTP/1.1','404 Not Found');
			$this->getResponse()->setHeader('Status','404 File not found');
			$this->_forward('defaultNoRoute');
		}
    }
}
