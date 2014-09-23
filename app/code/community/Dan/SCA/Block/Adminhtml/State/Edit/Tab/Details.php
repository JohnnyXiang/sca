<?php
class Dan_SCA_Block_Adminhtml_State_Edit_Tab_Details extends Mage_Adminhtml_Block_Widget_Form {
    public function __construct(){
        parent::__construct();
        $this->initForm();
        $this->setTemplate('dan_sca/state/details.phtml');
    }

    protected function _prepareLayout(){
        $this->setChild('delete_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'  => Mage::helper('dan_sca')->__('Delete Detail'),
                    'name'   => 'delete_detail',
                    'element_name' => 'delete_detail',
                    'class'  => 'delete'
                ))
        );
        $this->setChild('add_detail_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'  => Mage::helper('dan_sca')->__('Add New Detail'),
                    'id'     => 'add_detail_button',
                    'name'   => 'add_detail_button',
                    'element_name' => 'add_detail_button',
                    'class'  => 'add',
                    'onclick'=> 'gameunitDetails'.$this->getGameunit()->getId().'.addNewDetail()'
                ))
        );
        $this->setChild('cancel_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'  => Mage::helper('dan_sca')->__('Cancel'),
                    'id'     => 'cancel_add_detail'.$this->getTemplatePrefix(),
                    'name'   => 'cancel_add_detail',
                    'element_name' => 'cancel_add_detail',
                    'class'  => 'cancel delete-detail',
                    'onclick'=> 'gameunitDetails'.$this->getGameunit()->getId().'.cancelAdd(this)',
                ))
        );
        return parent::_prepareLayout();
    }

    public function getDeleteButtonHtml(){
        return $this->getChildHtml('delete_button');
    }

    /**
     * Initialize form object
     *
     * @return Dan_SCA_Adminhtml_Block_State_Edit_Tab_Details
     */
    public function initForm($gameunitId=null){
		
		/* @var $gameunit Dan_SCA_Model_Gameunit */
    	
    	if($gameunitId){
			$gameunit = Mage::getModel('dan_sca/gameunit')->load($gameunitId);
    	}elseif(Mage::registry('current_gameunit')){
    		$gameunit = Mage::registry('current_gameunit');
    	}
    	
    	if(!$gameunit){
    		return false;
    	}
    	
    	$this->setGameunit($gameunit);
    	
		$detailModel = Mage::getModel('dan_sca/gameunit_detail');
		$detailModel->setParentId($gameunit->getId());
		
        $form = new Varien_Data_Form();
        $fieldset = $form->addFieldset('gameunit_detail_fieldset', array(
            'legend'    => Mage::helper('dan_sca')->__("Edit Gameunit Detail"))
        );

		$fieldset->addField('parent_id', 'hidden', array(
				'name'	=> 'parent_id'
		));


		$fieldset->addField('year', 'text', array(
				'name'	=> 'year',
				'title'	=> 'Year',
				'label' => 'Year'
			));
		
		// retrieve the animal name / id options from the animal source model
	    $attr = Mage::getModel('eav/config')->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'animal_id');
	    $opts = $attr->setStoreId(0)->getSource()->getAllOptions();
		
		// format the option values as necessary to be used in addField()
		$animal_opts = array();
		foreach($opts as $_k => $_v){
			// skip the first one (it's empty)
			if($_k)
				$animal_opts[$_v['value']] = $_v['label'];
		};
		
		$fieldset->addField('animal_id', 'select', array(
				'name'	=> 'animal_id',
				'title'	=> 'Animal',
				'label' => 'Animal',
				'options'	=> $animal_opts,
				'required'	=> true
			));
		
		$fieldset->addField('weapon', 'select', array(
				'name'	=> 'weapon',
				'title'	=> 'Weapon',
				'label' => 'Weapon',
				'options'	=> array(
					'Archery'		=> 'Archery',
					'Rifle'			=> 'Rifle',
					'Muzzle Loader'	=> 'Muzzle Loader'
				),
				'required'	=> true
			));
			
		$fieldset->addField('available_tags', 'text', array(
				'name'	=> 'available_tags',
				'title'	=> '# Tags Available',
				'label' => '# Tags Available'
			));
				
		$fieldset->addField('tag_applications', 'text', array(
				'name'	=> 'tag_applications',
				'title'	=> '# Applied',
				'label' => '# Applied'
			));
			
		$fieldset->addField('num_tagged_out', 'text', array(
				'name'	=> 'num_tagged_out',
				'title'	=> '# "Tagged out"',
				'label' => '# "Tagged out"'
			));

        $detailCollection = $gameunit->getCurrentDetails();

        $this->assign('gameunit', $gameunit);
        $this->assign('detailCollection', $detailCollection);
        $form->setValues($detailModel->getData());
        $this->setForm($form);
        return $this;
    }

    public function getCancelButtonHtml(){
        return $this->getChildHtml('cancel_button');
    }

    public function getAddNewButtonHtml(){
        return $this->getChildHtml('add_detail_button');
    }

    public function getTemplatePrefix(){
        return '_template_';
    }

    /**
     * Return predefined additional element types
     *
     * @return array
     */
    protected function _getAdditionalElementTypes(){
        return array(
            'file'      => Mage::getConfig()->getBlockClassName('adminhtml/customer_form_element_file'),
            'image'     => Mage::getConfig()->getBlockClassName('adminhtml/customer_form_element_image'),
            'boolean'   => Mage::getConfig()->getBlockClassName('adminhtml/customer_form_element_boolean'),
        );
    }
}
