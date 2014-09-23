<?php
class Dan_SCA_Block_Adminhtml_State_Info
    extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    protected function _construct()
    {
        parent::_construct();

        $this->_blockGroup = 'dan_sca_adminhtml';
        $this->_controller = 'state';

        // title of the page in the admin panel
        $this->_headerText = Mage::helper('dan_sca')->__('Required Info');
    }

    public function getCreateUrl()
    {
        // when "Add" button is clicked, redirect to edit page (with current_state's id as a parameter)
        return $this->getUrl('dan_sca_admin/state_info/edit', array('state_id' => Mage::helper('dan_sca')->getState()->getId()));
    }
}
?>