<?php

/**
 * Atlas Extensions
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Atlas Commercial License
 * that is available through the world-wide-web at this URL:
 *
 * @copyright   Copyright (c) 2015 Atlas Extensions
 * @license     Commercial
 */
class Atlas_Bannerslider_Block_Adminhtml_Bannerslider_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        $this->setId('bannerGrid');
        $this->setUseAjax(true);
        $this->setDefaultSort('bannerslider_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        parent::__construct();
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel("bannerslider/bannerslider")->getCollection();
        $this->setCollection($collection);
        parent::_prepareCollection();
        foreach ($collection as $link)
        {
            if ($link->getStoreId() && $link->getStoreId() != 0)
            {
                $link->setStoreId(explode(',', $link->getStoreId()));
            }
            else
            {
                $link->setStoreId(array('0'));
            }
        }
        return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumn('bannerslider_id', array(
            'header' => 'ID',
            'align' => 'right',
            'width' => '50px',
            'index' => 'bannerslider_id',
        ));
        $this->addColumn('img', array(
            'header' => 'Image',
            'align' => 'center',
            'index' => 'img',
            'width' => '100',
            'renderer' => 'Atlas_Bannerslider_Block_Adminhtml_Bannerslider_Grid_Renderer_Image'
        ));
        $this->addColumn('title', array(
            'header' => 'Title',
            'align' => 'left',
            'index' => 'title',
        ));

        $this->addColumn('group_id', array(
            'header' => 'Group',
            'align' => 'right',
            'width' => '50px',
            'renderer' => 'Atlas_Bannerslider_Block_Adminhtml_Bannerslider_Grid_Renderer_Groupname'
        ));

        if (!Mage::app()->isSingleStoreMode())
        {
            $this->addColumn('store_id', array(
                'header' => 'Store View',
                'index' => 'store_id',
                'type' => 'store',
                'store_all' => true,
                'store_view' => true,
                'sortable' => false,
                'filter_condition_callback' => array($this, '_filterStoreCondition'),
            ));
        }


        $this->addColumn('status', array(
            'header' => 'Status',
            'align' => 'left',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                1 => 'Enabled',
                0 => 'Disabled',
            ),
        ));
        $this->addColumn('action', array(
            'header' => 'Action',
            'width' => '50px',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => 'Edit',
                    'url' => array(
                        'base' => '*/*/edit',
                        'params' => array('store' => $this->getRequest()->getParam('store'))
                    ),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
        ));
        $this->addColumn('delete', array(
            'header' => 'Remove',
            'width' => '50px',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => 'Delete',
                    'confirm' => 'Are you sure?',
                    'url' => array(
                        'base' => '*/*/delete',
                        'params' => array('store' => $this->getRequest()->getParam('store'))
                    ),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
        ));


        return parent::_prepareColumns();
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue())
        {
            return;
        }
        $this->getCollection()->addFieldToFilter('store_id', array('finset' => $value));
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('banner_id');
        $this->getMassactionBlock()->setFormFieldName('ids');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => 'Delete',
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => 'Are you sure?',
        ));

        $this->getMassactionBlock()->addItem('status', array(
            'label' => 'Change status',
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => 'Status',
                    'values' => array(
                        1 => 'Enabled',
                        0 => 'Disabled',
                    )
                ))
        ));

        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}

?>