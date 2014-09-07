<?php
	$installer = $this;
	$installer->startSetup();
		
	/* create state table (non-EAV) */
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
	
	
	
	/* create animal table (non-EAV) */
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



	
	/* create gameunit table (non-EAV) */
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
	
	
	
	/* create [gameunit] detail table (non-EAV) */
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
	$table->setOption('type', 'InnoDB');
	$table->setOption('charset', 'utf8');
	$installer->getConnection()->createTable($table);
	
	
	/* create state info table (non-EAV) */
	$table = new Varien_Db_Ddl_Table();
	$table->setName($installer->getTable('dan_sca/required_info'));
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
	    'attribute_code',
	    Varien_Db_Ddl_Table::TYPE_VARCHAR,
	    255,
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
	
	
	
	// add a new product attribute to associate a brand to each product
	$this->removeAttribute(Mage_Catalog_Model_Product::ENTITY,'state_id');
	$this->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'state_id', array(
	    'group'         => 'General',
	    'label'         => 'State',
	    'input'         => 'select',
	    'source'        => 'dan_sca/source_state'
	));
	
	// add a new product attribute to associate a brand to each product
	$this->removeAttribute(Mage_Catalog_Model_Product::ENTITY,'animal_id');
	$this->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'animal_id', array(
	    'group'         => 'General',
	    'label'         => 'Animal',
	    'input'         => 'multiselect',
		'type'			=> 'varchar',
		'backend'		=> 'eav/entity_attribute_backend_array',
	    'source'        => 'dan_sca/source_animal',
		'searchable'	=> true
	));

	// create new attribute sets
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

	$installer->endSetup();
?>