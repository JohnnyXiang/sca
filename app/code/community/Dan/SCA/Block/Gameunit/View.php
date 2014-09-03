<?php
class Dan_SCA_Block_Gameunit_View extends Mage_Core_Block_Template {
    public function getCurrentGameunit(){
        return Mage::registry('current_gameunit');
    }
}
?>