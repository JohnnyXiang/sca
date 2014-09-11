<?php
class Dan_SCA_Model_Source_Eyes extends Mage_Eav_Model_Entity_Attribute_Source_Abstract {
    public function getAllOptions(){

        $options = array(
            array(
                'label' => '',
                'value' => '',
            ),
        );
		
		$collection = array(
			'Blue' 	=> 'Blue',
			'Brown' => 'Brown',
			'Gray' 	=> 'Gray',
			'Green' => 'Green',
			'Hazel'	=> 'Hazel'
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