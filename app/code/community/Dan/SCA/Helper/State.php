<?php
class Dan_SCA_Helper_State extends Mage_Core_Helper_Abstract {
    
	public function getStateUrl(Dan_SCA_Model_State $state){
        if (!$state instanceof Dan_SCA_Model_State) {
            return '#';
        }
        
        return $this->_getUrl(
            'dan_sca/index/view', 
            array(
                'url' => $state->getUrlKey(),
            )
        );
    }
	
	// function to return gameunits (and their urls)
	public function getStateGameunits(Dan_SCA_Model_State $state){
        if (!$state instanceof Dan_SCA_Model_State) {
            return '#';
        }
		
		return true;
	}
}
?>