<?php
	class Dan_SCA_Helper_Data extends Mage_Core_Helper_Abstract{
	    
		public function getState(){
            // This will have been set in the controller.
            $state = Mage::registry('current_state');

            // Just in case the controller does not register the state.
            if (!$state instanceof Dan_SCA_Model_State) {
                $state = Mage::getModel('dan_sca/state');
            }

	        return $state;
	    }
		
		// function to retrieve the current_product's gameunis (and their details) for a given state/animal combo
		public function getProductGameunitsAndDetails($state_id, $animal_id){
			
			// get all gameunit details, filtering on the specified animal_id
		    $collection = Mage::getModel('dan_sca/gameunit_detail')->getCollection()
				->addFieldToFilter('animal_id', $animal_id);

			// join with gameunits, ensuring only the specified state_id is used
			$collection->getSelect()
				->join(array('gu' => 'dan_sca_state_gameunit'), 'gu.entity_id = main_table.parent_id')
				->where('gu.parent_id = '.$state_id)
				->columns(new Zend_Db_Expr("`gu`.`name` AS gameunit_name"));
			
	        return $collection;
	    }
	}
?>