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
		$id = (int)$this->getRequest()->getParam('state_id');
		
		if($id){
			Mage::getSingleton('customer/session')->setStateOfResidence($id);
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
		
		$postData = $this->getRequest()->getParams();
		
		// retrieve customer
		$order = Mage::getSingleton('customer/session')->getLastOrder();
		$customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
		
		if($customer->getUpdateToken() == $postData['key']){
			
			// remove the key value so that we don't attempt to save it as a customer attribute
			unset($postData['key']);

			foreach($postData as $_attr => $_val){
				$customer->setData($_attr, $_val);
			}
		
	        try {
	            $customer->save();
				$jsonData = json_encode(true);
				$this->getResponse()->setHeader('Content-type', 'application/json');
				$this->getResponse()->setBody($jsonData);
	        } catch (Exception $e) {
	            Mage::logException($e);
	            $jsonData = json_encode($e->getMessage());
				$this->getResponse()->setHeader('HTTP/1.0', '501', true);
				$this->getResponse()->setBody($jsonData);
	        }
		}
		else{
			Mage::logException('malicious attempt detected');
			$jsonData = json_encode(array('data' => 'malicious attempt detected'));
			$this->getResponse()->setHeader('HTTP/1.0', '502', true);
			$this->getResponse()->setBody($jsonData);
		}
    }
}