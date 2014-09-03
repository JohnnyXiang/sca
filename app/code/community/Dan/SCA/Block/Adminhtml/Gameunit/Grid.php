<?php
class Dan_SCA_Block_Adminhtml_Gameunit_Grid extends Mage_Adminhtml_Block_Widget_Grid {
    protected function _prepareCollection(){
		
		// get Gameunit collection, filtering the Parent_ID column with the current_state's Entity_ID
        $collection = Mage::getResourceModel('dan_sca/gameunit_collection')
			->addFieldToFilter('parent_id', $this->_getHelper()->getState()->getData('entity_id'));
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    public function getRowUrl($row){
        /* When a grid row is clicked, this is to where the user should be redirected. */
        return $this->getUrl('dan_sca_admin/gameunit/edit', array('id' => $row->getId()));
    }

    protected function _prepareColumns(){
        $this->addColumn('name', array(
            'header' 	=> $this->_getHelper()->__('Name'),
			'width' 	=> '200px',
            'type' 		=> 'text',
            'index' 	=> 'name',
        ));
        $this->addColumn('url_key', array(
            'header' 	=> $this->_getHelper()->__('URL Key'),
			'width' 	=> '220px',
            'type' 		=> 'text',
            'index' 	=> 'url_key',
        ));
        $this->addColumn('members_only', array(
            'header' 	=> $this->_getHelper()->__('Members Only?'),
			'width' 	=> '100px',
			'type'    	=> 'options',
			'options' 	=> array(1 => 'Yes', 0 => 'No'),
            'index' 	=> 'members_only',
        ));

        /* Add an action column with an edit link. */
        $this->addColumn('action', array(
            'header' => $this->_getHelper()->__('Action'),
            'width' => '50px',
            'type' => 'action',
            'actions' => array(
                array(
                    'caption' => $this->_getHelper()->__('Edit'),
                    'url' => array(
                        'base' => 'dan_sca_admin' . '/gameunit/edit',
                    ),
                    'field' => 'id'
                ),
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'entity_id',
        ));

        return parent::_prepareColumns();
    }

    protected function _getHelper(){
        return Mage::helper('dan_sca');
    }
	
    protected function _getState(){
        if (!$this->hasData('state')) {
            // This will have been set in the controller.
            $state = Mage::registry('current_state');

            // Just in case the controller does not register the state.
            if (!$state instanceof Dan_SCA_Model_State) {
                $state = Mage::getModel('dan_sca/state');
            }

            $this->setData('state', $state);
        }

        return $this->getData('state');
    }
}
?>