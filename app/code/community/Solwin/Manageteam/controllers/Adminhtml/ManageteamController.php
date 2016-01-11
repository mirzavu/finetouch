<?php

class Solwin_Manageteam_Adminhtml_ManageteamController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()->_setActiveMenu("manageteam/manageteam")->_addBreadcrumb(Mage::helper("adminhtml")->__("Manageteam  Manager"), Mage::helper("adminhtml")->__("Manageteam Manager"));
        return $this;
    }

    public function indexAction() {
        $this->_title($this->__("Manageteam"));
        $this->_title($this->__("Manager Manageteam"));

        $this->_initAction();
        $this->renderLayout();
    }

    public function editAction() {
        $this->_title($this->__("Manageteam"));
        $this->_title($this->__("Manageteam"));
        $this->_title($this->__("Edit Item"));

        $id = $this->getRequest()->getParam("id");
        $model = Mage::getModel("manageteam/manageteam")->load($id);
        if ($model->getId()) {
            Mage::register("manageteam_data", $model);
            $this->loadLayout();
            $this->_setActiveMenu("manageteam/manageteam");
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Manageteam Manager"), Mage::helper("adminhtml")->__("Manageteam Manager"));
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Manageteam Description"), Mage::helper("adminhtml")->__("Manageteam Description"));
            $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock("manageteam/adminhtml_manageteam_edit"))->_addLeft($this->getLayout()->createBlock("manageteam/adminhtml_manageteam_edit_tabs"));
            $this->renderLayout();
        } else {
            Mage::getSingleton("adminhtml/session")->addError(Mage::helper("manageteam")->__("Item does not exist."));
            $this->_redirect("*/*/");
        }
    }

    public function newAction() {

        $this->_title($this->__("Manageteam"));
        $this->_title($this->__("Manageteam"));
        $this->_title($this->__("New Item"));

        $id = $this->getRequest()->getParam("id");
        $model = Mage::getModel("manageteam/manageteam")->load($id);

        $data = Mage::getSingleton("adminhtml/session")->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register("manageteam_data", $model);

        $this->loadLayout();
        $this->_setActiveMenu("manageteam/manageteam");

        $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

        $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Manageteam Manager"), Mage::helper("adminhtml")->__("Manageteam Manager"));
        $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Manageteam Description"), Mage::helper("adminhtml")->__("Manageteam Description"));


        $this->_addContent($this->getLayout()->createBlock("manageteam/adminhtml_manageteam_edit"))->_addLeft($this->getLayout()->createBlock("manageteam/adminhtml_manageteam_edit_tabs"));

        $this->renderLayout();
    }

    public function saveAction() {

        $post_data = $this->getRequest()->getPost();


        if ($post_data) {

            try {

                //save image
                try {

                    if ((bool) $post_data['image']['delete'] == 1) {

                        $post_data['image'] = '';
                    } else {

                        unset($post_data['image']);

                        if (isset($_FILES)) {

                            if ($_FILES['image']['name']) {

                                if ($this->getRequest()->getParam("id")) {
                                    $model = Mage::getModel("manageteam/manageteam")->load($this->getRequest()->getParam("id"));
                                    if ($model->getData('image')) {
                                        $io = new Varien_Io_File();
                                        $io->rm(Mage::getBaseDir('media') . DS . implode(DS, explode('/', $model->getData('image'))));
                                    }
                                }
                                $path = Mage::getBaseDir('media') . DS . 'manageteam' . DS . 'manageteam' . DS;
                                $uploader = new Varien_File_Uploader('image');
                                $uploader->setAllowedExtensions(array('jpg', 'png', 'gif'));
                                $uploader->setAllowRenameFiles(false);
                                $uploader->setFilesDispersion(false);
                                $destFile = $path . $_FILES['image']['name'];
                                $filename = $uploader->getNewFileName($destFile);
                                $uploader->save($path, $filename);

                                $post_data['image'] = 'manageteam/manageteam/' . $filename;
                            }
                        }
                    }
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                    return;
                }
                
                //save image    
                $model = Mage::getModel("manageteam/manageteam")
                        ->addData($post_data)
                        ->setId($this->getRequest()->getParam("id"))
                        ->save();

                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Manageteam was successfully saved"));
                Mage::getSingleton("adminhtml/session")->setManageteamData(false);

                if ($this->getRequest()->getParam("back")) {
                    $this->_redirect("*/*/edit", array("id" => $model->getId()));
                    return;
                }
                $this->_redirect("*/*/");
                return;
            } catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                Mage::getSingleton("adminhtml/session")->setManageteamData($this->getRequest()->getPost());
                $this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
                return;
            }
        }
        $this->_redirect("*/*/");
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam("id") > 0) {
            try {
                $model = Mage::getModel("manageteam/manageteam");
                $model->setId($this->getRequest()->getParam("id"))->delete();
                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
                $this->_redirect("*/*/");
            } catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                $this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
            }
        }
        $this->_redirect("*/*/");
    }

    public function massRemoveAction() {
        try {
            $ids = $this->getRequest()->getPost('manageteam_ids', array());
            foreach ($ids as $id) {
                $model = Mage::getModel("manageteam/manageteam");
                $model->setId($id)->delete();
            }
            Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
        } catch (Exception $e) {
            Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }

    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction() {
        $fileName = 'manageteam.csv';
        $grid = $this->getLayout()->createBlock('manageteam/adminhtml_manageteam_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     *  Export order grid to Excel XML format
     */
    public function exportExcelAction() {
        $fileName = 'manageteam.xml';
        $grid = $this->getLayout()->createBlock('manageteam/adminhtml_manageteam_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }

}
