<?php
class Dan_SCA_Model_State extends Mage_Core_Model_Abstract {
 
 	private function blah(){
 		
 	}
 
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
        $this->_init('dan_sca/state');
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
        // might consider a regex to ensure no invalid characters are used
        return $this;
    }
}

?>