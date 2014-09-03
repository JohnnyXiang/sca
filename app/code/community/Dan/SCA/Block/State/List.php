<?php
class Dan_SCA_Block_State_List extends Mage_Core_Block_Template {
    public function getStateCollection(){
        return Mage::getModel('dan_sca/state')->getCollection()
            ->setOrder('name', 'ASC');
    }
}
?>