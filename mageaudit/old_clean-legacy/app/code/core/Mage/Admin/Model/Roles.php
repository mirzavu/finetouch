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
 * @package     Mage_Admin
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

class Mage_Admin_Model_Roles extends Mage_Core_Model_Abstract
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'admin_roles';

    protected function _construct()
    {
        $this->_init('admin/roles');
    }

    public function update()
    {
        $this->getResource()->update($this);
        return $this;
    }

    public function getUsersCollection()
    {
        return Mage::getResourceModel('admin/roles_user_collection');
    }

    public function getResourcesTree()
    {
        return $this->_buildResourcesArray(null, null, null, null, true);
    }

    public function getResourcesList()
    {
        return $this->_buildResourcesArray();
    }

    public function getResourcesList2D()
    {
        return $this->_buildResourcesArray(null, null, null, true);
    }

    public function getRoleUsers()
    {
        return $this->getResource()->getRoleUsers($this);
    }

    protected function _buildResourcesArray(Varien_Simplexml_Element $resource=null, $parentName=null, $level=0, $represent2Darray=null, $rawNodes = false, $module = 'adminhtml')
    {
        static $result;
        if (is_null($resource)) {
            $resource = Mage::getSingleton('admin/config')->getAdminhtmlConfig()->getNode('acl/resources');
            $resourceName = null;
            $level = -1;
        } else {
            $resourceName = $parentName;
            if ($resource->getName()!='title' && $resource->getName()!='sort_order' && $resource->getName() != 'children') {
                $resourceName = (is_null($parentName) ? '' : $parentName.'/').$resource->getName();

                //assigning module for its' children nodes
                if ($resource->getAttribute('module')) {
                    $module = (string)$resource->getAttribute('module');
                }

                if ($rawNodes) {
                    $resource->addAttribute("aclpath", $resourceName);
                    $resource->addAttribute("module_c", $module);
                }

                if ( is_null($represent2Darray) ) {
                    $result[$resourceName]['name']  = Mage::helper($module)->__((string)$resource->title);
                    $result[$resourceName]['level'] = $level;
                } else {
                    $result[] = $resourceName;
                }
            }
        }

        $children = $resource->children();
        if (empty($children)) {
            if ($rawNodes) {
                return $resource;
            } else {
                return $result;
            }
        }
        foreach ($children as $child) {
            $this->_buildResourcesArray($child, $resourceName, $level+1, $represent2Darray, $rawNodes, $module);
        }
        if ($rawNodes) {
            return $resource;
        } else {
            return $result;
        }
    }

}
