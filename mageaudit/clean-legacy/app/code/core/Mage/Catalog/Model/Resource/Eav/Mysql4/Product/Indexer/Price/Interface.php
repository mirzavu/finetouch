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
 * @category    Mage
 * @package     Mage_Catalog
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */


/**
 * Catalog Product Type Price Indexer interface
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
interface Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Indexer_Price_Interface
{
    /**
     * Reindex temporary (price result data) for all products
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Indexer_Price_Interface
     */
    public function reindexAll();

    /**
     * Reindex temporary (price result data) for defined product(s)
     *
     * @param int|array $entityIds
     * @param bool $hasOptions  the entity has custom options flag
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Indexer_Price_Interface
     */
    public function reindexEntity($entityIds);

    /**
     * Register data required by product type process in event object
     *
     * @param Mage_Index_Model_Event $event
     */
    public function registerEvent(Mage_Index_Model_Event $event);
}