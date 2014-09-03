<?php
class Dan_SCA_Block_Adminhtml_State_Grid extends Mage_Adminhtml_Block_Widget_Grid {
    protected function _prepareCollection(){
        /* Tell Magento which collection to use to display in the grid. */
        $collection = Mage::getResourceModel('dan_sca/state_collection');
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    public function getRowUrl($row){
        /* When a grid row is clicked, this is to where the user should be redirected. */
        return $this->getUrl('dan_sca_admin/state/edit', array('id' => $row->getId()));
    }

    protected function _prepareColumns(){
        $this->addColumn('entity_id', array(
            'header' 	=> $this->_getHelper()->__('ID'),
            'type' 		=> 'number',
            'index' 	=> 'entity_id',
        ));

        $this->addColumn('name', array(
            'header' 	=> $this->_getHelper()->__('Name'),
			'width' 	=> '150px',
            'type' 		=> 'text',
            'index' 	=> 'name',
        ));
		
        $this->addColumn('abbreviation', array(
            'header' 	=> $this->_getHelper()->__('Abbreviation'),
			'width' 	=> '80px',
            'type' 		=> 'text',
            'index' 	=> 'abbreviation',
        ));
		
        $this->addColumn('requires_safety_card', array(
            'header' 	=> $this->_getHelper()->__('Requires Safety Card?'),
			'width' 	=> '200px',
			'type'    	=> 'options',
			'options' 	=> array(1 => 'Yes', 0 => 'No'),
            'index' 	=> 'requires_safety_card',
        ));
		
        $this->addColumn('convenience_fee', array(
            'header' 	=> $this->_getHelper()->__('Convenience Fee'),
            'type' 		=> 'price',
            'index' 	=> 'convenience_fee',
        ));

        $this->addColumn('url_key', array(
            'header' 	=> $this->_getHelper()->__('URL Key'),
            'type' 		=> 'text',
            'index' 	=> 'url_key',
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
                        'base' => 'dan_sca_admin' . '/state/edit',
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
}
?>