<?php
class Dan_SCA_Model_Source_Years extends Mage_Eav_Model_Entity_Attribute_Source_Abstract {
    public function getAllOptions(){

        $options = array(
            array(
                'label' => '',
                'value' => '',
            ),
        );
		
		$collection = array(
			'2015' => '2015',
			'2016' => '2016',
			'2017' => '2017',
			'2018' => '2018',
			'2019' => '2019',
			'2020' => '2020'
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