<?php

class Dan_SCA_Model_Observer{

	// observer function to check if the customer purchased a membership and change their customer group if they did
	// * also handles prep for the success page
    public function upgradeMember(Varien_Event_Observer $observer){

		// load the just-placed order and get its customer
		$order_id = $observer->getEvent()->getOrder()->getId();
        $order = Mage::getModel('sales/order')->load($order_id);
		$customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
		
		// set the one-time, post-checkout update token
		$customer->setUpdateToken(Mage::helper('dan_sca')->getSecretKey());
		
		// get all the order's items
        $items = $order->getAllItems();
	    foreach($items as $_item){
	        $__item = $_item->getProduct()->getId();
			$_resource = $_item->getProduct()->getResource();
			$_sku = $_resource->getAttributeRawValue($__item, 'sku', Mage::app()->getStore());
			
			// determine if any of the item's product's SKUs is the membership SKU
			if($_sku == 'MEMBERSHIP'){
				
				// lookup the correct target group
				$targetGroup = Mage::getModel('customer/group');
				$targetGroup->load('Members', 'customer_group_code');
				
				// set customer's group --> 'Members'
			    $customer->setGroupId($targetGroup->getId());
				$customer->setMembershipDate(date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time())));
			    
				Mage::getSingleton('core/session')->addSuccess("Welcome to the team, ". $customer->getFirstname() ."!");
				break;
			};
	    };
		$customer->save();
    }
	
	// automatically add member group price to any newly-created product
	public function addMemberGroupPrice(Varien_Event_Observer $observer){
		$product = $observer->getEvent()->getProduct();
		
		$targetGroup = Mage::getModel('customer/group');
		$targetGroup->load('Members', 'customer_group_code');
		
		$groupPricingData = array(array('website_id'=>0, 'cust_group'=>$targetGroup->getId(), 'price'=>0));
		$product->setData('group_price', $groupPricingData);
	}
	
	// called after session->clear() of the checkout action, this re-populates the order into the session
	public function repopulateSessionOrder(Varien_Event_Observer $observer){
		$order = Mage::getModel('sales/order')->load($observer->getEvent()->getOrderIds()[0]);
		Mage::getSingleton('customer/session')->setLastOrder($order);
		$customer = Mage::getModel('customer/customer')->load($order->getCustomerId());

		// prep for dispatch system
		$order->setState('processing');
		if($customer->getPoaOnFile())
			$order->setStatus('ready');
		else
			$order->setStatus('no_poa');
	
		$order->save();
	}
	
	
	public function stuffResidence(Varien_Event_Observer $observer){
		if($state_id = Mage::getSingleton('customer/session')->getStateOfResidence()){
			$customer = $observer->getCustomer();
			$customer->setStateOfResidence($state_id);
			$customer->save();
		};
	}
	
	// ** dispatched by adminhtml_customer_prepare_save
	// automatically set a customer's orders to 'ready' if/when their has_poa attribute is toggled to true
	public function makeReady(Varien_Event_Observer $observer){
		if($customer = $observer->getCustomer()){
			// if the PoaOnFile value has changed ...
			if($customer->getPoaOnFile() != $customer->getOrigData()['poa_on_file']){
				$firstpass = true;
				
				// if PoaOnFile is now TRUE
				if($customer->getPoaOnFile()){
					// get all of the customer's orders marked as 'no_poa'
					$orders = Mage::getModel("sales/order")->getCollection()
						->addAttributeToSelect('*')
						->addFieldToFilter('customer_id', $customer->getId())
						->addFieldToFilter('status', 'no_poa');
					
					// add status comment update
					foreach($orders as $_o){
						$_o->addStatusToHistory('ready', "Power of Attorney received; order moved to processing queue.", true);
						// only email the customer once (even if they have multiple orders)
						if($firstpass){
							$_o->sendOrderUpdateEmail(true, "Good news - we've received your Power of Attorney and should finish processing your order(s) shortly!");
							$firstpass = false;
						};
						$_o->save();
					}
				}
				// if PoaOnFile is now FALSE
				else{
					// get all of the customer's orders marked as 'ready'
					$orders = Mage::getModel("sales/order")->getCollection()
						->addAttributeToSelect('*')
						->addFieldToFilter('customer_id', $customer->getId())
						->addFieldToFilter('status', 'ready');
					
					// add status comment update
					foreach($orders as $_o){
						$_o->addStatusToHistory('no_poa', "Power of Attorney revoked; order removed from processing queue.", true);
						// only email the user once (even if they have multiple orders)
						if($firstpass){
							$_o->sendOrderUpdateEmail(true, "We've received your revocation of Power of Attorney and will not process any more of your order(s)!");
							$firstpass = false;
						};
						$_o->save();
					}
				}
			}
			// handle initial, manual entry of credit card, ssn, and driver's license
			if($customer->getFauxCc() != $customer->getOrigData()['faux_cc'] && $customer->getFauxSsn() != $customer->getOrigData()['faux_ssn'] && $customer->getFauxCcv() != $customer->getOrigData()['faux_ccv'] && $customer->getFauxDl() != $customer->getOrigData()['faux_dl']){
				
				// get new encryption keys
				$encryption_keys = Mage::helper('dan_sca')->getNewEncryptionKey($customer->getId());
				
				// build the sensitive data string
				$sensitive_data = 
					$customer->getFauxSsn() .'%'.
					$customer->getFauxCc() .'%'.
					$customer->getCcExpMo() .'%'.
					$customer->getCcExpYr() .'%'.
					$customer->getFauxCcv() .'%'.
					$customer->getCcType() .'%'.	
					$customer->getBirthdateDy() .'%'.
					$customer->getBirthdateMo() .'%'.
					$customer->getBirthdateYr() .'%'.
					$customer->getFauxDl();
			
				// encrypt the sensitive data string using the newly-obtained encryption keys
				$iv = mcrypt_create_iv(16, MCRYPT_RAND);
				$encrypted = openssl_encrypt($sensitive_data, "AES-256-CBC", $encryption_keys[1], 0, $iv);
				$encrypted_data = $iv.$encrypted;
				
				// set the now-encrypted data
				$customer->setSecuredData($encrypted_data);
				
				// re-set the faux attributes to non-sensitive, simple text
				$customer->setFauxCc('**** encrypted ****');
				$customer->setFauxCcv('**** encrypted ****');
				$customer->setFauxSsn('**** encrypted ****');
				$customer->setFauxDl('**** encrypted ****');
			}
		}
	}
	
	/* this cron reviews orders w/status == 'ready'
	*   - *** DO NOT adjust the item's status or overall order's status in this function!
	*   - compare an order's count(get_processed) versus the number of errors ==> change overall status 
	*/
	public function doAutomatedDispatch($observer) {
		
		// get the oldest order that has been marked as ready
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
					// we've reached the currently-processing item; we can't go any futher until it finishes, so:
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
					$order->addStatusToHistory('errors_us', "Error(s) occured with our automated system. Check each item for further details.", false);
				}
				else if($errors_them){
					$order->addStatusToHistory('errors_them', "Error(s) occured that require the customer's assistance.", true);
					$order->sendOrderUpdateEmail(true, "We need your assistance! An error occured while using some of the information you provided. Please login to your account for more details.");
				}
				else{
					try{
				        if(!$order->canInvoice())
				            Mage::throwException(Mage::helper('core')->__('Cannot create an invoice.'));

				        $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice();

				        if (!$invoice->getTotalQty())
							Mage::throwException(Mage::helper('core')->__('Cannot create an invoice without products.'));

				        $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
				        $invoice->register();
						$invoice->getOrder()->setCustomerNoteNotify(true);
						$invoice->sendEmail();
				        
						$transactionSave = Mage::getModel('core/resource_transaction')
							->addObject($invoice)
							->addObject($invoice->getOrder());
						$transactionSave->save();

						$order->addStatusHistoryComment('Order processed without errors.', false);
						$order->setState('complete');
						$order->setStatus('complete');
				    }       
				    catch (Mage_Core_Exception $e) {
						Mage::log('Exception: '. $e, null, 'auto-invoice.log', true);
				    }
				}
				$order->save();
			};
	    };
	}
}

?>