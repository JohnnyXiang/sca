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
}

?>