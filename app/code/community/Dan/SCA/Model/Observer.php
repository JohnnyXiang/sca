<?php

class Dan_SCA_Model_Observer{

	// observer function to check if the customer purchased a membership and change their customer group if they did
	// * also handles general page prep for the success page
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
	}
	
	public function stuffResidence(Varien_Event_Observer $observer){
		if($state_id = Mage::getSingleton('customer/session')->getStateOfResidence()){
			$customer = $observer->getCustomer();
			$customer->setStateOfResidence($state_id);
			$customer->save();
		};
	}
	
	/* this cron reviews orders marked as 'Ready'
	*   - for each item in the order, determine if it needs to be fed into the dispatch system
	*   - if it does, feed it in
	*   - *** DO NOT set item status or overall order status in this function!
	*   - compare an Order's count(get_processed) versus the number of errors ==> change overall status 
	*/
	public function doAutomatedDispatch($observer) {
		
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
	    foreach($order->getAllItems() as $_i){

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
					$_i->setScaStatus('processing');
					$_i->save();
				}
				else if($_i->getScaStatus() == 'processing'){
					// we've reached the currently-processing item; we can't go any futher until it finishes.
					break;
				};
			};
	    };
	}
}

?>