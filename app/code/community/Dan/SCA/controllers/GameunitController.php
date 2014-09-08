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
        };
		
		/* handler to prevent showing protected gameunits (and their statistics) */
		$deny = false;
		if($gameunit->getMembersOnly()){
			
			// get the groupId for 'Members'
			$targetGroup = Mage::getModel('customer/group');
			$targetGroup->load('Members', 'customer_group_code');
			$memberGroupId = $targetGroup->getId();
			
			// get the current customer's groupId
			$customersGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
			
			// set deny flag if the Id's are not the same
			if($customersGroupId != $memberGroupId)
				$deny = true;
		};
		
		if(!$deny){
	        Mage::register('current_gameunit', $gameunit);
	        Mage::register('current_state', $state);
	        $this->loadLayout()->renderLayout();
		}
		else{
			// get url of the membership product and forward the user there
			$membershipUrl =  Mage::getModel('catalog/product')->loadByAttribute('sku', 'MEMBERSHIP')->getProductUrl();
			$baseUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);
			$_url = explode($baseUrl, $membershipUrl); 
			$this->_redirect($_url[1]);
		};

		/*
			dan06
			QatYUJkMC#1
		*/
    }
}