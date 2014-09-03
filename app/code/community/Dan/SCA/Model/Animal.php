<?php
class Dan_SCA_Model_Animal extends Mage_Core_Model_Abstract {
 
    protected function _construct(){
        /**
         * This tells Magento where the related resource model can be found.
         *
         * For a resource model, Magento will use the standard model alias -
         * in this case 'dan_sca' - and look in
         * config.xml for a child node <resourceModel/>. This will be the
         * location that Magento will look for a model when
         * Mage::getResourceModel() is called.
         */
        $this->_init('dan_sca/animal');
    }

    protected function _beforeSave(){
        parent::_beforeSave();
        return $this;
    }
}

?>