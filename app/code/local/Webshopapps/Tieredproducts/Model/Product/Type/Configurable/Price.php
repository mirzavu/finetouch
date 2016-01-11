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
 * Product type price model
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Webshopapps_Tieredproducts_Model_Product_Type_Configurable_Price extends Mage_Catalog_Model_Product_Type_Configurable_Price
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

        $finalPrice = Mage_Catalog_Model_Product_Type_Price::getFinalPrice($qty, $product);
        $tierPrice = $this->_applyWebshopAppsTierPrice($finalPrice, $product, $product->getEntityId());
                
        if ($tierPrice>-1) {
        	$finalPrice = min(array($tierPrice, $finalPrice));
        }
        
        
        
        $product->getTypeInstance(true)
            ->setStoreFilter($product->getStore(), $product);
        $attributes = $product->getTypeInstance(true)
            ->getConfigurableAttributes($product);

        $selectedAttributes = array();
        if ($product->getCustomOption('attributes')) {
            $selectedAttributes = unserialize($product->getCustomOption('attributes')->getValue());
        }

        $basePrice = $finalPrice;
        foreach ($attributes as $attribute) {
            $attributeId = $attribute->getProductAttribute()->getId();
            $value = $this->_getValueByIndex(
                $attribute->getPrices() ? $attribute->getPrices() : array(),
                isset($selectedAttributes[$attributeId]) ? $selectedAttributes[$attributeId] : null
            );
            if($value) {
                if($value['pricing_value'] != 0) {
                    $finalPrice += $this->_calcSelectionPrice($value, $basePrice);
                }
            }
        }
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

    /* Copyright Karen baker */
    public function _applyWebshopAppsTierPrice($finalPrice,$baseProduct, $baseProductId)
    {
        $tierPrice = -1;
        $items = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
        $tieredItems=array();
        $configSharedQtys = array();
        foreach($items as $item) {
        	if ( $item->getProductType() != Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {
				continue;
			}			
            $productId = $item->getProductId();
        	
			$productList = Mage::getModel('catalog/product')->getResourceCollection()
                ->addAttributeToSelect('cross_tier_id')
                ->addIdFilter($productId);
                
		  // Because we filtered by productId in the Collection above, and productId is unique, there will only be one item in the collection
            foreach($productList as $product)
            {
                continue;
            }
			
	        $crossTierId = $product->getAttributeText('cross_tier_id');
            $entityId = $item->getProductId();
	        $qty = $item->getQty();
            $configSharedQtys[$entityId][] = $qty;
            
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
        	if ($tieredItem['marked'] && $tieredItem['multiple']) {
        		$currentProduct = Mage::getModel('catalog/product')->load($baseProductId);
        		$tierPrice = $currentProduct->getTierPrice($tieredItem['qty']);	
        		break;
        	}        	
        }
        
    	if(array_key_exists($baseProductId, $configSharedQtys))
        {
        	$totalQty = array_sum($configSharedQtys[$baseProductId]);
        	if ($tierPrice>-1) {
        		$tierPrice = min(array($this->getTierPrice($totalQty, $baseProduct),$tierPrice));
        	} else {
        		$tierPrice =$this->getTierPrice($totalQty, $baseProduct);
        	}
        }        
        
        return $tierPrice;
    }
    
}