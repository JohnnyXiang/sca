<?php
	class Dan_SCA_Helper_Data extends Mage_Core_Helper_Abstract{
	    
		/*
		Mage::log(
		    (string)'update residence action',
		    null,
		    'my.log',
		    true
		);
		*/
		
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
		    $collection = Mage::getModel('dan_sca/gameunit_detail')->getCollection();

			// join with gameunits, ensuring only the specified state_id is used
			$collection->getSelect()
				->join(
					array('gu' => 'dan_sca_state_gameunit'), 
					'gu.entity_id = main_table.parent_id', 
					array('gu.name', 'gu.entity_id AS gameunit_id')
				)
				->where('gu.parent_id = '.$state_id)
				->where('main_table.animal_id = '.$animal_id)
				->order(array('gameunit_name ASC', 'year DESC'))
				->columns(new Zend_Db_Expr("gu.name AS gameunit_name"));
			
	        return $collection;
	    }
		
	    public function getSecretKey($controller = null, $action = null){
	        $salt = Mage::getSingleton('core/session')->getFormKey();
		
			$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
			$string = '';
			 for ($i = 0; $i < 10; $i++) {
			      $string .= $characters[rand(0, strlen($characters) - 1)];
			 }

	        $secret = $string . $salt;
	        return (string)Mage::helper('core')->getHash($secret);
	    }
		
		// helper to get encryption keys from API endpoint
		public function getNewEncryptionKey($customer_id, $encrypted_data = null){
			$payload = array('entity_id' => $customer_id, 'data' => $encrypted_data);
			$jsonData = Mage::helper('core')->jsonEncode($payload);
			$httpClient = new Zend_Http_Client('http://our.server.address:3713');
			return $httpClient->setRawData($jsonData, 'json')->request('POST');
		}
		
		public function performKeyRoll($customer){
			// lines to support key roll of encrypted data
			$secured_data = $customer->getSecuredData();
			$encryption_keys = $this->getNewEncryptionKey($customer->getId(), $secured_data);
			$iv = substr($secured_data, 0, 16);
			$unsecured_data = openssl_decrypt(substr($secured_data, 16), "AES-256-CBC", $encryption_keys[0], 0, $iv);
			$iv = mcrypt_create_iv(16, MCRYPT_RAND);
			$encrypted = openssl_encrypt($unsecured_data, "AES-256-CBC", $encryption_keys[1], 0, $iv);
			$secured_data = $iv.$encrypted;
			$customer->setSecuredData($secured_data);
			$customer->save();
		}
	}
?>