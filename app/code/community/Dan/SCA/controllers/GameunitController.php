<?php
class Dan_SCA_GameunitController extends Mage_Core_Controller_Front_Action {
    public function indexAction(){
        $this->loadLayout()->renderLayout();
    }
	
    public function viewAction(){
        $gameunit = Mage::getModel('dan_sca/gameunit');
		$state = Mage::getModel('dan_sca/state');
        
        $urlKey = $this->getRequest()->getParam('gu', '');
        if (strlen($urlKey) > 0) {
			$gameunit->load($urlKey, 'url_key');
        } else {
            $id = (int)$this->getRequest()->getParam('id', 0);
            $gameunit->load($id);
        }

        if ($gameunit->getId() < 1) {
            $this->_redirect('*/*/index');
        } else {
        	$state->load($gameunit->getParentId(), 'entity_id');
        }
        
        Mage::register('current_gameunit', $gameunit);
        Mage::register('current_state', $state);
		
        $this->loadLayout()->renderLayout();
    }
}