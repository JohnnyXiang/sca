<?php
class Dan_SCA_Model_Source_Weapons extends Mage_Eav_Model_Entity_Attribute_Source_Abstract {
    public function getAllOptions(){

        $options = array(
            array(
                'label' => '',
                'value' => '',
            ),
        );
		
		$collection = array(
			'Archery' 			=> 'Archery',
			'Rifle' 			=> 'Rifle',
			'Muzzle Loader' 	=> 'Muzzle Loader'
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