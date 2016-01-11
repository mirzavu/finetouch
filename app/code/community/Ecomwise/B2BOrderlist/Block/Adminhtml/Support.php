<?php
class Ecomwise_B2BOrderlist_Block_Adminhtml_Support extends Mage_Adminhtml_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{
	
	protected $_template = 'b2borderlist/support.phtml';
	
	public $module = 'Ecomwise_B2BOrderlist';
	public $supportUrl = 'http://support.ecomwise.com/support/tickets/new';
	public $email = 'feedback@ecomwise.com';
	public $faq = 'http://support.ecomwise.com/support/solutions/folders/111251';
	public $name = 'B2B Order List';
	public $compatibility = 'Magento CE 1.7-1.9';
	public $manualUrl = 'http://support.ecomwise.com/support/solutions/articles/3000001973-b2b-order-list';
	
	public function render(Varien_Data_Form_Element_Abstract $element) {
        return $this->toHtml();
    }
}  