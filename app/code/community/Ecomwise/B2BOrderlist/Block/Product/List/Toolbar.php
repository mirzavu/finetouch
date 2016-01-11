<?php 
class Ecomwise_B2bOrderlist_Block_Product_List_Toolbar extends Mage_Catalog_Block_Product_List_Toolbar {
	
	/**
	 * Init Toolbar
	 *
	 */
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

		$selectedCustomerGroups = Mage::getStoreConfig("b2borderlist/parameters/groups"); 				
		if(Mage::getSingleton('customer/session')->isLoggedIn()){
			$customerGroup = Mage::getSingleton('customer/session')->getCustomer()->getGroupId();
		}else{
			$customerGroup = 0;
		}
		

		if(Mage::helper("b2borderlist")->isActive()){	

			$current_cat = Mage::registry('current_category')->getId();
			$selected_cat = Mage::getStoreConfig("b2borderlist/categories/categories");
			$selected_cat = explode(',',$selected_cat);
			
			if (!Mage::getStoreConfig("b2borderlist/categories/enabled") || (Mage::getStoreConfig("b2borderlist/categories/enabled") && in_array($current_cat, $selected_cat))) {
				if (in_array($customerGroup,explode(',',$selectedCustomerGroups))) {
					
					switch (Mage::getStoreConfig('b2borderlist/parameters/list_mode')) {
						case 'grid':
							$this->_availableMode = array('grid' => $this->__('Grid'));
							break;
					
						case 'list':
							$this->_availableMode = array('list' => $this->__('List'));
							break;

						case 'grid-list':
							$this->_availableMode = array('grid' => $this->__('Grid'), 'list' =>  $this->__('List') );
							break;
								
						case 'list-grid':
							$this->_availableMode = array('list' =>  $this->__('List'), 'grid' => $this->__('Grid'),);
							break;
							
						case 'grid-list-order_list':
							$this->_availableMode = array('grid' => $this->__('Grid'), 'list' =>  $this->__('List'), 'order_list' =>  $this->__('Order List')  );
							break;
					
						case 'list-grid-order_list':							
							$this->_availableMode = array('list' =>  $this->__('List'), 'grid' => $this->__('Grid'), 'order_list' =>  $this->__('Order List')  );
							break;
					
						case 'order_list-list-grid':
							$this->_availableMode = array('order_list' =>  $this->__('Order List'), 'list' =>  $this->__('List'), 'grid' => $this->__('Grid'));
							break;
					
						case 'order_list-grid-list':
							$this->_availableMode = array('order_list' =>  $this->__('Order List'),  'grid' => $this->__('Grid'), 'list' =>  $this->__('List'));
							break;
					
						case 'order_list':
							$this->_availableMode = array('order_list' =>  $this->__('Order List'));
							break;
					}					
				}
			}
		}		
		
		$this->setTemplate('catalog/product/list/toolbar.phtml');
	}	
	
	/**
	 * Retrieve available limits for current view mode
	 *
	 * @return array
	*/
	public function getAvailableLimit()
	{
		$currentMode = $this->getCurrentMode();
		if (in_array($currentMode, array('list', 'grid'))) {
			return $this->_getAvailableLimit($currentMode);
		} elseif (in_array($currentMode, array('order_list'))) {			
			return $this->_getOrderListLimits();		
		} else {
			return $this->_defaultAvailableLimit;
		}
	}
	
	protected function _getOrderListLimits(){
		if (isset($this->_availableLimit[$mode])) {
			return $this->_availableLimit[$mode];
		}		
		$perPageConfigKey = 'b2borderlist/parameters/per_page_values';
		$perPageValues = (string)Mage::getStoreConfig($perPageConfigKey);
		$perPageValues = explode(',', $perPageValues);
		$perPageValues = array_combine($perPageValues, $perPageValues);
		if (Mage::getStoreConfigFlag('b2borderlist/parameters/allow_all')) {
			return ($perPageValues + array('all'=>$this->__('All')));
		} else {
			return $perPageValues;
		}		
	}
	
	public function getLimit()
    {
    	if ($this->getCurrentMode() == 'order_list') {
    		$limit = Mage::getStoreConfig('b2borderlist/parameters/per_page');
			return $limit;
		}	
		
        $limit = $this->_getData('_current_limit');
        if ($limit) {
            return $limit;
        }

        $limits = $this->getAvailableLimit();
        $defaultLimit = $this->getDefaultPerPageValue();
        if (!$defaultLimit || !isset($limits[$defaultLimit])) {
            $keys = array_keys($limits);
            $defaultLimit = $keys[0];
        }

        $limit = $this->getRequest()->getParam($this->getLimitVarName());
        if ($limit && isset($limits[$limit])) {
            if ($limit == $defaultLimit) {
                Mage::getSingleton('catalog/session')->unsLimitPage();
            } else {
                $this->_memorizeParam('limit_page', $limit);
            }
        } else {
            $limit = Mage::getSingleton('catalog/session')->getLimitPage();
        }
        if (!$limit || !isset($limits[$limit])) {
            $limit = $defaultLimit;
        }

        $this->setData('_current_limit', $limit);       
        return $limit;
    }
    
	/**
	 * Retrieve default per page values
	 *
	 * @return string (comma separated)
	 */
	public function getDefaultPerPageValue()
	{
		if ($this->getCurrentMode() == 'list') {
			if ($default = $this->getDefaultListPerPage()) {
				return $default;
			}
			return Mage::getStoreConfig('catalog/frontend/list_per_page');
		}
		elseif ($this->getCurrentMode() == 'grid') {
			if ($default = $this->getDefaultGridPerPage()) {
				return $default;
			}
			return Mage::getStoreConfig('catalog/frontend/grid_per_page');
		}
		elseif ($this->getCurrentMode() == 'order_list') {			
			return Mage::getStoreConfig('b2borderlist/parameters/per_page');
		}		
		return 0;
	}
}