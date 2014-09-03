<?php
class Dan_SCA_Model_Source_State extends Mage_Eav_Model_Entity_Attribute_Source_Abstract {
    public function getAllOptions(){
        $stateCollection = Mage::getModel('dan_sca/state')->getCollection()->setOrder('name', 'ASC');
        
        $options = array(
            array(
                'label' => '',
                'value' => '',
            ),
        );
        
        foreach ($stateCollection as $_state) {
            $options[] = array(
                'label' => $_state->getName(),
                'value' => $_state->getId(),
            );
        }
        
        return $options;
    }
}
?>