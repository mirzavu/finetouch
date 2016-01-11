<?php 
class Ecomwise_Creditlimitplus_Adminhtml_Credit_RulesController extends Mage_Adminhtml_Controller_Action
{
	protected function _initAction()
	{
		$this->loadLayout()->_setActiveMenu('ecomwisecreditplus');		
		return $this;
	}
	
	public function indexAction()
	{		
		$this->_initAction();
		$this->_title($this->__('Limits'));
		$this->renderLayout();
	}
	
	public function newAction(){
		$this->_initAction();

		$id  = $this->getRequest()->getParam('id');
		$model = Mage::getModel('ecomwisecreditplus/limits');

		$this->_title($this->__('Edit Credit Limit'));

		$data = Mage::getSingleton('adminhtml/session')->getNotificationData(true);
		if (!empty($data)) {
			//$model->setData($data);
		}
		Mage::register('creditlimit', $model);
		$this->renderLayout();
	}	
	
	public function editAction(){
		$this->_initAction();

		$id  = $this->getRequest()->getParam('id');
		$model = Mage::getModel('ecomwisecreditplus/limits');

		if ($id) {
			$model->load($id);

			if (!$model->getId()) {
				Mage::getSingleton('adminhtml/session')->addError($this->__('This no longer exists.'));
				$this->_redirect('*/*/');
				return;
			}
		}

		$this->_title($this->__('Edit Credit Limit'));

		$data = Mage::getSingleton('adminhtml/session')->getNotificationData(true);
		if (!empty($data)) {
			//$model->setData($data);
		}

		Mage::register('creditlimit', $model);
		$this->renderLayout();
	}


	public function saveAction()
	{		
		if ($this->getRequest()->getPost())
		{		
			try {
								
				$postData = $this->getRequest()->getPost();				
				$rule_id = $this->getRequest()->getParam('id');	

				/* Image upload */ 
				if(isset($_FILES['logo']['name']) && (file_exists($_FILES['logo']['tmp_name']))) {					
					$uploader = new Varien_File_Uploader('logo');
					$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png')); // or pdf or anything							
					$uploader->setAllowRenameFiles(false);						
					// setAllowRenameFiles(true) -> move your file in a folder the magento way
					// setAllowRenameFiles(true) -> move your file directly in the $path folder
					$uploader->setFilesDispersion(false);
					$uploader->setAllowRenameFiles(false);
					//$path = Mage::getBaseDir('media') . DS ;						
					$path = Mage::getBaseDir('media') . DS . 'credit_limit' . DS ;				
					$uploader->save($path, $_FILES['logo']['name']);							
					//$postData['logo'] = $_FILES['logo']['name'];						
					$postData['logo'] = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'credit_limit/' . $_FILES['logo']['name'];				
				}else {				
					if(isset($postData['logo']['delete']) && $postData['logo']['delete'] == 1)
						$postData['logo'] = '';
					else
						unset($postData['logo']);
				}
				/* End of Image upload */				
				
				if(isset($postData['customer_groups'])){
					$postData['customer_groups'] = implode(",",$postData['customer_groups']); 
				}					
				$testModel = Mage::getModel('ecomwisecreditplus/limits');
				if( $this->getRequest()->getParam('id') <= 0 )
					$testModel->setCreatedTime(
							Mage::getSingleton('core/date')
							->gmtDate()
					);
				$testModel
				->addData($postData)
				/* ->setUpdateTime(
						Mage::getSingleton('core/date')
						->gmtDate()) */
						->setId($rule_id)
						
				//->setId(1)				
						->save();				
				
				$rule_id = $testModel->getId();			
				
				Mage::getModel('ecomwisecreditplus/limits_customers')->deleteByRuleId( $rule_id );
				if($selected_customers = $this->getRequest()->getParam('customer_ids')){

					$selected_customers = Mage::helper('adminhtml/js')->decodeGridSerializedInput($selected_customers);
									
					foreach ($selected_customers as $customer_id){						
						$customers_model = Mage::getModel('ecomwisecreditplus/limits_customers');
						$data = array(
								'customer_id' => $customer_id,
								'rule_id' => $rule_id
						);						
						$customers_model->addData($data);//->save();
						$customers_model->save();						
					}
				}
				
				$success_msg = Mage::helper('ecomwisecreditplus')->__('Rule %s Successfully Saved', $testModel->getName());				
				Mage::getSingleton('adminhtml/session')
				->addSuccess($success_msg);
				Mage::getSingleton('adminhtml/session')
				->settestData(false);
				$this->_redirect('*/*/');
				return;
			} catch (Exception $e){
				Mage::getSingleton('adminhtml/session')
				->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')
				->settestData($this->getRequest()
				->getPost()
				);
				$this->_redirect('*/*/edit',
						array('id' => $this->getRequest()
								->getParam('id')));
				return;
			}
		}
		$this->_redirect('*/*/');
	}
	
	
	public function deleteAction()
	{		
		if ($this->getRequest()->getParam('id'))
		{
			try {
				$rule_id =$this->getRequest()->getParam('id');				
				Mage::getModel('ecomwisecreditplus/limits')->setId($rule_id)->delete();			
				Mage::getModel('ecomwisecreditplus/limits_customers')->deleteByRuleId( $rule_id );	
				$success_msg = Mage::helper('ecomwisecreditplus')->__('Record Successfully Deleted');
				Mage::getSingleton('adminhtml/session')->addSuccess($success_msg);		
			} catch (Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());				
				$this->_redirect('*/*/');
				return;
			}		
		}		
		$this->_redirect('*/*/');	
	}
	
	/**
	 * Action for ajax request from assigned users grid
	 *
	 */
	public function editrolegridAction()
	{
		$this->getResponse()->setBody(
				$this->getLayout()->createBlock('ecomwisecreditplus/credit_rules_grid_user')->setCustomers($this->getRequest()->getPost('customers', null))->toHtml()
		);	
		
	}	
}