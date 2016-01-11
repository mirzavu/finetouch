<?php

$installer = $this;

$installer->startSetup();

$installer->run("

select @entity_type_id:=entity_type_id from {$this->getTable('eav_entity_type')} where entity_type_code='catalog_product';

insert ignore into {$this->getTable('eav_attribute')} 
    set entity_type_id 	= @entity_type_id,
    	attribute_code 	= 'cross_tier_id',
    	backend_type	= 'int',
    	frontend_input	= 'select',
    	is_required	= 0,
    	is_user_defined	= 1,
    	source_model = 'eav/entity_attribute_source_table',
    	frontend_label	= 'Cross Tier Id';
    	
select @attribute_id:=attribute_id from {$this->getTable('eav_attribute')} where attribute_code='cross_tier_id';

insert ignore into {$this->getTable('catalog_eav_attribute')} 
    set attribute_id 	= @attribute_id,
    	is_visible 	= 1,
    	used_in_product_listing	= 1,
    	is_filterable_in_search	= 1;
    	

    	
");

$installer->endSetup();


