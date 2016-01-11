<?php

class Solwin_Manageteam_IndexController extends Mage_Core_Controller_Front_Action {

    public function IndexAction() {

        $this->loadLayout();
        $this->getLayout()->getBlock("head")->setTitle($this->__("Our Team"));
        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
        $breadcrumbs->addCrumb("home", array(
            "label" => $this->__("Home"),
            "title" => $this->__("Home"),
            "link" => Mage::getBaseUrl()
        ));

        $breadcrumbs->addCrumb("manage team", array(
            "label" => $this->__("Our Team"),
            "title" => $this->__("Our Team")
        ));

        $this->renderLayout();
    }

}
