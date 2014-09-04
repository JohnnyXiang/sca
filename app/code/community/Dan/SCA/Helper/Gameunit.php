<?php
class Dan_SCA_Helper_Gameunit extends Mage_Core_Helper_Abstract {
    
	public function getGameunitUrl(Dan_SCA_Model_Gameunit $gameunit){
        if (!$gameunit instanceof Dan_SCA_Model_Gameunit) {
            return '#';
        }
        
        return $this->_getUrl(
            'dan_sca/gameunit/view', 
            array(
                'gu' => $gameunit->getUrlKey(),
            )
        );
    }
}
?>