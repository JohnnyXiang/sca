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
						Mage::log('invalid customer update token provided');
						$jsonData = json_encode(array('data' => 'an invalid value was supplied'));
					};
				}
				else{
					Mage::log('no customer found using provided id');
					$jsonData = json_encode(array('data' => 'an invalid value was supplied'));
				};
			}
			else{
				Mage::log('no customer id provided');
				$jsonData = json_encode(array('data' => 'an invalid value was supplied'));
			};
		}
		else{
			Mage::log('update parameters not supplied');
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
	
	
	public function cronAction() {
		
		// get the first order that is marked as ready to process
		$orders = Mage::getModel('sales/order')
			->getCollection()
			->addFieldToFilter('status', array('eq' => 'ready'))
			->setOrder('entity_id', 'ASC')
			->setPageSize(1)
			->setCurPage(1);
		
		// call this in order to get the first element of the $orders collection
		$order = $orders->getFirstItem(); 

		// ... roll through the order's items
		$items = $order->getAllItems();
		$num = count($items);
		$_iterator = 0;
		
		$errors_us = false;
		$errors_them = false;
		$block = false;
	    foreach($items as $_i){
			
			$_iterator++;
			
			// if the the item's GetsProcessed value is NULL, then we haven't looked at the item yet
			if(is_null($_i->getScaGetsProcessed())){
				
		        $_product = $_i->getProduct();
				$_attributeSetId = $_product->getAttributeSetId();
							
				$model = Mage::getModel('eav/entity_attribute_set');
				$_attributeSet = $model->load($_attributeSetId);
				$_attributeSetName = $_attributeSet->getAttributeSetName();

				//  ... determine if it should be fed into the processing system (based on Attribute Set Type)
				if($_attributeSetName == 'Draw Entry')
					$_i->setScaGetsProcessed(true);
				else
					$_i->setScaGetsProcessed(false);
				
				$_i->save();
			};
			
			// if the item gets processed ...
			if($_i->getScaGetsProcessed()){
				// ... and it doesn't have a status, then we've reached the item we need to process
				if(is_null($_i->getScaStatus())){
					
					// feed into processing system
					// *** here
					
					$_i->setScaStatus('processing');
					$_i->save();
					$block = true;
				}
				else if($_i->getScaStatus() == 'processing'){
					// we've reached the currently-processing item; we can't go any futher until it finishes.
					break;
				}
				else if($_i->getScaStatus() == 'errors_us'){
					$errors_us = true;
				}
				else if($_i->getScaStatus() == 'errors_them'){
					$errors_them = true;
				};
			};
			
			// due to the BREAK in the preceeding if() statement (which would prevent getting here (and interrupt the foreach() if any part of the order was currently processing), we'll only reach this point if the entire order has been processed
			if($num == $_iterator && !$block){
				
				if($errors_us){
					$order->setStatus('errors_us');
				}
				else if($errors_them){
					$order->setStatus('errors_them');
				}
				else{
					
					// set order ==> complete ... Magento does not permit this manually unless the order has been invoiced & shipped (?)
					/*
					$order->setState('complete');
					$order->setStatus('complete');
					$history = $order->addStatusHistoryComment('This order has been processed successfully.', false);
					$history->setIsCustomerNotified(false);
					*/
				}
				$order->save();
			};
	    };
	}
}