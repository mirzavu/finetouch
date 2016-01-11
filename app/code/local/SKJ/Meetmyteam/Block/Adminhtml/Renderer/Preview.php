<?php
/**
 * Render for my team
 *
 *
 * @category   SKJ
 * @package    SKJ_Meetmyteam
 * @author     Sanjeev Kumar Jha <jha.sanjeev.in@gmail.com>
 */
class SKJ_Meetmyteam_Block_Adminhtml_Renderer_Preview extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

	public function render(Varien_Object $row)
	{
		$categoryId =  $row->getMeetmyteamCatId();
		$href = Mage::getUrl('meetmyteam/index/category',array('id'=>$categoryId));

		return '<a href="'.$href.'" target="_blank">'.$this->__('Preview').'</a>';
	}

}
