<?php
class Hetinfoway_Video_Adminhtml_CategoryController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('video/categories')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Category Manager'), Mage::helper('adminhtml')->__('Category Manager'));
			
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		//echo get_class(Mage::getModel('video/category'));
		
		//echo get_class(Mage::getModel('video/video'));	  exit;
		$model  = Mage::getModel('video/category')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('category_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('video/categories');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Category Manager'), Mage::helper('adminhtml')->__('Category Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Category News'), Mage::helper('adminhtml')->__('Category News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('video/adminhtml_category_edit'))
				->_addLeft($this->getLayout()->createBlock('video/adminhtml_category_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('video')->__('Category does not exist'));
			$this->_redirect('*/*/');
		}
	}
	public function newAction() {
		$this->_forward('edit');
	}
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
				try {
						if((bool)$data['filename']['delete']==1) {
									$data['filename']='';
						}else {
							unset($data['filename']);
							if (isset($_FILES)){
								if(isset($_FILES['filename']['name'])) {
									if($this->getRequest()->getParam("id")){
										$model = Mage::getModel("video/category")->load($this->getRequest()->getParam("id"));
										if($model->getData('filename')){
												$io = new Varien_Io_File();
												$io->rm(Mage::getBaseDir('media').DS.implode(DS,explode('/',$model->getData('filename'))));	
										}
									}
									$path = Mage::getBaseDir('media') . DS . 'video' . DS .'category'.DS;
									/* Starting upload */
									$uploader = new Varien_File_Uploader('filename');
									// Any extention would work
									$uploader->setAllowedExtensions(array('jpg','png','gif','jpeg'));
									$uploader->setAllowRenameFiles(false);
									// Set the file upload mode 
									// false -> get the file directly in the specified folder
									// true -> get the file in the product like folders 
									//	(file.jpg will go in something like /media/f/i/file.jpg)
									$uploader->setFilesDispersion(false);
									$destFile = $path.$_FILES['filename']['name'];
									$filename = $uploader->getNewFileName($destFile);
									$uploader->save($path, $filename);

									$data['filename']='video/category/'.$filename;
							}
						}
					}
					
				} catch (Exception $e) {
								Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
								$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
								return;
		      
		        }
			$model = Mage::getModel('video/category');
			$model->addData($data)		
				->setId($this->getRequest()->getParam('id'));
			
			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}	
				
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('video')->__('Category was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('video')->__('Unable to find Category to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('video/category');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Category was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $galleryIds = $this->getRequest()->getParam('category');
        if(!is_array($galleryIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Category(s)'));
        } else {
            try {
                foreach ($galleryIds as $galleryId) {
                    $gallery = Mage::getModel('video/category')->load($galleryId);
                    $gallery->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($galleryIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $galleryIds = $this->getRequest()->getParam('category');
        if(!is_array($galleryIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select Category(s)'));
        } else {
            try {
                foreach ($galleryIds as $galleryId) {
                    $gallery = Mage::getSingleton('video/category')
                        ->load($galleryId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($galleryIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    /**
		 * Export order grid to CSV format
		 */
		public function exportCsvAction()
		{
			$fileName   = 'videocategory.csv';
			$grid       = $this->getLayout()->createBlock('video/adminhtml_category_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'videocategory.xml';
			$grid       = $this->getLayout()->createBlock('video/adminhtml_category_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
	
	
}
