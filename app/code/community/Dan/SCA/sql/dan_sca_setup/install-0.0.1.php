<?php
	$installer = $this;
	$installer->startSetup();


	// create state table (non-EAV)
	$table = new Varien_Db_Ddl_Table();
	$table->setName($installer->getTable('dan_sca/state'));
	$table->addColumn(
	    'entity_id',
	    Varien_Db_Ddl_Table::TYPE_INTEGER,
	    10,
	    array(
	        'auto_increment' => true,
	        'unsigned' => true,
	        'nullable'=> false,
	        'primary' => true
	    )
	);
	$table->addColumn(
	    'name',
	    Varien_Db_Ddl_Table::TYPE_VARCHAR,
	    255,
	    array(
	        'nullable' => false,
	    )
	);
	$table->addColumn(
	    'abbreviation',
	    Varien_Db_Ddl_Table::TYPE_VARCHAR,
	    2,
	    array(
	        'nullable' => false,
	    )
	);
	$table->addColumn(
	    'url_key',
	    Varien_Db_Ddl_Table::TYPE_VARCHAR,
	    255,
	    array(
	        'nullable' => false,
	    )
	);
	$table->addColumn(
	    'description',
	    Varien_Db_Ddl_Table::TYPE_TEXT,
	    null,
	    array(
	        'nullable' => false,
	    )
	);
	$table->addColumn(
	    'requires_safety_card',
	    Varien_Db_Ddl_Table::TYPE_BOOLEAN,
	    null,
	    array(
	        'nullable' => false,
	    )
	);
	$table->addColumn(
	    'convenience_fee',
	    Varien_Db_Ddl_Table::TYPE_DECIMAL, 
		'12,4',
		array(),
		'Convenience Fee'
	);
	$table->addColumn(
	    'created_at',
	    Varien_Db_Ddl_Table::TYPE_DATETIME,
	    null,
	    array(
	        'nullable' => false,
	    )
	);
	$table->addColumn(
	    'updated_at',
	    Varien_Db_Ddl_Table::TYPE_DATETIME,
	    null,
	    array(
	        'nullable' => false,
	    )
	);
	$table->setOption('type', 'InnoDB');
	$table->setOption('charset', 'utf8');
	$installer->getConnection()->createTable($table);
	
	
	
	// create animal table (non-EAV) 
	$table = new Varien_Db_Ddl_Table();
	$table->setName($installer->getTable('dan_sca/animal'));
	$table->addColumn(
	    'entity_id',
	    Varien_Db_Ddl_Table::TYPE_INTEGER,
	    10,
	    array(
	        'auto_increment' => true,
	        'unsigned' => true,
	        'nullable'=> false,
	        'primary' => true
	    )
	);
	$table->addColumn(
	    'name',
	    Varien_Db_Ddl_Table::TYPE_VARCHAR,
	    55,
	    array(
	        'nullable' => false,
	    )
	);
	$table->setOption('type', 'InnoDB');
	$table->setOption('charset', 'utf8');
	$installer->getConnection()->createTable($table);



	
	// create gameunit table (non-EAV) 
	$table = new Varien_Db_Ddl_Table();
	$table->setName($installer->getTable('dan_sca/gameunit'));
	$table->addColumn(
	    'entity_id',
	    Varien_Db_Ddl_Table::TYPE_INTEGER,
	    10,
	    array(
	        'auto_increment' => true,
	        'unsigned' => true,
	        'nullable'=> false,
	        'primary' => true
	    )
	);	
	$table->addColumn(
	    'parent_id',
	    Varien_Db_Ddl_Table::TYPE_INTEGER,
	    10,
	    array(
	        'unsigned' => true,
	        'nullable'=> false
	    )
	);
	$table->addColumn(
	    'name',
	    Varien_Db_Ddl_Table::TYPE_VARCHAR,
	    255,
	    array(
	        'nullable' => false,
	    )
	);
	$table->addColumn(
	    'website_id',
	    Varien_Db_Ddl_Table::TYPE_VARCHAR,
	    255,
	    array(
	        'nullable' => false,
	    )
	);
	$table->addColumn(
	    'url_key',
	    Varien_Db_Ddl_Table::TYPE_VARCHAR,
	    255,
	    array(
	        'nullable' => false,
	    )
	);
	$table->addColumn(
	    'description',
	    Varien_Db_Ddl_Table::TYPE_TEXT,
	    null,
	    array(
	        'nullable' => false,
	    )
	);
	$table->addColumn(
	    'unit_size',
	    Varien_Db_Ddl_Table::TYPE_DECIMAL, 
		'12,4',
		array(),
		'Unit Size (sq mi)'
	);
	$table->addColumn(
	    'members_only',
	    Varien_Db_Ddl_Table::TYPE_BOOLEAN,
	    null,
	    array(
	        'nullable' => false,
			'default' => 1
	    )
	);
	$table->addColumn(
	    'created_at',
	    Varien_Db_Ddl_Table::TYPE_DATETIME,
	    null,
	    array(
	        'nullable' => false,
	    )
	);
	$table->addColumn(
	    'updated_at',
	    Varien_Db_Ddl_Table::TYPE_DATETIME,
	    null,
	    array(
	        'nullable' => false,
	    )
	);
	$table->addIndex($installer->getIdxName('dan_sca_state_gameunit',
            	array('parent_id'),
            	Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
        	),
        	array('parent_id'),
        	array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX));
	$table->addIndex($installer->getIdxName('dan_sca_state_gameunit',
				array('parent_id', 'name'),
				Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
	        ),
	        array('parent_id', 'name'),
	        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE));
	$table->addIndex($installer->getIdxName('dan_sca_state_gameunit',
	            array('url_key'),
	            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
	        ),
	        array('url_key'),
			array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE));
    $table->addForeignKey($installer->getFkName('dan_sca_state_gameunit', 
			'parent_id', 
			'dan_sca/state',
			'entity_id'
		),
		'parent_id',
		$installer->getTable('dan_sca/state'), 
		'entity_id',
		Varien_Db_Ddl_Table::ACTION_CASCADE, 
		Varien_Db_Ddl_Table::ACTION_CASCADE);
	$table->setOption('type', 'InnoDB');
	$table->setOption('charset', 'utf8');
	$installer->getConnection()->createTable($table);
	
	
	
	// create [gameunit] detail table (non-EAV) 
	$table = new Varien_Db_Ddl_Table();
	$table->setName($installer->getTable('dan_sca/gameunit_detail'));
	$table->addColumn(
	    'entity_id',
	    Varien_Db_Ddl_Table::TYPE_INTEGER,
	    10,
	    array(
	        'auto_increment' => true,
	        'unsigned' => true,
	        'nullable'=> false,
	        'primary' => true
	    )
	);	
	$table->addColumn(
	    'parent_id',
	    Varien_Db_Ddl_Table::TYPE_INTEGER,
	    10,
	    array(
	        'unsigned' => true,
	        'nullable'=> false
	    )
	);
	$table->addColumn(
	    'year',
	    Varien_Db_Ddl_Table::TYPE_VARCHAR,
	    7,
	    array(
	        'nullable' => false,
	    )
	);
	$table->addColumn(
	    'weapon',
	    Varien_Db_Ddl_Table::TYPE_VARCHAR,
	    25,
	    array(
	        'nullable' => false,
	    )
	);
	$table->addColumn(
	    'animal_id',
	    Varien_Db_Ddl_Table::TYPE_INTEGER,
	    10,
	    array(
	        'unsigned' => true,
	        'nullable'=> false
	    )
	);
	$table->addColumn(
	    'available_tags',
	    Varien_Db_Ddl_Table::TYPE_INTEGER,
	    10,
	    array(
	        'nullable' => false,
	    )
	);
	$table->addColumn(
	    'tag_applications',
	    Varien_Db_Ddl_Table::TYPE_INTEGER,
	    10,
	    array(
	        'nullable' => false,
	    )
	);
	$table->addColumn(
	    'num_tagged_out',
	    Varien_Db_Ddl_Table::TYPE_INTEGER,
	    10,
	    array(
	        'nullable' => false,
	    )
	);
	$table->addColumn(
	    'num_animals',
	    Varien_Db_Ddl_Table::TYPE_INTEGER,
	    10,
	    array(
	        'nullable' => false,
	    )
	);
	$table->addColumn(
	    'created_at',
	    Varien_Db_Ddl_Table::TYPE_DATETIME,
	    null,
	    array(
	        'nullable' => false,
	    )
	);
	$table->addColumn(
	    'updated_at',
	    Varien_Db_Ddl_Table::TYPE_DATETIME,
	    null,
	    array(
	        'nullable' => false,
	    )
	);
	$table->addIndex($installer->getIdxName('dan_sca_state_gameunit_detail',
            array(
				'year'
			),
            Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
        	),
        	array(
				'year'
			),
        	array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX));
	$table->addIndex($installer->getIdxName('dan_sca_state_gameunit_detail',
            array(
				'animal_id'
			),
            Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
        	),
        	array(
				'animal_id'
			),
        	array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX));
	$table->addIndex($installer->getIdxName('dan_sca_state_gameunit_detail',
            array(
				'parent_id'
			),
            Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
        	),
        	array(
				'parent_id'
			),
        	array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX));
    $table->addForeignKey($installer->getFkName('dan_sca_state_gameunit_detail', 
			'parent_id', 
			'dan_sca/gameunit',
			'entity_id'
		),
		'parent_id',
		$installer->getTable('dan_sca/gameunit'), 
		'entity_id',
		Varien_Db_Ddl_Table::ACTION_CASCADE, 
		Varien_Db_Ddl_Table::ACTION_CASCADE);
    $table->addForeignKey($installer->getFkName('dan_sca_state_gameunit_detail', 
			'animal_id', 
			'dan_sca/animal',
			'entity_id'
		),
		'animal_id',
		$installer->getTable('dan_sca/animal'), 
		'entity_id',
		Varien_Db_Ddl_Table::ACTION_CASCADE, 
		Varien_Db_Ddl_Table::ACTION_CASCADE);
	$table->setOption('type', 'InnoDB');
	$table->setOption('charset', 'utf8');
	$installer->getConnection()->createTable($table);
	
	
	// create state info table (non-EAV) 
	$table = new Varien_Db_Ddl_Table();
	$table->setName($installer->getTable('dan_sca/state_info'));
	$table->addColumn(
	    'entity_id',
	    Varien_Db_Ddl_Table::TYPE_INTEGER,
	    10,
	    array(
	        'auto_increment' => true,
	        'unsigned' => true,
	        'nullable'=> false,
	        'primary' => true
	    )
	);	
	$table->addColumn(
	    'parent_id',
	    Varien_Db_Ddl_Table::TYPE_INTEGER,
	    10,
	    array(
	        'unsigned' => true,
	        'nullable'=> false
	    )
	);
	$table->addColumn(
	    'name',
	    Varien_Db_Ddl_Table::TYPE_VARCHAR,
	    55,
	    array(
	        'nullable' => false,
	    )
	);
	$table->addColumn(
	    'attribute_code',
	    Varien_Db_Ddl_Table::TYPE_VARCHAR,
	    255,
	    array(
	        'nullable' => false,
	    )
	);
	$table->addColumn(
	    'input_type',
	    Varien_Db_Ddl_Table::TYPE_VARCHAR,
	    10,
	    array(
	        'nullable' => false,
	    )
	);
	$table->addColumn(
	    'required',
	    Varien_Db_Ddl_Table::TYPE_BOOLEAN,
	    null,
	    array(
	        'nullable' => false,
			'default' => 1
	    )
	);
	$table->addColumn(
	    'created_at',
	    Varien_Db_Ddl_Table::TYPE_DATETIME,
	    null,
	    array(
	        'nullable' => false,
	    )
	);
	$table->addColumn(
	    'updated_at',
	    Varien_Db_Ddl_Table::TYPE_DATETIME,
	    null,
	    array(
	        'nullable' => false,
	    )
	);
	$table->addIndex($installer->getIdxName('dan_sca_state_info',
            array(
				'parent_id'
			),
            Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
        	),
        	array(
				'parent_id'
			),
        	array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX));
    $table->addForeignKey($installer->getFkName(
			'dan_sca_state_info', 
			'parent_id', 
			'dan_sca/state',
			'entity_id'
		),
		'parent_id',
		$installer->getTable('dan_sca/state'), 
		'entity_id',
		Varien_Db_Ddl_Table::ACTION_CASCADE, 
		Varien_Db_Ddl_Table::ACTION_CASCADE);
	$table->setOption('type', 'InnoDB');
	$table->setOption('charset', 'utf8');
	$installer->getConnection()->createTable($table);
	
	
	// create a new attribute set for Draw Entry
	$sNewAttributeSetName = 'Draw Entry';
	$iCatalogProductEntityTypeId = (int) $installer->getEntityTypeId('catalog_product');

	$oAttributeset = Mage::getModel('eav/entity_attribute_set')
	    ->setEntityTypeId($iCatalogProductEntityTypeId)
	    ->setAttributeSetName($sNewAttributeSetName);

	if ($oAttributeset->validate()) {
	    $oAttributeset
	        ->save()
	        ->initFromSkeleton($iCatalogProductEntityTypeId)
	        ->save();
	}
	else {
	    die('Attributeset with name ' . $sNewAttributeSetName . ' already exists.');
	}

	
	// add state_id attribute to products
	// $this->removeAttribute(Mage_Catalog_Model_Product::ENTITY, 'state_id');
	$this->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'state_id', array(
		'attribute_set' => 'Draw Entry',
	    'label'         => 'State',
		'type'			=> 'int',
	    'input'         => 'select',
	    'source'        => 'dan_sca/source_state',
		'searchable'	=> true,
		'user_defined'	=> true 
	));
	
	// add animal_id attribute to products
	// $this->removeAttribute(Mage_Catalog_Model_Product::ENTITY, 'animal_id');
	$this->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'animal_id', array(
		'attribute_set' => 'Draw Entry',
	    'label'         => 'Animal',
	    'input'         => 'multiselect',
		'type'			=> 'varchar',
		'backend'		=> 'eav/entity_attribute_backend_array',
	    'source'        => 'dan_sca/source_animal',
		'searchable'	=> true,
		'user_defined'	=> true 
	));

	// $this->removeAttribute(Mage_Catalog_Model_Product::ENTITY, 'fee_resident');
	$this->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'fee_resident', array(
		'attribute_set' => 'Draw Entry',
	    'label'         => 'Resident Fee',
	    'input'         => 'price',
		'type'			=> 'decimal',
		'user_defined'	=> true 
	));
	
	// $this->removeAttribute(Mage_Catalog_Model_Product::ENTITY, 'fee_non_resident');
	$this->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'fee_non_resident', array(
		'attribute_set' => 'Draw Entry',
	    'label'         => 'Non-Resident Fee',
	    'input'         => 'price',
		'type'			=> 'decimal',
		'user_defined'	=> true 
	));
	

	$attributeSetId = $this->getAttributeSetId('catalog_product', 'Draw Entry');
	$attributeGroupId = $this->getAttributeGroupId('catalog_product', $attributeSetId, 'General');
	
	$attributeList = ['animal_id', 'state_id', 'fee_resident', 'fee_non_resident'];
	
	// add attributes to set
	foreach($attributeList as $_attr){
		$_attributeId = $this->getAttributeId('catalog_product', $_attr);
		$this->addAttributeToSet('catalog_product', $attributeSetId, $attributeGroupId, $_attributeId);
	};
	
	// add 'Members' customer group
	Mage::getSingleton('customer/group')->setData(array('customer_group_code' => 'Members', 'tax_class_id' => 3))
		->save();
	

	// add membership_date to customer entity (to track when a person upgrades)
	$entity = $installer->getEntityTypeId('customer');
	$customerEntities = array();
	

	// $installer->removeAttribute($entity, 'membership_date');
	$installer->addAttribute($entity, 'membership_date', array(
	    'label' 			=> 'Upgraded to Member',
		'input' 			=> 'date',
		'type' 				=> 'datetime',
		'backend'			=> 'eav/entity_attribute_backend_datetime',
	    'global' 			=> true,
	    'visible' 			=> false,
	    'required' 			=> false,
	    'user_defined' 		=> true,
	    'visible_on_front' 	=> false
	));
	$customerEntities['membership_date'] = array('adminhtml_customer');
	
	

	// $installer->removeAttribute($entity, 'state_of_residence');
	$installer->addAttribute($entity, 'state_of_residence', array(
	    'label'				=> 'State of Residence',
		'type'				=> 'int',
	    'input'         	=> 'select',
	    'source'        	=> 'dan_sca/source_state',
	    'visible' 			=> true,
	    'required' 			=> true,
	    'visible_on_front' 	=> true
	));
	$customerEntities['state_of_residence'] = array('customer_account_create', 'customer_account_edit', 'adminhtml_customer');

	
	// $installer->removeAttribute($entity, 'color_eyes');
	$installer->addAttribute($entity, 'color_eyes', array(
	    'label'				=> 'Eye Color',
		'type'				=> 'varchar',
	    'input'         	=> 'select',
		'source'			=> 'dan_sca/source_eyes',
	    'visible' 			=> true,
	    'required' 			=> true,
	    'visible_on_front' 	=> false
	));
	$customerEntities['color_eyes'] = array('customer_account_create', 'adminhtml_customer');
	
	// $installer->removeAttribute($entity, 'color_hair');
	$installer->addAttribute($entity, 'color_hair', array(
	    'label'				=> 'Hair Color',
		'type'				=> 'varchar',
	    'input'         	=> 'select',
		'source'			=> 'dan_sca/source_hair',
	    'visible' 			=> true,
	    'required' 			=> true,
	    'visible_on_front' 	=> true
	));
	$customerEntities['color_hair'] = array('customer_account_create', 'adminhtml_customer', 'customer_account_edit');
	
	// $installer->removeAttribute($entity, 'height');
	$installer->addAttribute($entity, 'height', array(
	    'label'				=> 'Height (inches)',
		'type'				=> 'int',
	    'input'         	=> 'select',
	    'visible' 			=> true,
	    'required' 			=> true,
	    'visible_on_front' 	=> true,
		'source'			=> 'dan_sca/source_height'
	));
	$customerEntities['height'] = array('customer_account_create', 'adminhtml_customer', 'customer_account_edit');
	
	// $installer->removeAttribute($entity, 'weight');
	$installer->addAttribute($entity, 'weight', array(
	    'label'				=> 'Weight (lbs)',
		'type'				=> 'int',
	    'input'         	=> 'text',
	    'visible' 			=> true,
	    'required' 			=> true,
	    'visible_on_front' 	=> true
	));
	$customerEntities['weight'] = array('customer_account_create', 'adminhtml_customer', 'customer_account_edit');

	
	// $installer->removeAttribute($entity, 'poa_on_file');
	$installer->addAttribute($entity, 'poa_on_file', array(
	    'label'				=> 'Power of Attorney On-file?',
		'type' 				=> 'int',
	    'input' 			=> 'boolean',
	    'global' 			=> Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
	    'visible' 			=> true,
	    'required' 			=> true,
	    'visible_on_front' 	=> true,
		'user_defined' 		=> true,
	    'filterable' 		=> true
	));
	$customerEntities['poa_on_file'] = array('adminhtml_customer');
	
	// $installer->removeAttribute($entity, 'update_token');
	$installer->addAttribute($entity, 'update_token', array(
	    'label'				=> 'Post-Checkout Update Token',
		'type' 				=> 'varchar',
	    'input' 			=> 'text',
		'user_defined' 		=> true,
		'required' 			=> false
	));
	$customerEntities['update_token'] = array('adminhtml_customer');

	$attributeSetId   = $installer->getDefaultAttributeSetId($entity);
	$attributeGroupId = $installer->getDefaultAttributeGroupId($entity, $attributeSetId);

	foreach($customerEntities as $_attr => $used_in_forms){
		$attribute = Mage::getSingleton("eav/config")->getAttribute("customer", $_attr);
		
		$installer->addAttributeToGroup(
		    $entity,
		    $attributeSetId,
		    $attributeGroupId,
		    $_attr,
		    '999'
		);

		$attribute->setData("used_in_forms", $used_in_forms)
	            ->setData("is_used_for_customer_segment", true)
	            ->setData("is_system", 0)
	            ->setData("is_user_defined", 1)
	            ->setData("is_visible", 1)
	            ->setData("sort_order", 100);
	    $attribute->save();
	};
	
	/*
	// make product SKU available for shopping cart price rules
	$attributeId = Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'sku');
	if ($attributeId) {
	    $attribute = Mage::getModel('catalog/resource_eav_attribute')->load($attributeId);
	    $attribute->setIsUsedForPromoRules(1)->save();
	};
	*/
	
	
	// create price rule for detecting presence of Membership product ==> reduce all other prices to $0.00
    $rule = Mage::getModel('salesrule/rule');
    $rule->setName('Membership Discount')
      ->setDescription('Reduce all other item prices to $0 if the shopper is in the process of buying a membership')
      ->setCouponType(1)
      ->setCustomerGroupIds(array(0, 1))
      ->setIsActive(1)
      ->setConditionsSerialized('')
      ->setActionsSerialized('')
      ->setStopRulesProcessing(0)
      ->setIsAdvanced(1)
      ->setProductIds('')
      ->setSortOrder(0)
      ->setSimpleAction('by_percent')
      ->setDiscountAmount(100)
      ->setSimpleFreeShipping('0')
      ->setApplyToShipping('0')
      ->setIsRss(0)
      ->setStoreLabels(array('Membership'))
      ->setWebsiteIds(array(1));

    $item_found = Mage::getModel('salesrule/rule_condition_product_found')
      ->setType('salesrule/rule_condition_product_found')
      ->setValue(1)
      ->setAggregator('all');

    $rule->getConditions()->addCondition($item_found);

    $conditionSKU = Mage::getModel('salesrule/rule_condition_product')
      ->setType('salesrule/rule_condition_product')
      ->setAttribute('sku')
      ->setOperator('==')
      ->setValue('MEMBERSHIP');

    $item_found->addCondition($conditionSKU);
    
    $actions = Mage::getModel('salesrule/rule_condition_product')
      ->setType('salesrule/rule_condition_product')
      ->setAttribute('sku')
      ->setOperator('!=')
      ->setValue('MEMBERSHIP');

    $rule->getActions()->addCondition($actions);
    $rule->save();
	
	$installer->endSetup();
?>