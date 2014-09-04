<?php
class Dan_SCA_Block_Gameunit_View extends Mage_Core_Block_Template {
    
	public function getCurrentGameunit(){
        return Mage::registry('current_gameunit');
    }
	
	public function getGameunitDetails(){
	    $collection = Mage::getResourceModel('dan_sca/gameunit_detail_collection')
			->addFieldToFilter('parent_id', $this->getCurrentGameunit()->getEntityId());
		
		return $collection;
	}
}
?>