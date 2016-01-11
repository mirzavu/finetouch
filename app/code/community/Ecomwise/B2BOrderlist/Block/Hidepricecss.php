<?php 

class Ecomwise_B2BOrderlist_Block_Hidepricecss extends Mage_Core_Block_Abstract{
	
	public function _toHtml(){
	
		$html = "";
			if(Mage::helper('b2borderlist')->hidePrices()){
					
					$html .= '<style type="text/css">
								.price-excl-tax .price, .price{display:none; !important}
					
						 </style>';
				}
		return $html;
	}
}