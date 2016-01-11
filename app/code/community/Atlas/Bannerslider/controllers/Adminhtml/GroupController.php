<?php

/**
 * Atlas Extensions
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Atlas Commercial License
 * that is available through the world-wide-web at this URL:
 *
 * @copyright   Copyright (c) 2015 Atlas Extensions
 * @license     Commercial
 */
class Atlas_Bannerslider_Adminhtml_GroupController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->loadLayout()->_setActiveMenu('general/bannerslider/index');
        $this->renderLayout();
    }

    public function editAction()
    {
        $testId = $this->getRequest()->getParam('id');
        $testModel = Mage::getModel('bannerslider/bannerslidergroup')->load($testId);
        // echo $testModel->getId();
        //exit;
        if ($testModel->getId() || $testId == 0)
        {
            Mage::register('test_data', $testModel);
            $this->loadLayout();
            $this->_setActiveMenu('general/bannerslider/set_time');
            $this->_addBreadcrumb('test Manager', 'test Manager');
            $this->_addBreadcrumb('Test Description', 'Test Description');
            $this->getLayout()->getBlock('head')
                    ->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()
                            ->createBlock('bannerslider/adminhtml_bannerslidergroup_edit'))
                    ->_addLeft($this->getLayout()
                            ->createBlock('bannerslider/adminhtml_bannerslidergroup_edit_tabs')
            );
            $this->renderLayout();
        }
        else
        {
            Mage::getSingleton('adminhtml/session')
                    ->addError('Test does not exist');
            $this->_redirect('*/*/');
        }
    }

    public function saveAction()
    {
        if ($this->getRequest()->getPost())
        {
            try {
                $postData = $this->getRequest()->getPost();
                $testModel = Mage::getModel('bannerslider/bannerslidergroup');

                if ($this->getRequest()->getParam('id') <= 0)
                {
                    $testModel->setCreatedTime(
                            Mage::getSingleton('core/date')
                                    ->gmtDate()
                    );
                    $createdtime = Mage::getSingleton('core/date')
                            ->gmtDate();
                    $postData['added_date'] = $createdtime;
                }

                if (isset($postData['stores']))
                {
                    if (in_array('0', $postData['stores']))
                    {
                        $postData['store_id'] = '0';
                    }
                    else
                    {
                        $postData['store_id'] = implode(",", $postData['stores']);
                    }
                    unset($postData['stores']);
                }



                $updatedtime = Mage::getSingleton('core/date')
                        ->gmtDate();
                $postData['updated_date'] = $updatedtime;
                $testModel->addData($postData)
                        ->setUpdateTime(
                                Mage::getSingleton('core/date')
                                ->gmtDate())
                        ->setId($this->getRequest()->getParam('id'))
                        ->save();
                Mage::getSingleton('adminhtml/session')->addSuccess('Group Successfully Saved.');
                Mage::getSingleton('adminhtml/session')->settestData(false);
                $newId = $testModel->getId();

                if ($this->getRequest()->getParam('back'))
                {
                    if ($this->getRequest()->getParam('id') <= 0)
                    {
                        $this->_redirect('*/*/edit', array('id' => $newId));
                        return;
                    }
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->settestData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        $deleteid = $this->getRequest()->getParam('id');
        if ($this->getRequest()->getParam('id') > 0)
        {

            try {
                $testModel = Mage::getModel('bannerslider/bannerslidergroup');
                $relationModel = Mage::getModel('bannerslider/bannerslidergrouprelation');
                $reqgrpid = $this->getRequest()->getParam('id');
                $testModel->setId($this->getRequest()->getParam('id'))->delete();
                $bannerdelcol = $relationModel->getCollection()->addFieldToFilter('group_id', $reqgrpid)->addFieldToSelect('bannerslidergroup_relation_id');
                $bannerdelcoldata = $bannerdelcol->getData();
                // $bannerdelcol->printLogQuery(true);	
                foreach ($bannerdelcoldata as $singlebannerdelcoldata)
                {

                    $relationid = $singlebannerdelcoldata['bannerslidergroup_relation_id'];
                    $relationModel->load($relationid)->delete();
                    // var_dump($relationid);
                }

                Mage::getSingleton('adminhtml/session')->addSuccess('successfully deleted');
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function massDeleteAction()
    {
        $banners = $this->getRequest()->getParam('ids');
        $relationModel = Mage::getModel('bannerslider/bannerslidergrouprelation');
        if (!is_array($banners))
        {
            Mage::getSingleton('adminhtml/session')->addError('Please select item(s)');
        }
        else
        {
            try {
                foreach ($banners as $bannerId)
                {
                    $bannersliderId = Mage::getModel('bannerslider/bannerslidergroup')->load($bannerId);
                    $bannersliderId->delete();
                    $bannerdelcol = $relationModel->getCollection()->addFieldToFilter('group_id', $bannerId)->addFieldToSelect('bannerslidergroup_relation_id');
                    $bannerdelcoldata = $bannerdelcol->getData();
                    // $bannerdelcol->printLogQuery(true);	
                    foreach ($bannerdelcoldata as $singlebannerdelcoldata)
                    {

                        $relationid = $singlebannerdelcoldata['bannerslidergroup_relation_id'];
                        $relationModel->load($relationid)->delete();
                        // var_dump($relationid);
                    }
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted', count($banners)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction()
    {
        $banners = $this->getRequest()->getParam('ids');
        if (!is_array($banners))
        {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        }
        else
        {
            try {
                foreach ($banners as $bannerId)
                {
                    $bannersliderId = Mage::getSingleton('bannerslider/bannerslidergroup')
                            ->load($bannerId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($banners))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }

}

?>
