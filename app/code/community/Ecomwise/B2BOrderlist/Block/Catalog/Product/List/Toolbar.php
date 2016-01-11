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
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @copyright  Copyright (c) 2006-2014 X.commerce, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Product list toolbar
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Ecomwise_B2BOrderlist_Block_Catalog_Product_List_Toolbar extends Mage_Catalog_Block_Product_List_Toolbar
{
    
    protected function _construct()
    {
        parent::_construct();
		$this->_orderField  = Mage::getStoreConfig(
				Mage_Catalog_Model_Config::XML_PATH_LIST_DEFAULT_SORT_BY
		);
	
		$this->_availableOrder = $this->_getConfig()->getAttributeUsedForSortByArray();
	
		switch (Mage::getStoreConfig('catalog/frontend/list_mode')) {
			case 'grid':
				$this->_availableMode = array('grid' => $this->__('Grid'));
				break;
	
			case 'list':
				$this->_availableMode = array('list' => $this->__('List'));
				break;
	
			case 'grid-list':
				$this->_availableMode = array('grid' => $this->__('Grid'), 'list' =>  $this->__('List'));
				break;
	
			case 'list-grid':
				$this->_availableMode = array('list' => $this->__('List'), 'grid' => $this->__('Grid'));
				break;
		}
		
		if(Mage::helper("b2borderlist")->isActive()){		
			if(!Mage::getStoreConfig("b2borderlist/categories/enabled")){		
				$this->_availableMode = array('grid' => $this->__('Grid'), 'list' =>  $this->__('List'), 'order_list' =>  $this->__('Order List')  );
			}else {					
				$current_cat = Mage::registry('current_category')->getId();					
				$selected_cat = Mage::getStoreConfig("b2borderlist/categories/categories");					
				$selected_cat = explode(',',$selected_cat);					
				if (in_array($current_cat, $selected_cat)) {					
					$this->_availableMode = array('grid' => $this->__('Grid'), 'list' =>  $this->__('List'), 'order_list' =>  $this->__('Order List')  );
				}
			}		
		}		
		
		$this->setTemplate('catalog/product/list/toolbar.phtml');
		
    }

    
}
