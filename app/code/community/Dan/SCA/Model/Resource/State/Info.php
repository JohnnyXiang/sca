<?php
class Dan_SCA_Model_Resource_State_Info extends Mage_Core_Model_Resource_Db_Abstract {
    protected function _construct(){
        $this->_init('dan_sca/state_info', 'entity_id');
    }
}
?>