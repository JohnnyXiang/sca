<?php
class Dan_SCA_Block_Adminhtml_State_Edit_Tab_Details extends Mage_Adminhtml_Block_Widget_Form {
    public function __construct(){
        parent::__construct();
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
                    'onclick'=> 'gameunitDetails.addNewDetail()'
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
                    'onclick'=> 'gameunitDetails.cancelAdd(this)',
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
    public function initForm($gameunitId){
		
		/* @var $gameunit Dan_SCA_Model_Gameunit */
		$gameunit = Mage::getModel('dan_sca/gameunit')->load($gameunitId);

        $form = new Varien_Data_Form();
        $fieldset = $form->addFieldset('address_fieldset', array(
            'legend'    => Mage::helper('dan_sca')->__("Edit Gameunit Detail"))
        );

        $detailModel = Mage::getModel('dan_sca/gameunit_detail');
		
		/*
        $addressForm = Mage::getModel('customer/form');
        $addressForm->setFormCode('adminhtml_customer_address')
            ->setEntity($addressModel)
            ->initDefaultValues();

        $attributes = $addressForm->getAttributes();
        foreach ($attributes as $attribute) {
            $attribute->setFrontendLabel(Mage::helper('dan_sca')->__($attribute->getFrontend()->getLabel()));
            $attribute->unsIsVisible();
        }
        $this->_setFieldset($attributes, $fieldset);
		*/
		
        $detailCollection = $gameunit->getCurrentDetails();

        $this->assign('gameunit', $gameunit);
        $this->assign('detailCollection', $detailCollection);
        // $form->setValues($addressModel->getData());
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
	
    public function addValuesToNamePrefixElement($values)
    {
        if ($this->getForm() && $this->getForm()->getElement('prefix')) {
            $this->getForm()->getElement('prefix')->addElementValues($values);
        }
        return $this;
    }

    /**
     * Add specified values to name suffix element values
     *
     * @param string|int|array $values
     * @return Mage_Adminhtml_Block_Customer_Edit_Tab_Addresses
     */
    public function addValuesToNameSuffixElement($values)
    {
        if ($this->getForm() && $this->getForm()->getElement('suffix')) {
            $this->getForm()->getElement('suffix')->addElementValues($values);
        }
        return $this;
    }
}
