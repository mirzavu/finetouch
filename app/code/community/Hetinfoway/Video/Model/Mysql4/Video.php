<?php
class Hetinfoway_Video_Model_Mysql4_Video extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("video/video", "hetvideo_id");
    }
	protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
	$stores = (array)$object->getData('stores');
	if (!empty($stores))
        {
            foreach ($stores as $storeId)
            {
                $rewriteModel = Mage::getModel('core/url_rewrite');
                $rewriteCollection = $rewriteModel->getCollection();
                $rewriteCollection->addStoreFilter($storeId, false)
                                  ->setPageSize(1)
                                  ->load();
                if (count($rewriteCollection) > 0)
                {
                    foreach ($rewriteCollection as $rewrite) {
                        $rewriteModel->setData($rewrite->getData());
                    }
                }
                $rewriteModel->setData('store_id', $storeId);
                $rewriteModel->setData('request_path', $object->getUrlKey() . '.html');
                $rewriteModel->setData('id_path', 'video/' . $object->getId());
                
            }
        }
		return parent::_afterSave($object);
    }
}