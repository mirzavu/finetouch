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
 * @package     Mage_Sales
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

/**
 * Flat sales order shipment comments collection
 *
 */
class Mage_Sales_Model_Mysql4_Order_Shipment_Comment_Collection extends Mage_Sales_Model_Mysql4_Order_Comment_Collection_Abstract
{
    /*
     * @var string
     */
    protected $_eventPrefix = 'sales_order_shipment_comment_collection';

    /*
     * @var string
     */
    protected $_eventObject = 'order_shipment_comment_collection';

    /*
     * Inits shipment comment collection
     *
     * @return void
     */
    protected function _construct()
    {
    	parent::_construct();
        $this->_init('sales/order_shipment_comment');
    }

    /**
     * Set shipment filter
     *
     * @param int $shipmentId
     * @return Mage_Sales_Model_Mysql4_Order_Shipment_Comment_Collection
     */
    public function setShipmentFilter($shipmentId)
    {
        return $this->setParentFilter($shipmentId);
    }
}
