<?php
class Dan_SCA_Block_State_View extends Mage_Core_Block_Template {
    
	public function getCurrentState(){
        return Mage::registry('current_state');
    }
	
	public function getStateGameunits(){
	    $collection = Mage::getResourceModel('dan_sca/gameunit_collection')
			->addFieldToFilter('parent_id', $this->getCurrentState()->getEntityId());

		return $collection;
	}
}
?>