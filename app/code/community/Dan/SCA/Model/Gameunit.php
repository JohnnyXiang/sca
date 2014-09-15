<?php
class Dan_SCA_Model_Gameunit extends Mage_Core_Model_Abstract {
 
    public function getCurrentDetails(){
	    $collection = Mage::getModel('dan_sca/gameunit_detail')->getCollection();
		$collection->getSelect()
			->where('main_table.parent_id = '.$this->getId())
			->where('main_table.year = "current"');		// *** lock to current year for speed / admin need
		
        return $collection;
    }
 
    protected function _construct(){
        $this->_init('dan_sca/gameunit');
    }

    protected function _beforeSave(){
        parent::_beforeSave();

        $this->_updateTimestamps();
        $this->_prepareUrlKey();

        return $this;
    }

    protected function _updateTimestamps(){
        $timestamp = now();

        $this->setUpdatedAt($timestamp);

        if ($this->isObjectNew()) {
            $this->setCreatedAt($timestamp);
        }

        return $this;
    }

    protected function _prepareUrlKey(){
        // might consider a regex to ensure no invalid characters are used
        return $this;
    }
}

?>