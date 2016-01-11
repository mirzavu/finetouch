<?php
class Hetinfoway_Video_Helper_Data extends Mage_Core_Helper_Abstract
{
	const XML_CONFIG_URL_PREFIX = 'hetinfoway_video/hetinfoway_vgroup/page_url';
	const DEFAULT_ROOT = 'video';
	const XML_ROOT = 'hetinfoway_video/hetinfoway_vgroup/page_url';
	public function toUrlKey($string)
    {
        $urlKey = preg_replace(array('/[^a-z0-9-_]/i', '/[ ]{2,}/', '/[ ]/'), array(' ', ' ', '-'), $string);
        if (empty($urlKey)){
            $urlKey = time();
        }
        return strtolower($urlKey);
    }
	
	public function getVideoUrl()
    {
        return Mage::getModel('core/url')->getUrl(Mage::getStoreConfig(self::XML_CONFIG_URL_PREFIX));
    }
	
	public function getUrlPrefix()
    {
        return Mage::getStoreConfig(self::XML_CONFIG_URL_PREFIX);
    }
    public function conf($code, $store = null)
    {
        return Mage::getStoreConfig($code, $store);
    }
	public function getRoute($store = null)
    {
        $route = trim($this->conf(self::XML_ROOT));
        if (!$route) {
            $route = self::DEFAULT_ROOT;
        }
        return $route;
    }
    public function getRouteUrl($store = null)
    {
        return Mage::getUrl($this->getRoute($store), array('_store' => $store));

    }
    public function getStoreIdByCode($storeCode)
    {
        foreach (Mage::app()->getStore()->getCollection() as $store) {
            if ($storeCode == $store->getCode()) {
                return $store->getId();
            }
        }
        return false;
    }
    public function ifStoreChangedRedirect()
    {
        $path = Mage::app()->getRequest()->getPathInfo();

        $helper = Mage::helper('video');
        $currentRoute = $helper->getRoute();

        $fromStore = Mage::app()->getRequest()->getParam('___from_store');
        if ($fromStore) {

            $fromStoreId = $helper->getStoreIdByCode($fromStore);
            $fromRoute = $helper->getRoute($fromStoreId);

            $url = preg_replace("#$fromRoute#si", $currentRoute, $path, 1);
            $url = Mage::getBaseUrl() . ltrim($url, '/');

            Mage::app()->getFrontController()->getResponse()
                ->setRedirect($url)
                ->sendResponse();
            exit;
        }
    }
}
	 
