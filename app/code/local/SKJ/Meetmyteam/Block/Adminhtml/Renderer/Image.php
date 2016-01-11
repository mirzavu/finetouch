<?php
/**
 * Render block for my team
 *
 *
 * @category   SKJ
 * @package    SKJ_Meetmyteam
 * @author     Sanjeev Kumar Jha <jha.sanjeev.in@gmail.com>
 */
class SKJ_Meetmyteam_Block_Adminhtml_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
	 
	public function render(Varien_Object $row)
	{

		$html = '<img ';
		$html .= 'id="' . $row->getId() . '" ';
		$html .= 'src="'. Mage::getBaseUrl('media') . $row->getFilename() . '"';
		$html .= 'class="grid-image ' . $this->getColumn()->getInlineCss().'"';
		$html .= 'style="weight:30px;height:30px"' . '"/>';
		return $html;
	}


}
