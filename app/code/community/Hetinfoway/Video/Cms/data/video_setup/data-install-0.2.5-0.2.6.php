<?php
/*$content = '{{block type="video/video" name="all_video" template="video/allvideo.phtml"}}';
//if you want one block for each store view, get the store collection
$stores = Mage::getModel('core/store')->getCollection()->addFieldToFilter('store_id', array('gt'=>0))->getAllIds();
//if you want one general block for all the store viwes, uncomment the line below
//$stores = array(0);
foreach ($stores as $store){
    $block = Mage::getModel('cms/block');
    $block->setTitle('All Video Gallery');
    $block->setIdentifier('all_video_gallery');
    $block->setStores(array($store));
    $block->setIsActive(1);
    $block->setContent($content);
    $block->save();
}*/

$installer = $this;
$installer->startSetup();
$content = '{{block type="video/video" name="all_video" template="video/allvideo.phtml"}}';
// For creating a block for a store
$enStore = Mage::app()->getStore('store_code');
$block = array (
'identifier' => 'all_video_gallery',
'title' => 'All Video Gallery',
'stores' => array(
$enStore->getId()
),
'content' => $content
);
$cmsBlock = Mage::getModel('cms/block');
$cmsBlock->setData($block)->save();
$installer->endSetup();

?>
