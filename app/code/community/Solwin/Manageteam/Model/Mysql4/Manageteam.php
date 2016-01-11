<?php
class Solwin_Manageteam_Model_Mysql4_Manageteam extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("manageteam/manageteam", "manageteam_id");
    }
}