<?php

class E2_Reporter_Model_Cron {
    const LOGFILE = 'e2_reporter.log';
    const MAXPROCESSED = 200;
    private $reports;

    public function __construct() {

      $this->reports = array();

    }
    ///////////////////////////
    // ENTRY POINT FROM CRON //
    ///////////////////////////
    public function run() {

         // scan for report files
         $i = 0;
         foreach($this->_getReportFiles() as $file) {
           $i++;
            $this->processReportFile($file);
            if ($i == self::MAXPROCESSED) {
              break;
            }
         }
         // generate summary report
         foreach($this->reports as $report) {
            $this->notify($report);
         }

    }

    // Get the folder for report files
    // (Mage.php hardcodes /error/report.php, /error/processor.php hardcodes /var/report)
    private function _getReportDir() {
      return Mage::getBaseDir() . DS . 'var' . DS . 'report' ;
    }

    // Get a list of current report files
    private function _getReportFiles() {
      return glob($this->_getReportDir() . DS .'*', GLOB_NOSORT);
    }

    public function processReportFile($file) {

      // load the report
      $report = Mage::getModel('e2_reporter/report')->loadFromFile($file);
      $report->save();

      // hold onto it for summary reporting
      $this->reports[] = $report;

      // and remove it
      unlink($file);

    }

    public function notify($report) {

      $sitename = Mage::getBaseUrl();
      $subject = '['.$sitename.'] Magento error report - '.$report->getErrorMessage();

      $date = date(DATE_RFC2822, $report->getTimestamp());

      $headers =  "From: ".Mage::getStoreConfig('trans_email/ident_general/name')." <".Mage::getStoreConfig('trans_email/ident_general/email').">";
      $headers .= "\r\nDate: $date";

      $emails = Mage::getStoreConfig('e2/reporter/notification_email');
      if ($emails) {
        mail($emails, $subject , $report->getContentAsText(), $headers);
      }

    }

}
