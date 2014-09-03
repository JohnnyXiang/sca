<?php
class Dan_SCA_Model_Gameunit extends Mage_Core_Model_Abstract {
 
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
        $this->_init('dan_sca/gameunit');
    }

    protected function _beforeSave(){
        parent::_beforeSave();

        $this->_updateTimestamps();
        $this->_prepareUrlKey();

        return $this;
    }

    protected function _updateTimestamps(){
        $timestamp = now();

        $this->setUpdatedAt($timestamp);

        if ($this->isObjectNew()) {
            $this->setCreatedAt($timestamp);
        }

        return $this;
    }

    protected function _prepareUrlKey(){
        /* Might consider ensuring that the URL Key entered is unique and contains only alphanumeric characters. */

        return $this;
    }
}

?>