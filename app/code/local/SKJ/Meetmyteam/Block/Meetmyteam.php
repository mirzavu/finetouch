<?php
/**
 * @category   SKJ
 * @package    SKJ_Meetmyteam
 * @author     Sanjeev Kumar Jha <jha.sanjeev.in@gmail.com>
 * @website    http://www.sanjeevkumarjha.com.np
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class SKJ_Meetmyteam_Block_Meetmyteam extends Mage_Core_Block_Template
{
	    
     public function getAllMembers()     
     {      	     
        $collection = Mage::getModel('meetmyteam/meetmyteam')->getCollection();
		$collection->addFieldToFilter('status',Array('eq'=>1));
		
		return $collection;        
    }
    
    
}