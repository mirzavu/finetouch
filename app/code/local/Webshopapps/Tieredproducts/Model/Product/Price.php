<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Mage
 * @package    Mage_Bundle
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Bundle Price Model 
 *
 * @category    Mage
 * @package     Mage_Bundle
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class  Webshopapps_Tieredproducts_Model_Product_Price extends Mage_Bundle_Model_Product_Price
{
  

    /**
     * Get product final price
     *
     * @param   double $qty
     * @param   Mage_Catalog_Model_Product $product
     * @return  double
     */
    public function getFinalPrice($qty=null, $product)
    {
    	if ($qty == '')
    	{
    		$qty = $this->_getItemQuantity($product->getEntityId());
    	}
    	
    	if (!Mage::getStoreConfig('catalog/tieredproducts/active')) {
    		return parent::getFinalPrice($qty,$product);
    	}
    	if (is_null($qty) && !is_null($product->getCalculatedFinalPrice())) {
            return $product->getCalculatedFinalPrice();
        }

        $finalPrice = $product->getPrice();

        /**
         * Just product with fixed price calculation has price
         */
        if ($finalPrice) {
        	$tierPrice = $this->_applyTierPrice($product, $qty, $finalPrice);
            $tier1Price = $this->_applyWebshopAppsTierPrice($qty, $finalPrice,$product->getEntityId());
            $tierPrice = min(array($tierPrice, $tier1Price));
            
            $specialPrice = $this->_applySpecialPrice($product, $finalPrice);
            $finalPrice = min(array($tierPrice, $specialPrice));
            $product->setFinalPrice($finalPrice);
            Mage::dispatchEvent('catalog_product_get_final_price', array('product'=>$product));
            $finalPrice = $product->getData('final_price');
        }
        $basePrice = $finalPrice;

        if ($product->hasCustomOptions()) {
            $customOption = $product->getCustomOption('bundle_option_ids');
            $customOption = $product->getCustomOption('bundle_selection_ids');
            $selectionIds = unserialize($customOption->getValue());
            $selections = $product->getTypeInstance(true)->getSelectionsByIds($selectionIds, $product);
            foreach ($selections->getItems() as $selection) {
                if ($selection->isSalable()) {
                    $selectionQty = $product->getCustomOption('selection_qty_' . $selection->getSelectionId());
                    if ($selectionQty) {
                        $finalPrice = $finalPrice + $this->getSelectionFinalPrice($product, $selection, $qty, $selectionQty->getValue());
                    }
                }
            }
        } else {
//            if ($options = $this->getOptions($product)) {
//                /* some strange thing
//                foreach ($options as $option) {
//                    $selectionCount = count($option->getSelections());
//                    if ($selectionCount) {
//                        foreach ($option->getSelections() as $selection) {
//                            if ($selection->isSalable() && ($selection->getIsDefault() || ($option->getRequired() &&)) {
//                                $finalPrice = $finalPrice + $this->getSelectionPrice($product, $selection);
//                            }
//                        }
//                    }
//                }
//                */
//            }
        }

        $finalPrice = $finalPrice + $this->_applyOptionsPrice($product, $qty, $basePrice) - $basePrice;
        $product->setFinalPrice($finalPrice);

        return max(0, $product->getData('final_price'));
    }
    
	protected function _getItemQuantity($baseProductId)
    {
    	$items = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
    	foreach($items as $item) 
    	{
    		if ($item->getProductId()==$baseProductId) 
    		{
    			return $item->getQty();
    		}
		}
    	return 0;
    }
    
   /**
     * Calculate final price of selection
     *
     * @param Mage_Catalog_Model_Product $bundleProduct
     * @param Mage_Catalog_Model_Product $selectionProduct
     * @param decimal $bundleQty
     * @param decimal $selectionQty
     * @return decimal
     */
    public function getSelectionFinalPrice($bundleProduct, $selectionProduct, $bundleQty, $selectionQty = null, $multiplyQty = true)
    {
        $selectionPrice = $this->getSelectionPrice($bundleProduct, $selectionProduct, $selectionQty, $multiplyQty);
        // apply bundle tier price
        $tierPrice = $this->_applyTierPrice($bundleProduct, $bundleQty, $selectionPrice);
        $tier1Price = $this->_applyWebshopAppsTierPrice($bundleQty, $selectionPrice,$bundleProduct->getEntityId());
        $tierPrice = min(array($tierPrice, $tier1Price));
        
        // apply bundle special price
        $specialPrice = $this->_applySpecialPrice($bundleProduct, $selectionPrice);

        return min(array($tierPrice, $specialPrice));
    }
    
  	protected function _applyWebshopAppsTierPrice($qty, $finalPrice,$baseProductId)
    {
    	if (is_null($qty)) {
            return $finalPrice;
        }

        // Get array of items in cart
        $items = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
        $tieredItems=array();
		foreach($items as $item) {
			if ( $item->getProductType() != Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) {
				continue;
			}
        	$crossTierId="";
        	$bundledId="";
			
            $productId = $item->getProductId();
            $qty = $item->getQty();
			
			
			$productList = Mage::getModel('catalog/product')->getResourceCollection()
                ->addAttributeToSelect('cross_tier_id')
                ->addIdFilter($productId);
                
		  // Because we filtered by productId in the Collection above, and productId is unique, there will only be one item in the collection
            foreach($productList as $product)
            {
                continue;
            }
            
			
	        $crossTierId = $product->getAttributeText('cross_tier_id');
	        if(!empty($crossTierId)) 
            {
            	$found = false;
            	$marked = false;
            	if ($item->getProductId()==$baseProductId) {
            		$marked=true;
            	}
            	// lets collate like products together
            	foreach ($tieredItems as $key=>$tieredItem) {
            		
            		if ($tieredItem['tier_id']==$crossTierId) {
            			$found = true;
            			//$tieredItems[$key]['items'][]=$item->getProductId();
						$tieredItems[$key]['qty']+=$qty;
						$tieredItems[$key]['multiple']=true;
						$tieredItems[$key]['bundled_id']=$bundledId;
						if ($marked) { $tieredItems[$key]['marked']=true; }
            			break;
            		}
            	}
            	if (!$found) {
            			$tieredItem=array('tier_id'			=> $crossTierId,
            								//'items' 		=> array($item->getProductId()),
            								'qty' 			=> $qty,
            								'multiple' 		=> false,
            								'bundled_id'	=> $bundledId,
            								'marked'		=> $marked);
            			
            			$tieredItems[]=$tieredItem;
            	}
            }
		}        
       foreach ($tieredItems as $tieredItem) {
        	if ($tieredItem['marked'] && $tieredItem['multiple']) {
        			//bundled
        			$currentProduct = Mage::getModel('catalog/product')->load($baseProductId);
        			$discount = $currentProduct->getTierPrice($tieredItem['qty']);	
        			if ($discount >0) {
        				$tierPrice = $finalPrice - ($finalPrice*$discount)/100;	
         				$finalPrice = min($finalPrice, $tierPrice);
       				}
	        	break;
        	}        	
        }
       
     
        
        
        return $finalPrice;
    }

}
