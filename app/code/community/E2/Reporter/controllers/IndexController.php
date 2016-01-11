<?php

class E2_Reporter_IndexController extends Mage_Core_Controller_Front_Action {
    public function indexAction() {
        echo 'Setup!';
        
        
        
    }
    
    public function crashAction() {
        throw new Exception('deliberate exception created');
    }
    
} 

