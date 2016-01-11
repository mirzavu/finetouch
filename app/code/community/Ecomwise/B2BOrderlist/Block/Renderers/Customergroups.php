<?php
class Ecomwise_B2BOrderlist_Block_Renderers_Customergroups extends Varien_Data_Form_Element_Multiselect{
    
	protected function _optionToHtml($option, $selected)
	{
		$is_new = false;
		$current_category_id = Mage::registry('current_category')->getId();
		if(empty($current_category_id)){
			$is_new = true;
		}
		$html = '<option value="'.$this->_escape($option['value']).'"';
		$html.= isset($option['title']) ? 'title="'.$this->_escape($option['title']).'"' : '';
		$html.= isset($option['style']) ? 'style="'.$option['style'].'"' : '';
		if (($option['value'] == "ALL" && $is_new) || in_array((string)$option['value'], $selected)) {
			$html.= ' selected="selected"';
		}
		$html.= '>'.$this->_escape($option['label']). '</option>'."\n";
		return $html;
	}
}	