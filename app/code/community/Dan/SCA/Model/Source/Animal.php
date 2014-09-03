<?php
class Dan_SCA_Model_Source_Animal extends Mage_Eav_Model_Entity_Attribute_Source_Abstract {
    public function getAllOptions(){
        $animalCollection = Mage::getModel('dan_sca/animal')->getCollection()->setOrder('name', 'ASC');
        
        $options = array(
            array(
                'label' => '',
                'value' => '',
            ),
        );
        
        foreach ($animalCollection as $_animal) {
            $options[] = array(
                'label' => $_animal->getName(),
                'value' => $_animal->getId(),
            );
        }
        
        return $options;
    }
}
?>