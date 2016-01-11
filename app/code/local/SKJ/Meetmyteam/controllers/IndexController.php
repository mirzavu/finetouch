<?php
/**
 * @category   SKJ
 * @package    SKJ_Meetmyteam
 * @author     Sanjeev Kumar Jha <jha.sanjeev.in@gmail.com>
 * @website    http://www.sanjeevkumarjha.com.np
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class SKJ_Meetmyteam_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
		$this->loadLayout();     
		$this->renderLayout();
    }
    
    public function detailAction()
    {    	 
    	$this->loadLayout();
    	$this->renderLayout();
    }

    public function categoryAction()
    {    	 
    	$this->loadLayout();
    	$this->renderLayout();
    }
    
    
}
