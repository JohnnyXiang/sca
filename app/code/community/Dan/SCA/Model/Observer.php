<?php

class Dan_SCA_Model_Observer{

	// observer function to check if the customer purchased a membership and change their customer group if they did
    public function upgradeMember(Varien_Event_Observer $observer){

		// load the just-placed order
		$order_id = $observer->getEvent()->getOrder()->getId();
        $order = Mage::getModel('sales/order')->load($order_id);

        $items = $order->getAllItems();
	    foreach($items as $_item){
	        $__item = $_item->getProduct()->getId();
			$_resource = $_item->getProduct()->getResource();
			$_sku = $_resource->getAttributeRawValue($__item, 'sku', Mage::app()->getStore());
			
			if($_sku == 'MEMBERSHIP'){
				
				// lookup the correct target group
				$targetGroup = Mage::getModel('customer/group');
				$targetGroup->load('Members', 'customer_group_code');
				
				// get the order's customer and set their group --> 'Members'
				$customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
			    $customer->setGroupId($targetGroup->getId());
			    $customer->save();
				break;
			};
	    };
    }
	
	public function addMemberGroupPrice(Varien_Event_Observer $observer){
		
		$product = $observer->getEvent()->getProduct();
		
		$targetGroup = Mage::getModel('customer/group');
		$targetGroup->load('Members', 'customer_group_code');
		
		$groupPricingData = array(array('website_id'=>0, 'cust_group'=>$targetGroup->getId(), 'price'=>0));
		$product->setData('group_price', $groupPricingData);
	}
}

?>