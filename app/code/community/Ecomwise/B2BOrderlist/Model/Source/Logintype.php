<?php 
class Ecomwise_B2BOrderlist_Model_Source_Logintype
{	
	public function toOptionArray()
	{		
		$ret = array();			
		$ret[] = array('value'=>0, 'label'=>Mage::helper('b2borderlist')->__('Login only'));		
		$ret[] = array('value'=>1, 'label'=>Mage::helper('b2borderlist')->__('Register and Login' ));		
		return $ret;			
	}	
	
}