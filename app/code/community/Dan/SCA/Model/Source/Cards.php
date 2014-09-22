<?php
class Dan_SCA_Model_Source_Cards extends Mage_Eav_Model_Entity_Attribute_Source_Abstract {
    public function getAllOptions(){

        $options = array(
            array(
                'label' => '',
                'value' => '',
            ),
        );
		
		$collection = array(
			'Visa' 				=> 'Visa',
			'MasterCard' 		=> 'MasterCard',
			'American Express' 	=> 'American Express',
			'Discover'			=> 'Discover',
			'Diners Club'		=> 'Diners Club',
			'JCB'				=> 'JCB'
		);
        
        foreach ($collection as $key => $val) {
            $options[] = array(
                'label' => $key,
                'value' => $val,
            );
        }
        
        return $options;
    }
}
?>