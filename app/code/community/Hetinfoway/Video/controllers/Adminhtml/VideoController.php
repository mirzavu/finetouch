<?php
class Hetinfoway_Video_Adminhtml_VideoController extends Mage_Adminhtml_Controller_Action
{
		protected function _initAction()
		{
				$this->loadLayout()
				->_setActiveMenu("video/video")
				->_addBreadcrumb(Mage::helper("adminhtml")->__("Video  Manager"),Mage::helper("adminhtml")->__("Video Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("Video"));
			    $this->_title($this->__("Manager Video"));

				$this->_initAction()
					->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("Video"));
				$this->_title($this->__("Video"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				//echo get_class(Mage::getModel('video/video'));exit;
				$model = Mage::getModel("video/video")->load($id);
				if ($model->getId() || $id == 0) {
					$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
					if (!empty($data)) {
						$model->setData($data);
					}
					Mage::register("video_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("video/video");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Video Manager"), Mage::helper("adminhtml")->__("Video Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Video Description"), Mage::helper("adminhtml")->__("Video Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("video/adminhtml_video_edit"))->_addLeft($this->getLayout()->createBlock("video/adminhtml_video_edit_tabs"));
					$this->renderLayout();
				} 
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("video")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
				
		}

		public function newAction() {
			$this->_forward('edit');
		}
		public function saveAction()
		{
			$post_data=$this->getRequest()->getPost();
				if ($post_data) {
					try {
						 //save image
				try{

					if((bool)$post_data['vimage']['delete']==1) {

								$post_data['vimage']='';

					}
					else {

							unset($post_data['vimage']);

							if (isset($_FILES)){

								if ($_FILES['vimage']['name']) {

									if($this->getRequest()->getParam("id")){
										$model = Mage::getModel("video/video")->load($this->getRequest()->getParam("id"));
										if($model->getData('vimage')){
												$io = new Varien_Io_File();
												$io->rm(Mage::getBaseDir('media').DS.implode(DS,explode('/',$model->getData('vimage'))));	
										}
									}
												$path = Mage::getBaseDir('media') . DS . 'video' . DS .'video'.DS;
												$uploader = new Varien_File_Uploader('vimage');
												$uploader->setAllowedExtensions(array('jpg','png','gif'));
												$uploader->setAllowRenameFiles(false);
												$uploader->setFilesDispersion(false);
												$destFile = $path.$_FILES['vimage']['name'];
												$filename = $uploader->getNewFileName($destFile);
												$uploader->save($path, $filename);

												$post_data['vimage']='video/video/'.$filename;
								}
							}
						}

						} catch (Exception $e) {
								Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
								$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
								return;
						}
				//save image
						
						$date=now(); 
						$model = Mage::getModel("video/video");
						$model->addData($post_data)
						->setId($this->getRequest()->getParam("id"));
						if($this->getRequest()->getParam("id")){ 
							$model->setVupdateTime(now());
						}else{
							$model->setVcreatedTime(now());
							$model->setVupdateTime(now());
							}
						$model->save();
						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Video was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setVideoData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setVideoData($this->getRequest()->getPost());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					return;
					}

				}
				$this->_redirect("*/*/");
		}

		public function deleteAction()
		{
				if( $this->getRequest()->getParam("id") > 0 ) {
					try {
						$model = Mage::getModel("video/video");
						$model->setId($this->getRequest()->getParam("id"))->delete();
						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
						$this->_redirect("*/*/");
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					}
				}
				$this->_redirect("*/*/");
		}
		/**
		 * Export order grid to CSV format
		 */
		public function exportCsvAction()
		{
			$fileName   = 'video.csv';
			$grid       = $this->getLayout()->createBlock('video/adminhtml_video_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'video.xml';
			$grid       = $this->getLayout()->createBlock('video/adminhtml_video_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
		
		public function importplaylistAction()
		{
			$playlistid = Mage::getStoreConfig('hetinfoway_video/hetinfoway_vgroup/youtubeplaylist');
			if ($playlistid)
			{
				$url = "https://gdata.youtube.com/feeds/api/playlists/".$playlistid."?v=2&alt=json";
				$data = json_decode(file_get_contents($url),true);
				
				$info = $data["feed"];
				$video = $info["entry"];
				$nVideo = count($video);
				for($i=0;$i<$nVideo;$i++)
				{
					$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
					 
					$connection->beginTransaction();

					$abc = substr( strstr($video[$i]['link'][0]['href'], '='),1,100);
					 substr( $abc, 0, strpos( $abc, '&' ) );
					$abcdef=substr( $abc, 0, strpos( $abc, '&' ) );

					$videotitle=$video[$i]['title']['$t'];
					$date=now(); 
					$__fields = array();
					$__fields['vname'] = $videotitle;
					$__fields['vurl'] = 'http://www.youtube.com/watch?v='.$abcdef;
					$__fields['vcode'] = $abcdef;
					$__fields['status'] = '1';
					$__fields['vsort_order'] = '0';
					$__fields['vfeatured'] = '0';
					$__fields['vcreated_time'] = $date;
					
					$videocollection = Mage::getModel('video/video')->getCollection()->addFieldToFilter('vcode', $abcdef)->getFirstItem();
					if($videocollection->getHetvideoId()){
					   //executing this block if record found in database
					   $data = array(
					   "vname" => $videotitle,
					   "vurl" => 'http://www.youtube.com/watch?v='.$abcdef,
					   "vcode" => $abcdef
					   ); 
					   $where = 'hetvideo_id ='.$videocollection->getHetvideoId(); 
					   $connection->update('hetvideo', $data, $where);
					   $connection->commit();
					}else{
					   //executing this block if record not found in database
					   $connection->insert('hetvideo', $__fields);
						$connection->commit();
					}

				}
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("video(s) was successfully Import"));
			
				$this->_redirect('*/*/');
			}else
			{
				$this->_redirect('*/*/');
				Mage::getSingleton("adminhtml/session")->addError('Please add your playlist id first in configuration section.');
			}
		}
		protected function _customerExists($videocode, $websiteId = null)
		{
			$video = Mage::getModel('video/video');
			if ($websiteId) {
				$customer->setWebsiteId($websiteId);
			}
			$video->loadByVcode($videocode);
			if ($video->getHetvideoId()) {
				return $video;
			}
			return false;
		}
		
		public function massDeleteAction() {
			$galleryIds = $this->getRequest()->getParam('video');
			if(!is_array($galleryIds)) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
			} else {
				try {
					foreach ($galleryIds as $galleryId) {
						$gallery = Mage::getModel('video/video')->load($galleryId);
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
			$galleryIds = $this->getRequest()->getParam('video');
			if(!is_array($galleryIds)) {
				Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
			} else {
				try {
					foreach ($galleryIds as $galleryId) {
						$gallery = Mage::getSingleton('video/video')
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
}
