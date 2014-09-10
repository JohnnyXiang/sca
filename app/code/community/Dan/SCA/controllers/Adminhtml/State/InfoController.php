<?php
class Dan_SCA_Adminhtml_State_InfoController extends Mage_Adminhtml_Controller_Action {
    /**
     * Instantiate our grid container block and add to the page content.
     * When accessing this admin index page, we will see a grid of all
     * gameunits currently registered in our Magento instance, along with
     * a button to add a new one if we wish.
     */
    public function indexAction(){
        // instantiate the grid container
        $stateInfoBlock = $this->getLayout()->createBlock('dan_sca_adminhtml/state_info');

        // Add the grid container as the only item on this page
        $this->loadLayout()
            ->_addContent($stateInfoBlock)
            ->renderLayout();
    }

    /* This action handles both viewing and editing existing pieces of info. */
    public function editAction(){
        $info = Mage::getModel('dan_sca/state_info');
		$state = Mage::getModel('dan_sca/state');
		
		// load state from url parameter and make it available to blocks ... will only execute if Adding
		if($stateId = $this->getRequest()->getParam('state_id', false)){
			$state->load($stateId);
	        Mage::register('current_state', $state);
		};

		if($infoId = $this->getRequest()->getParam('id', false)) {
            $info->load($infoId);
			
            if (!$info->getId()){
				_getSession()->addError($this->__('This piece of info no longer exists.'));
                return $this->_redirect('dan_sca_admin/state_info/index');
            };
        };

        // process $_POST data if the form was submitted
        if ($postData = $this->getRequest()->getPost('state_infoData')) {
            try {

				$state->load($postData['parent_id']);
				$refAttributeCode = $info->getAttributeCode();
				
				// create (or update) the Magento customer/attribute referenced by the state info
				if(!$refAttributeCode){
					// new --> create
					
					// clean the attribute_code and prepend the state's abbreviation
					$postData['attribute_code'] = strtolower($state->getAbbreviation() . '_' . preg_replace('([.<!+-\,-@#$%^&*();\\/|>"?= ])', '_', $postData['attribute_code']));

					// prep the attribute data
					$attrData = array(
					    'label' 			=> $state->getName() . ' ' . $postData['name'],
					    'global' 			=> true,
					    'visible' 			=> false,
					    'required' 			=> (boolean)$postData['is_required'],
					    'user_defined' 		=> true,
					    'visible_on_front' 	=> false,
						'note'				=> $state->getAbbreviation()
					);
					
					// add type details based on user input
					if($postData['input_type'] == 'date'){
						$attrData['input']   = 'date';
						$attrData['type']    = 'datetime';
						$attrData['backend'] = 'eav/entity_attribute_backend_datetime';
					}
					else{
						$attrData['input'] = 'text';
						$attrData['type']  = 'varchar';
					};

					// CREATE a proper Magento customer EAV attribute
					$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
					$entityTypeId = $setup->getEntityTypeId('customer');

					$setup->addAttribute($entityTypeId, $postData['attribute_code'], $attrData);
					$attributeSetId   = $setup->getDefaultAttributeSetId($entityTypeId);
					$attributeGroupId = $setup->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);
					
					$attribute = Mage::getSingleton("eav/config")->getAttribute("customer", $postData['attribute_code']);
					$setup->addAttributeToGroup(
					    $entityTypeId,
					    $attributeSetId,
					    $attributeGroupId,
					    $postData['attribute_code'],
					    '999'
					);

					$used_in_forms=array();
					$used_in_forms[]="adminhtml_customer";
					$attribute->setData("used_in_forms", $used_in_forms)
			                ->setData("is_used_for_customer_segment", true)
			                ->setData("is_system", 0)
			                ->setData("is_user_defined", 1)
			                ->setData("is_visible", 1)
			                ->setData("sort_order", 100);
			        $attribute->save();
				}
				else{
					$attribute = Mage::getSingleton("eav/config")->getAttribute("customer", $refAttributeCode);		
					$attribute->setData('frontend_label', $state->getName() . ' ' . $postData['name']);
					$attribute->setData('is_required', (boolean)$postData['is_required']);
					$attribute->save();
				};
				
                $info->addData($postData);
                $info->save();
                $this->_getSession()->addSuccess($this->__('The piece of info has been saved.'));

                // redirect to remove $_POST data from the request
                return $this->_redirect('dan_sca_admin/state_info/edit', array(
					'id' => $info->getId()
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

        // make the current state_info object available to blocks.
        Mage::register('current_stateInfo', $info);

        // Instantiate the form container.
        $stateInfoEditBlock = $this->getLayout()->createBlock('dan_sca_adminhtml/state_info_edit');

        // Add the form container as the only item on this page.
        $this->loadLayout()->_addContent($stateInfoEditBlock)->renderLayout();
    }

    public function deleteAction(){		
        $info = Mage::getModel('dan_sca/state_info');
		$state = Mage::getModel('dan_sca/state');

        if ($infoId = $this->getRequest()->getParam('id', false)){
			$info->load($infoId);
			$state->load($info->getParentId());
		};

        if (!$info->getId()){
			_getSession()->addError($this->__('This piece of info no longer exists.'));
            return $this->_redirect('dan_sca_admin/state_info/index');
		};
		
        try {
            $info->delete();
			
			$attribute = Mage::getSingleton("eav/config")->getAttribute("customer", $info->getAttributeCode());		
			$attribute->delete();
			
            $this->_getSession()->addSuccess($this->__('The piece of info has been deleted.'));
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
                    ->isAllowed('dan_sca/state_info');
                break;
        }

        return $isAllowed;
    }
}
?>