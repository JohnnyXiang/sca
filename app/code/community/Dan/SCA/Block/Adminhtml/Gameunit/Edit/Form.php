<?php
class Dan_SCA_Block_Adminhtml_Gameunit_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm(){
        // Instantiate a new form to display our state for editing.
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('dan_sca_admin/gameunit/edit', array(
				'_current' => true,
				'continue' => 0,
			)),
            'method' => 'post',
		));
        $form->setUseContainer(true);
        $this->setForm($form);

        // Define a new fieldset. We need only one for our simple entity.
        $fieldset = $form->addFieldset(
            'general', array(
				'legend' => $this->__('Gameunit Details')
			)
        );

        $stateSingleton = Mage::getSingleton('dan_sca/gameunit');

        // Add the fields that we want to be editable.
        $this->_addFieldsToFieldset($fieldset, array(
            'name' => array(
                'label' => $this->__('Name'),
                'input' => 'text',
                'required' => true,
            ),
            'description' => array(
                'label' => $this->__('Description'),
                'input' => 'textarea',
                'required' => true,
            ),
            'url_key' => array(
                'label' => $this->__('URL Key'),
                'input' => 'text',
                'required' => true,
			),
            'website_id' => array(
                'label' => $this->__('HTML Identifier'),
                'input' => 'text',
                'required' => false,
            ),
            'size' => array(
                'label' => $this->__('Land Area (sq-mi)'),
                'input' => 'text',
                'required' => false,
            ),
            'parent_id' => array(
                'label' => $this->__('Parent ID'),
                'input' => 'hidden',
                'required' => true,
			),
            'members_only' => array(
                'label' => $this->__('Members-only?'),
				'input' => 'select',
				'options' => array (
						1 => 'Yes',
						0 => 'No'
        			),
                'required' => true,
			)
        ));

        return $this;
    }

    protected function _addFieldsToFieldset(Varien_Data_Form_Element_Fieldset $fieldset, $fields){
        $requestData = new Varien_Object($this->getRequest()->getPost('gameunitData'));

		// set the parent_id from current_state for new objects ==> will return false if we are editing
		$parent_id = $this->_getHelper()->getState()->getId();

        foreach ($fields as $name => $_data) {
            if ($requestValue = $requestData->getData($name)) {
                $_data['value'] = $requestValue;
            }

            // Wrap all fields with gameunitData group.
            $_data['name'] = "gameunitData[$name]";

            // Generally, label and title are always the same.
            $_data['title'] = $_data['label'];

            // If no new value exists, use the existing gameunit data.
            if(!array_key_exists('value', $_data)) {
                $_data['value'] = $this->_getGameunit()->getData($name);
            }
			
			if($name == 'parent_id' && $parent_id){
				$_data['value'] = $parent_id;
			};

            // Finally, call vanilla functionality to add field.
            $fieldset->addField($name, $_data['input'], $_data);
        };
		
        return $this;
    }

    /**
     * Retrieve the existing gameunit for pre-populating the form fields.
     * For a new gameunit entry, this will return an empty gameunit object.
     */
    protected function _getGameunit(){
        if (!$this->hasData('gameunit')) {
            // This will have been set in the controller.
            $gameunit = Mage::registry('current_gameunit');

            // Just in case the controller does not register the gameunit.
            if (!$gameunit instanceof Dan_SCA_Model_Gameunit) {
                $gameunit = Mage::getModel('dan_sca/gameunit');
            }

            $this->setData('gameunit', $gameunit);
        }

        return $this->getData('gameunit');
    }
	
    protected function _getHelper(){
        return Mage::helper('dan_sca');
    }
}
?>