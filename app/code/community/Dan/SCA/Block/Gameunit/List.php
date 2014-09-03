<?php
class Dan_SCA_Block_Gameunit_List extends Mage_Core_Block_Template {
    public function getGameunitCollection(){
        return Mage::getModel('dan_sca/gameunit')->getCollection()
            ->setOrder('name', 'ASC');
    }
}
?>