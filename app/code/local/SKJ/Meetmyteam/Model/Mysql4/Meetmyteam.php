<?php

class SKJ_Meetmyteam_Model_Mysql4_Meetmyteam extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the meetmyteam_id refers to the key field in your database table.
        $this->_init('meetmyteam/meetmyteam', 'meetmyteam_id');
    }
}