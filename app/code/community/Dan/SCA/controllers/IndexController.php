<?php
class Dan_SCA_IndexController extends Mage_Core_Controller_Front_Action {
    
	public function indexAction(){
        $this->loadLayout()->renderLayout();
    }
    
    public function viewAction(){
        $state = Mage::getModel('dan_sca/state');
        
        $urlKey = $this->getRequest()->getParam('s', '');
        if (strlen($urlKey) > 0) {
			$state->load($urlKey, 'url_key');
        } else {
            $id = (int)$this->getRequest()->getParam('id', 0);
            $state->load($id);
        }
        
        if ($state->getId() < 1) {
            $this->_redirect('*/*/index');
        }
        
        Mage::register('current_state', $state);
        
        $this->loadLayout()->renderLayout();
    }
	
	// handler for ajax state of residence prompt / input
    public function updateResidenceAction(){

		if($id = (int)$this->getRequest()->getParam('state_id')){
			
			// we set directly on the customer object so that if the user registers during checkout, the value will be persisted & saved before the session->clear()
			$customerSession = Mage::getSingleton('customer/session');
			$customerSession->getCustomer()->setStateOfResidence($id);
			$customerSession->setStateOfResidence($id);
			
			$jsonData = json_encode(true);
			$this->getResponse()->setHeader('Content-type', 'application/json');
		}
		else{
			$jsonData = json_encode(array('data' => 'null input'));
			$this->getResponse()->setHeader('HTTP/1.0', '501', true);
		};
		$this->getResponse()->setBody($jsonData);
    }
	
	// handler for ajax post-checkout details update
    public function updateDetailsAction(){
		
		$error = true;
		
		if($postData = $this->getRequest()->getParams()){
			if($custId = $postData['id']){
				if($customer = Mage::getModel('customer/customer')->load($custId)){
					if($customer->getUpdateToken() == $postData['key']){

						// remove these value so that we don't attempt to save it as a customer attribute
						unset($postData['key']);
						unset($postData['id']);

						foreach($postData as $_attr => $_val){
							$customer->setData($_attr, $_val);
						};

						try {
							$customer->setData('update_token', Mage::helper('dan_sca')->getSecretKey());
							$customer->save();
							$jsonData = json_encode(true);
							$error = false;
						}
						catch(Exception $e){
							Mage::logException($e);
							$jsonData = json_encode($e->getMessage());
						}
					}
					else{
						Mage::logException('invalid token detected');
						$jsonData = json_encode(array('data' => 'an invalid value was supplied'));
					};
				}
				else{
					Mage::logException('invalid customer provided');
					$jsonData = json_encode(array('data' => 'an invalid value was supplied'));
				};
			}
			else{
				Mage::logException('no id provided');
				$jsonData = json_encode(array('data' => 'an invalid value was supplied'));
			};
		}
		else{
			Mage::logException('update parameters not supplied');
			$jsonData = json_encode(array('data' => 'an invalid value was supplied'));
		};
		
		// return result
		if(!$error){
			$this->getResponse()->setHeader('Content-type', 'application/json');
			$this->getResponse()->setBody($jsonData);
		}
		else{
			$this->getResponse()->setHeader('HTTP/1.0', '501', true);
			$this->getResponse()->setBody($jsonData);
		};
    }
}