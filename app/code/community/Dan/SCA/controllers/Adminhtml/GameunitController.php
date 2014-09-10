<?php
class Dan_SCA_Adminhtml_GameunitController extends Mage_Adminhtml_Controller_Action {
    /**
     * Instantiate our grid container block and add to the page content.
     * When accessing this admin index page, we will see a grid of all
     * gameunits currently registered in our Magento instance, along with
     * a button to add a new one if we wish.
     */
    public function indexAction(){
        // instantiate the grid container
        $gameunitBlock = $this->getLayout()->createBlock('dan_sca_adminhtml/gameunit');

        // Add the grid container as the only item on this page
        $this->loadLayout()
            ->_addContent($gameunitBlock)
            ->renderLayout();
    }

    /* This action handles both viewing and editing existing gameunits. */
    public function editAction(){
        $gameunit = Mage::getModel('dan_sca/gameunit');
		$state = Mage::getModel('dan_sca/state');
		
		// load state from url parameter and make it available to blocks
		if($stateId = $this->getRequest()->getParam('state_id', false)){
			$state->load($stateId);
	        Mage::register('current_state', $state);
		};
        
		if($gameunitId = $this->getRequest()->getParam('id', false)) {
			
            $gameunit->load($gameunitId);
			
            if (!$gameunit->getId()){
				_getSession()->addError($this->__('This gameunit no longer exists.'));
                return $this->_redirect('dan_sca_admin/gameunit/index');
            }
        }

        // process $_POST data if the form was submitted
        if ($postData = $this->getRequest()->getPost('gameunitData')) {
            try {
                $gameunit->addData($postData);
                $gameunit->save();
                $this->_getSession()->addSuccess($this->__('The gameunit has been saved.'));

                // redirect to remove $_POST data from the request
                return $this->_redirect('dan_sca_admin/state/edit', array('id' => $gameunit->getParentId()));
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
            }

            /**
             * If we get to here, then something went wrong. Continue to
             * render the page as before, the difference this time being
             * that the submitted $_POST data is available.
             */
        }

        // Make the current gameunit object available to blocks.
        Mage::register('current_gameunit', $gameunit);

        // Instantiate the form container.
        $gameunitEditBlock = $this->getLayout()->createBlock('dan_sca_adminhtml/gameunit_edit');

        // Add the form container as the only item on this page.
        $this->loadLayout()->_addContent($gameunitEditBlock)->renderLayout();
    }

    public function deleteAction(){
        $gameunit = Mage::getModel('dan_sca/gameunit');
		$state = Mage::getModel('dan_sca/state');

        if ($gameunitId = $this->getRequest()->getParam('id', false)){
			$gameunit->load($gameunitId);
			$state->load($gameunit->getParentId());
		};

        if (!$gameunit->getId()){
			_getSession()->addError($this->__('This gameunit no longer exists.'));
            return $this->_redirect('dan_sca_admin/gameunit/index');
		};
		
        try {
            $gameunit->delete();
            $this->_getSession()->addSuccess($this->__('The gameunit has been deleted.'));
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($e->getMessage());
        }

        return $this->_redirect('dan_sca_admin/state/edit', array('id' => $state->getId()));
    }

    protected function _isAllowed(){
        /**
         * we include this switch to demonstrate that you can add action
         * level restrictions in your ACL rules. The isAllowed() method will
         * use the ACL rule we have configured in our adminhtml.xml file:
         * - acl
         * - - resources
         * - - - admin
         * - - - - children
         * - - - - - dan_sca
         * - - - - - - children
         * - - - - - - - gameunit
         *
         * eg. you could add more rules inside gameunit for edit and delete.
         */
        $actionName = $this->getRequest()->getActionName();
        switch ($actionName) {
            case 'index':
            case 'edit':
            case 'delete':
                // intentionally no break
            default:
                $adminSession = Mage::getSingleton('admin/session');
                $isAllowed = $adminSession
                    ->isAllowed('dan_sca/gameunit');
                break;
        }

        return $isAllowed;
    }
}
?>