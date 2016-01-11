<?php

class  Ecomwise_B2BOrderlist_Block_Catalog_Product_Bundle_Option_Multi
    extends Mage_Bundle_Block_Catalog_Product_View_Type_Bundle_Option_Multi
{
   
    protected function _construct()
    {
        $this->setTemplate('b2borderlist/product/options/type/multi.phtml');
    }
    
    public function setValidationContainer($elementId, $containerId, $productId)
    {
    	return '<script type="text/javascript">
            $(\'' . $elementId . '\').advaiceContainer = \'' . $containerId . '\';
            $(\'' . $elementId . '\').callbackFunction  = \'bundle_'.$productId.'.validationCallback\';
            </script>';
    }
}
