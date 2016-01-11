<?php

class Ecomwise_B2BOrderlist_Block_Catalog_Product_Bundle_Option_Select
    extends Mage_Bundle_Block_Catalog_Product_View_Type_Bundle_Option_Select
{
    protected function _construct()
    {
        $this->setTemplate('b2borderlist/product/options/type/select.phtml');
    }
    
    
}
