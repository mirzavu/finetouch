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
 * @package     Mage_Core
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

/**
 * Templates collection
 * 
 * @category   Mage
 * @package    Mage_Core
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Core_Model_Mysql4_Email_Template_Collection extends Varien_Data_Collection_Db
{
    /**
     * Template table name
     *
     * @var string
     */
    protected $_templateTable;
    
    public function __construct()
    {
        parent::__construct(Mage::getSingleton('core/resource')->getConnection('core_read'));
        $this->_templateTable = Mage::getSingleton('core/resource')->getTableName('core/email_template');
        $this->_select->from($this->_templateTable, array('template_id','template_code',
                                                             'template_type',
                                                             'template_subject','template_sender_name',
                                                             'template_sender_email',
                                                             'added_at',
                                                             'modified_at'));
        $this->setItemObjectClass(Mage::getConfig()->getModelClassName('core/email_template'));
    }
                
    public function toOptionArray()
    {
        return $this->_toOptionArray('template_id', 'template_code');
    }
    
}