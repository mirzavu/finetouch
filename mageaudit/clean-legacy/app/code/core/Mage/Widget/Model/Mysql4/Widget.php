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
 * @package     Mage_Widget
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

/**
 * Preconfigured widget
 *
 * @category    Mage
 * @package     Mage_Widget
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Widget_Model_Mysql4_Widget extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('widget/widget', 'widget_id');
    }

    /**
     * Retrieves preconfigured parameters for widget
     *
     * @param int $widgetId
     * @return array
     */
    public function loadPreconfiguredWidget($widgetId)
    {
        $read = $this->_getReadAdapter();
        $select = $read->select();
        $select->from($this->getMainTable())
            ->where($this->getIdFieldName() . ' = ?', $widgetId);

        $widget = $read->fetchRow($select);
        if (is_array($widget)) {
            if ($widget['parameters']) {
                $widget['parameters'] = unserialize($widget['parameters']);
            }
            return $widget;
        }
        return false;
    }
}
