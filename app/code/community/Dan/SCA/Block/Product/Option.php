<?php
class Dan_SCA_Block_Product_Option extends Mage_Core_Block_Template {
    
	public function helper($helperName){
        return Mage::helper($helperName);
    }
	
    public function getProduct()
    {
        if (!Mage::registry('product') && $this->getProductId()) {
            $product = Mage::getModel('catalog/product')->load($this->getProductId());
            Mage::register('product', $product);
        }
        return Mage::registry('product');
    }
}
?>