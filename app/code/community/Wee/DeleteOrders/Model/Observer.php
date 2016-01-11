<?php

class Wee_DeleteOrders_Model_Observer
{
    const SALES_ORDER_GRID_NAME = 'sales_order_grid';
    
    public function addOptionToSelect($observer)
    {
        if (self::SALES_ORDER_GRID_NAME == $observer->getEvent()->getBlock()->getId()) {
            $massBlock = $observer->getEvent()->getBlock()->getMassactionBlock();
            if ($massBlock) {
                $massBlock->addItem('wee_delete_orders', array(
                    'label'=> Mage::helper('core')->__('Delete'),
                    'url'  => Mage::getUrl('wee_delete_orders', array('_secure'=>true)),
                    'confirm' => Mage::helper('core')->__('Are you sure to delete the selected orders?'),
                ));
            }
        }
    }
}
