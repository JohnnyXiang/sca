<?php
class Dan_SCA_Adminhtml_StateController extends Mage_Adminhtml_Controller_Action {
    /**
     * Instantiate our grid container block and add to the page content.
     * When accessing this admin index page, we will see a grid of all
     * states currently registered in our Magento instance, along with
     * a button to add a new one if we wish.
     */
    public function indexAction(){
        $this->loadLayout();
		$this->_addContent($this->getLayout()->createBlock('dan_sca_adminhtml/state'));
		$this->renderLayout();
    }

    /* This action handles both viewing and editing existing states. */
    public function editAction(){
        /**
         * Retrieve existing state data if an ID was specified.
         * If not, we will have an empty state entity ready to be populated.
         */
        $state = Mage::getModel('dan_sca/state');
        
		if ($stateId = $this->getRequest()->getParam('id', false)) {
			
            $state->load($stateId);
			
            if (!$state->getId()){
				_getSession()->addError($this->__('This state no longer exists.'));
                return $this->_redirect('dan_sca_admin/state/index');
            }
        }

        // process $_POST data if the form was submitted
        if ($postData = $this->getRequest()->getPost('stateData')) {
            try {
                $state->addData($postData);
                $state->save();
                $this->_getSession()->addSuccess($this->__('The state has been saved.'));

                // redirect to remove $_POST data from the request
                return $this->_redirect('dan_sca_admin/state/edit', array(
					'id' => $state->getId()
				));
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

        // Make the current state object available to blocks.
        Mage::register('current_state', $state);

        $this->loadLayout();
		$this->_addContent($this->getLayout()->createBlock('dan_sca_adminhtml/state_edit'))
			    ->_addLeft($this->getLayout()->createBlock('dan_sca_adminhtml/state_edit_tabs'));
		$this->renderLayout();
    }

    public function deleteAction(){
        $state = Mage::getModel('dan_sca/state');

        if ($stateId = $this->getRequest()->getParam('id', false)){
			$state->load($stateId);
		};

        if ($state->getId()){
			_getSession()->addError($this->__('This state no longer exists.'));
            return $this->_redirect('dan_sca_admin/state/index');
		};
		
        try {
            $state->delete();
            $this->_getSession()->addSuccess($this->__('The state has been deleted.'));
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($e->getMessage());
        }

        return $this->_redirect('dan_sca_admin/state/index');
    }

    /**
     * Thanks to Ben for pointing out this method was missing. Without
     * this method the ACL rules configured in adminhtml.xml are ignored.
     */
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
         * - - - - - - - state
         *
         * eg. you could add more rules inside state for edit and delete.
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
                    ->isAllowed('dan_sca/state');
                break;
        }

        return $isAllowed;
    }
}
?>