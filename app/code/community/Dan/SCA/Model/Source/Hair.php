<?php
class Dan_SCA_Model_Source_Hair extends Mage_Eav_Model_Entity_Attribute_Source_Abstract {
    public function getAllOptions(){

        $options = array(
            array(
                'label' => '',
                'value' => '',
            ),
        );
		
		$collection = array(
			'Bald' 		=> 'Bald',
			'Black'		=> 'Black',
			'Blond(e)'	=> 'Blond(e)',
			'Brown'		=> 'Brown',
			'Gray'		=> 'Gray',
			'Red'		=> 'Red'
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