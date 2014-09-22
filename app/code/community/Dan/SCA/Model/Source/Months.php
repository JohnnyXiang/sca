<?php
class Dan_SCA_Model_Source_Months extends Mage_Eav_Model_Entity_Attribute_Source_Abstract {
    public function getAllOptions(){

        $options = array(
            array(
                'label' => '',
                'value' => '',
            ),
        );
		
		$collection = array(
			'01' => '01',
			'02' => '02',
			'03' => '03',
			'04' => '04',
			'05' => '05',
			'06' => '06',
			'07' => '07',
			'08' => '08',
			'09' => '09',
			'10' => '10',
			'11' => '11',
			'12' => '12'
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