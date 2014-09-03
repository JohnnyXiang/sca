<?php
class Dan_SCA_Block_Adminhtml_Gameunit_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {
    protected function _construct(){
        $this->_blockGroup = 'dan_sca_adminhtml';
        $this->_controller = 'gameunit';

        /**
         * The $_mode property tells Magento which folder to use
         * to locate the related form blocks to be displayed in
         * this form container. In our example, this corresponds
         * to SportsmansCommonApp/Block/Adminhtml/Gameunit/Edit/.
         */
        $this->_mode = 'edit';

        $newOrEdit = $this->getRequest()->getParam('id') ? $this->__('Edit') : $this->__('New');
        $this->_headerText =  $newOrEdit . ' ' . $this->__('Gameunit');
    }
}
?>