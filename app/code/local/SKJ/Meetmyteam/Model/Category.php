<?php
/**
 * @category   SKJ
 * @package    SKJ_Meetmyteam
 * @author     Sanjeev Kumar Jha <jha.sanjeev.in@gmail.com>
 * @website    http://www.sanjeevkumarjha.com.np
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class SKJ_Meetmyteam_Model_Category extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('meetmyteam/category');
    }
}