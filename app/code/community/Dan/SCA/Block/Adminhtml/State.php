<?php
class Dan_SCA_Block_Adminhtml_State
    extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    protected function _construct()
    {
        parent::_construct();

        /**
         * The $_blockGroup property tells Magento which alias to use to
         * locate the blocks to be displayed in this grid container.
         * In our example, this corresponds to SportsmansCommonApp/Block/Adminhtml.
         */
        $this->_blockGroup = 'dan_sca_adminhtml';

        /**
         * $_controller is a slightly confusing name for this property.
         * This value, in fact, refers to the folder containing our
         * Grid.php and Edit.php - in our example,
         * SportsmansCommonApp/Block/Adminhtml/State. So, we'll use 'state'.
         */
        $this->_controller = 'state';

        /* The title of the page in the admin panel. */
        $this->_headerText = Mage::helper('dan_sca')->__('State Directory');
    }

    public function getCreateUrl()
    {
        /**
         * When the "Add" button is clicked, this is where the user should
         * be redirected to - in our example, the method editAction of
         * StateController.php in SportsmansCommonApp module.
         */
        return $this->getUrl('dan_sca_admin/state/edit');
    }
}
?>