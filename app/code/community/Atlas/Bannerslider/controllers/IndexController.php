<?php

/**
 * Atlas Extensions
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Atlas Commercial License
 * that is available through the world-wide-web at this URL:
 *
 * @copyright   Copyright (c) 2015 Atlas Extensions
 * @license     Commercial
 */
class Atlas_Bannerslider_IndexController extends Mage_Core_Controller_Front_Action
{

    public function indexAction()
    {

        $this->loadLayout();
        $this->renderLayout();
    }

}

?>