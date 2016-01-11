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
 * @package    Mage_Catalog
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * Webshopapps Extension
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
 * Ammendments have been made to support tiering across products
 * 
 * @category   Auctionmaid
 * @package    Auctionmaid_Tieredproducts
 * @copyright  Copyright (c) 2008 Auction Maid (http://www.auctionmaid.com)
 * @author     Karen Baker <enquiries@auctionmaid.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


class Webshopapps_Tieredproducts_Model_Product_Type_Price extends Mage_Catalog_Model_Product_Type_Price
{

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
        $finalPrice = $this->_applyTierPrice($product, $qty, $finalPrice);
    	
     // Call our custom function to apply category Tier Pricing, if applicable.
        $finalPrice = $this->_applyWebshopAppsTierPrice($qty, $finalPrice,$product->getEntityId());
        $finalPrice = $this->_applySpecialPrice($product, $finalPrice);
        
        $product->setFinalPrice($finalPrice);

        Mage::dispatchEvent('catalog_product_get_final_price', array('product'=>$product));

        $finalPrice = $product->getData('final_price');
        $finalPrice = $this->_applyOptionsPrice($product, $qty, $finalPrice);
		return max(0, $finalPrice);
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
    
    protected function _applyWebshopAppsTierPrice($qty, $finalPrice,$baseProductId)
    {
    	if (is_null($qty)) {
            return $finalPrice;
        }

        // Get array of items in cart
        $items = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
        $tieredItems=array();
		$masterTierId="";
		foreach($items as $item) {
        	$crossTierId="";
            $productId = $item->getProductId();
			if ($item->getProductType() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE ||
				$item->getProductType() == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE  ) {
				continue;
			} 
		
			$productList = Mage::getModel('catalog/product')->getResourceCollection()
                ->addAttributeToSelect('cross_tier_id')
                ->addIdFilter($productId);
                
		  // Because we filtered by productId in the Collection above, and productId is unique, there will only be one item in the collection
            foreach($productList as $product)
            {
                continue;
            }
            

			if ($item->getParentItem()!=null) {
				if ($item->getParentItem()->getProductType() == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) {
					continue;
				}
			}
			$qty=$item->getQty();
			
	            $crossTierId = $product->getAttributeText('cross_tier_id');
            if(!empty($crossTierId)) 
            {
            	$found = false;
            	$marked = false;
            	if ($item->getProductId()==$baseProductId) {
            		$marked=true;
	            	if ($item->getProductType() != Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE &&
	        			$item->getProductType() != Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) {
	        			
	            		$masterTierId=$crossTierId;
	        			
	        		}
            	}
            	// lets collate like products together
            	foreach ($tieredItems as $key=>$tieredItem) {
            		
            		if ($tieredItem['tier_id']==$crossTierId) {
            			$found = true;
						$tieredItems[$key]['qty']+=$qty;
						$tieredItems[$key]['multiple']=true;
						if ($marked) { $tieredItems[$key]['marked']=true; }
            			break;
            		}
            	}
            	if (!$found) {
            			$tieredItem=array('tier_id'			=> $crossTierId,
            								'qty' 			=> $qty,
            								'multiple' 		=> false,
            								'marked'		=> $marked);
            			
            			$tieredItems[]=$tieredItem;
            	}
            }
		}        
 
		foreach ($tieredItems as $tieredItem) {
        	if ($tieredItem['marked'] && $tieredItem['multiple'] && $masterTierId==$tieredItem['tier_id']) {
        			$currentProduct = Mage::getModel('catalog/product')->load($baseProductId); 
        			$tierPrice = $currentProduct->getTierPrice($tieredItem['qty']);	
        			$finalPrice = min($finalPrice, $tierPrice);
        		
	        	break;
        	}        	
        }
       
     
        
        
        return $finalPrice;
    }

} 