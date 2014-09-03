<?php
class Dan_SCA_IndexController extends Mage_Core_Controller_Front_Action {
    public function indexAction(){
        $this->loadLayout()->renderLayout();
    }
    
    public function viewAction(){
        $state = Mage::getModel('dan_sca/state');
        
        $urlKey = $this->getRequest()->getParam('url', '');
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
	
    public function gameunitAction(){
        $gameunit = Mage::getModel('dan_sca/gameunit');
        
        $urlKey = $this->getRequest()->getParam('url', '');
        if (strlen($urlKey) > 0) {
			$gameunit->load($urlKey, 'url_key');
        } else {
            $id = (int)$this->getRequest()->getParam('id', 0);
            $gameunit->load($id);
        }
        
        if ($gameunit->getId() < 1) {
            $this->_redirect('*/*/index');
        }
        
        Mage::register('current_gameunit', $gameunit);
        
        $this->loadLayout()->renderLayout();
    }
}