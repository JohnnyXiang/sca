<?php
class Dan_SCA_Model_Source_Height extends Mage_Eav_Model_Entity_Attribute_Source_Abstract {
    public function getAllOptions(){

        $options = array(
            array(
                'label' => '',
                'value' => '',
            ),
        );
		
		//create the options for the height attribute (36 inches --> 84 inches)
		$_num = 36;
		while($_num < 85){
            $options[] = array(
                'label' => $_num,
                'value' => $_num,
            );
			$_num++;
		};
        
        return $options;
    }
}
?>