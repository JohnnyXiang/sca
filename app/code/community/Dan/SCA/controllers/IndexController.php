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
    public function updateAction(){
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
}