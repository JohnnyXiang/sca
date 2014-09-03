<?php
class Dan_SCA_Block_Adminhtml_State_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {
	
	protected function _prepareForm() {
		$form = new Varien_Data_Form(array(
	            'id' => 'edit_form',
	            'action' => $this->getUrl('dan_sca_admin/state/edit', array(
					'_current' => true,
					'continue' => 0,
				)),
	            'method' => 'post',
	            'enctype' => 'multipart/form-data'
			)
		);

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
?>