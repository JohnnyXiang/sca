<?php
	class Dan_SCA_Helper_Data extends Mage_Core_Helper_Abstract{
	    
		// not working ...
		public function getState(){
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