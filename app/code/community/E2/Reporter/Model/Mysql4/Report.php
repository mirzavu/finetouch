<?php

class E2_Reporter_Model_Mysql4_Report extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct() {
        $this->_init('e2_reporter/report','report_id');
    }
}
