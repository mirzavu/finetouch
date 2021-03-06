<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition License
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magentocommerce.com/license/enterprise-edition
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
 * @category    Find
 * @package     Find_Feed
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */


/**
 * TheFind feed attribute map grid container
 *
 * @category    Find
 * @package     Find_Feed
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Find_Feed_Block_Adminhtml_List_Codes extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Initialize grid container settings
     *
     */
    public function __construct()
    {
        $this->_blockGroup      = 'find_feed';
        $this->_controller      = 'adminhtml_list_codes';
        $this->_headerText      = Mage::helper('find_feed')->__('Attributes map');
        $this->_addButtonLabel  = Mage::helper('find_feed')->__('Add new');

        parent::__construct();

        $url = $this->getUrl('*/codes_grid/editForm');
        $this->_updateButton('add', 'onclick', 'openNewImportWindow(\''.$url.'\');');
    }
}
