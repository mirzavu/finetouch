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
 * @package     Mage_Checkout
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

/**
 * One page checkout status
 *
 * @category   Mage
 * @package    Mage_Checkout
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Checkout_Block_Onepage_Progress extends Mage_Checkout_Block_Onepage_Abstract
{
    public function getBilling()
    {
        return $this->getQuote()->getBillingAddress();
    }

    public function getShipping()
    {
        return $this->getQuote()->getShippingAddress();
    }

    public function getShippingMethod()
    {
        return $this->getQuote()->getShippingAddress()->getShippingMethod();
    }

    public function getShippingDescription()
    {
        return $this->getQuote()->getShippingAddress()->getShippingDescription();
    }

    public function getShippingAmount()
    {
        /*$amount = $this->getQuote()->getShippingAddress()->getShippingAmount();
        $filter = Mage::app()->getStore()->getPriceFilter();
        return $filter->filter($amount);*/
        //return $this->helper('checkout')->formatPrice(
        //    $this->getQuote()->getShippingAddress()->getShippingAmount()
        //);
        return $this->getQuote()->getShippingAddress()->getShippingAmount();
    }

    public function getPaymentHtml()
    {
        return $this->getChildHtml('payment_info');
    }

    /**
     * Get quote shipping price including tax
     * @return float
     */
    public function getShippingPriceInclTax()
    {
        $inclTax = $this->getQuote()->getShippingAddress()->getShippingInclTax();
        return $this->formatPrice($inclTax);
    }

    public function getShippingPriceExclTax()
    {
        return $this->formatPrice($this->getQuote()->getShippingAddress()->getShippingAmount());
    }

    public function formatPrice($price)
    {
        return $this->getQuote()->getStore()->formatPrice($price);
    }
}
