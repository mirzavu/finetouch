<?php

class E2_Reporter_Model_Report extends Mage_Core_Model_Abstract
{
    protected function _construct() {
        $this->_init('e2_reporter/report');
    }

    public function loadFromFile($file) {

      $content = file_get_contents($file);

      $report = unserialize($content);
      if ($report) {
        $errormessage = $report[0];
      } else {
        $errormessage = '[unable to unserialize report]';
      }

      // Retrieve the ctime of the file
      $d = new Zend_Date(null, null, $this->getLocale());
      $d->setTimestamp(filectime($file));
      $ctime = $d->toString();

      // store information about it
      $this->setContent($content);
      $this->setHash(md5($content));
      $this->setReportCode(basename($file));
      $this->setFileCreatedAt($ctime);
      $this->setErrorMessage($errormessage);

      return $this;

    }

    public function getTimestamp() {
      $d = new Zend_Date;
      $d->set($this->getFileCreatedAt());
      return $d->getTimestamp();
    }

    public function getContentAsText() {
      $arr = unserialize($this->getContent());
      if (!$arr) return "[unable to deserialize]\n".$this->getContent();
      $c = '';
      $message    = array_shift($arr);
      $stacktrace = array_shift($arr);
      foreach($arr as $key=>$val) {
        $c .= "$key: $val\n";
      }
      return "$message\n\n$c\nStacktrace:\n$stacktrace";
    }
}
