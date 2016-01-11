<?php
$installer = $this;
 
// Required tables
$statusTable = $installer->getTable('sales/order_status');
$statusStateTable = $installer->getTable('sales/order_status_state');
 
// Insert statuses
$installer->getConnection()->insertArray(
    $statusTable,
    array(
        'status',
        'label'
    ),
    array(
        array('status' => 'payonaccountplus_pending', 'label' => 'Placed by Credit Limit'),       
    )
);
 
// Insert states and mapping of statuses to states
$installer->getConnection()->insertArray(
    $statusStateTable,
    array(
        'status',
        'state',
        'is_default'
    ),
    array(
        array(
            'status' => 'payonaccountplus_pending',
            'state' => 'payonaccountplus_state',
            'is_default' => 1
        ),        
    )
);