<?php
/**
 * MageBase DPS Payment Express
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with Magento in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category    MageBase
 * @package     MageBase_DpsPaymentExpress
 * @author      Kristof Ringleff
 * @copyright   Copyright (c) 2010 MageBase (http://www.magebase.com)
 * @copyright   Copyright (c) 2010 Fooman Ltd (http://www.fooman.co.nz)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class MageBase_DpsPaymentExpress_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Currency codes supported by DPS
     * @var array
     */
    protected $_supportedCurrencyCodes = array('CHF','EUR','FRF','GBP','HKD','JPY','NZD','SGD','USD','ZAR',
                                                        'AUD','WST','VUV','TOP','SBD','PNG','MYR','KWD','FJD');

    public function canUseCurrency($currencyCode)
    {
        return in_array($currencyCode, $this->_supportedCurrencyCodes);
    }

    public function getAdditionalData($info,$key = null)
    {
        $data = array();
        if ($info->getAdditionalData()) {
            $data = unserialize($info->getAdditionalData());
        }
        if (!empty($key) && isset($data[$key])) {
            return $data[$key];
        } else {
            return '';
        }
    }
}