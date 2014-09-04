<?php
class Dan_SCA_Model_Resource_Gameunit_Detail extends Mage_Core_Model_Resource_Db_Abstract {
    protected function _construct(){
        /**
         * Tell Magento the database name and primary key field to which to persist data. 
		 * Similar to the _construct() of our model, Magento finds
         * this data from config.xml by finding the <resourceModel/> node
         * and locating children of <entities/>.
         *
         * In this example:
         * - dan_sca is the model alias
         * - state is the entity referenced in config.xml
         * - entity_id is the name of the primary key column
         *
         * As a result, Magento will write data to the table
         * 'dan_sca_state' and any calls
         * to $model->getId() will retrieve the data from the
         * column named 'entity_id'.
         */
        $this->_init('dan_sca/gameunit_detail', 'entity_id');
    }
}
?>