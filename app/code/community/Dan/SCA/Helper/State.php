<?php
class Dan_SCA_Helper_State extends Mage_Core_Helper_Abstract {
    
	public function getStateUrl(Dan_SCA_Model_State $state){
        if (!$state instanceof Dan_SCA_Model_State) {
            return '#';
        }
        
        return $this->_getUrl(
            'dan_sca/index/view', 
            array(
                's' => $state->getUrlKey(),
            )
        );
    }
	
	public function getCurrentState(){
        // This will have been set in the controller.
        $state = Mage::registry('current_state');

        // Just in case the controller does not register the state.
        if (!$state instanceof Dan_SCA_Model_State) {
            $state = Mage::getModel('dan_sca/state');
        }

        return $state;
    }
}
?>