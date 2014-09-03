<?php
class Dan_SCA_Block_Adminhtml_State_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {
	
	protected function _prepareForm(){

        $form = new Varien_Data_Form();
        $this->setForm($form);

        // Define a new fieldset. We need only one for our simple entity.
        $fieldset = $form->addFieldset('dan_sca_state', array('legend' => $this->__('State Details')));

        // Add the fields that we want to be editable.
        $this->_addFieldsToFieldset($fieldset, array(
            'name' => array(
                'label' => $this->__('Name'),
                'input' => 'text',
                'required' => true,
            ),
            'abbreviation' => array(
                'label' => $this->__('Abbreviation'),
                'input' => 'text',
                'required' => true,
            ),
   //         'requires_safety_card' => array(
   //             'label' => $this->__('Requires Safety Card?'),
   //				'input' => 'boolean',
   //				'options' => array (
   //						1 => 'Yes',
   //						0 => 'No',
   //     			),
   //             'required' => true,
   //         ),
            'convenience_fee' => array(
                'label' => $this->__('Convenience Fee'),
                'input' => 'text',
                'required' => true,
            ),
		
            'url_key' => array(
                'label' => $this->__('URL Key'),
                'input' => 'text',
                'required' => true,
            ),
            'description' => array(
                'label' => $this->__('Description'),
                'input' => 'textarea',
                'required' => true,
            ),
        ));
		
        return parent::_prepareForm();
    }

    /**
     * This method makes life a little easier for us by pre-populating
     * fields with $_POST data where applicable and wrapping our post data
     * in 'stateData' so that we can easily separate all relevant information
     * in the controller. You could of course omit this method entirely
     * and call the $fieldset->addField() method directly.
     */
    protected function _addFieldsToFieldset(Varien_Data_Form_Element_Fieldset $fieldset, $fields){
        $requestData = new Varien_Object($this->getRequest()->getPost('stateData'));

        foreach($fields as $name => $_data) {
            if ($requestValue = $requestData->getData($name)) {
                $_data['value'] = $requestValue;
            }

            // Wrap all fields with stateData group.
            $_data['name'] = "stateData[$name]";

            // Generally, label and title are always the same.
            $_data['title'] = $_data['label'];

            // If no new value exists, use the existing state data.
            if (!array_key_exists('value', $_data)) {
                $_data['value'] = $this->_getState()->getData($name);
            }

            // Finally, call vanilla functionality to add field.
            $fieldset->addField($name, $_data['input'], $_data);
        }

        return $this;
    }

	// helper function to get data (from the registry) of 'current_state'
    protected function _getState(){
        if (!$this->hasData('state')) {
            // This will have been set in the controller.
            $state = Mage::registry('current_state');

            // Just in case the controller does not register the state.
            if (!$state instanceof Dan_SCA_Model_State) {
                $state = Mage::getModel('dan_sca/state');
            }

            $this->setData('state', $state);
        }

        return $this->getData('state');
    }
}
?>