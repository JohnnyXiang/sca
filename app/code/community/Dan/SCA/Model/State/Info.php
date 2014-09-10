<?php
class Dan_SCA_Model_State_Info extends Mage_Core_Model_Abstract {
 
    protected function _construct(){
        $this->_init('dan_sca/state_info');
    }

    protected function _beforeSave(){
        parent::_beforeSave();

        $this->_updateTimestamps();

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
}

?>