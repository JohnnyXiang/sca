<?php

class Dan_SCA_Model_Observer{

	/*
	public function catalogProductLoadAfter(Varien_Event_Observer $observer){
	    // set the additional options on the product
	    $action = Mage::app()->getFrontController()->getAction();
	    if ($action->getFullActionName() == 'checkout_cart_add'){

        	$resource = Mage::getSingleton(‘core/resource’);
        	$readConnection = $resource->getConnection(‘core_read’);
        	$query = "";
        	$results = $readConnection->fetchAll($query);
			
	        if ($options = $action->getRequest()->getParam('extra_options')){
	            $product = $observer->getProduct();

	            // add to the additional options array
	            $additionalOptions = array();
	            if ($additionalOption = $product->getCustomOption('additional_options')){
	                $additionalOptions = (array) unserialize($additionalOption->getValue());
	            }
	            foreach ($options as $key => $value){
	                $additionalOptions[] = array(
	                    'label' => $key,
	                    'value' => $value,
	                );
	            }
	            // add the additional options array with the option code additional_options
	            $observer->getProduct()
	                ->addCustomOption('additional_options', serialize($additionalOptions));
	        }
	    }
	}

	public function salesConvertQuoteItemToOrderItem(Varien_Event_Observer $observer){
	    $quoteItem = $observer->getItem();
	    if ($additionalOptions = $quoteItem->getOptionByCode('additional_options')){
	        $orderItem = $observer->getOrderItem();
	        $options = $orderItem->getProductOptions();
	        $options['additional_options'] = unserialize($additionalOptions->getValue());
	        $orderItem->setProductOptions($options);
	    }
	}
	
}
	*/

?>