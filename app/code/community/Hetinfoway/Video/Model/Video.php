<?php
class Hetinfoway_Video_Model_Video extends Mage_Core_Model_Abstract
{
	protected static $_url = null;
    protected function _construct(){
       $this->_init("video/video");
    }
    public function getCollection() {
        return Mage::getResourceModel('video/video_collection');
    }
	public function checkUrlKey($urlKey, $storeId)
    {
        return $this->_getResource()->checkUrlKey($urlKey, $storeId);
    }
    public function isUniqueUrlKey($urlKey, $id = 0, $storeId = null)
    {
        if (is_null($storeId)){
            $storeId = Mage::app()->getStore()->getId();
        }
        $id = $this->_getResource()->checkUniqueUrlKey($urlKey, $id, $storeId);
        return (bool)empty($id);
    }
	public function getUrlInstance()
    {
        if (!self::$_url) {
            self::$_url = Mage::getModel('core/url');
        }
        return self::$_url;
    }
	public function getUrl()
    {
       	if ($this->getId())
        {
            $storeId = Mage::app()->getStore()->getId();
            $rewriteModel = Mage::getModel('core/url_rewrite');
            $rewriteCollection = $rewriteModel->getCollection();
            $rewriteCollection->addStoreFilter($storeId, true)
                              ->setOrder('store_id', 'DESC')
                              ->addFieldToFilter('target_path', 'video/index/view/id/' . $this->getId())
                              ->setPageSize(1)
                              ->load();
           if (count($rewriteCollection) > 0)
           {
                foreach ($rewriteCollection as $rewrite) {
                    $rewriteModel->setData($rewrite->getData());
                }
                //return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . $rewriteModel->getRequestPath();
                return Mage::getBaseUrl() . $rewriteModel->getRequestPath();
           } else 
           {
               return $this->getUrlInstance()->getUrl('video/index/view', array('id' => $this->getId()));
           }
        }
		return '';
        if ($this->getId())
            return $this->getUrlInstance()->getUrl(Mage::helper('video')->getUrlPrefix().'/'.$this->getUrlKey());
    }
}
