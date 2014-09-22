<?php
class Dan_SCA_Block_Adminhtml_State_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {
 
    public function __construct() {
        parent::__construct();
        $this->setId('state_tabs');
        $this->setDestElementId('edit_form');
		
		// use the getState() function (below) to retrieve the current_state's name from the registry
        $this->setTitle(Mage::helper('dan_sca')->getState()->getData('name'));
    }

    protected function _beforeToHtml() {

        $this->addTab('form_section', array(
            'label' => Mage::helper('dan_sca')->__('State Details'),
            'title' => Mage::helper('dan_sca')->__('State Details'),
            'content' => $this->getLayout()->createBlock('dan_sca_adminhtml/state_edit_tab_form')->toHtml(),
        ));

        $this->addTab('form_section1', array(
            'label' => Mage::helper('dan_sca')->__('Manage Gameunits'),
            'title' => Mage::helper('dan_sca')->__('Manage Gameunits'),
			// *** NOTE: this will create a block with ALL database gameunits, but we only want the gameunits associated with the state --- perhaps pass the current_state Entity_ID via the URL and use it (in Gameunit controller to pull only gameunits with Parent_ID == the passed Entity_ID?)
            'content' => $this->getLayout()->createBlock('dan_sca_adminhtml/gameunit')->toHtml(),
        ));
		
        $this->addTab('form_section2', array(
            'label' => Mage::helper('dan_sca')->__('Manage Required Info'),
            'title' => Mage::helper('dan_sca')->__('Manage Required Info'),
            'content' => $this->getLayout()->createBlock('dan_sca_adminhtml/state_info')->toHtml(),
        ));

	// *** this creates a tab for each one of the current_state's gameunits
		
		// get all of the current_state's Gameunits (filter parent_ID == current_state's Entity_ID)
		$collection = Mage::getResourceModel('dan_sca/gameunit_collection')
			->addFieldToFilter('parent_id', $this->_getHelper()->getState()->getData('entity_id'));
		
		$tab_num = 3; // starting tab number for the Gameunit listing
		foreach($collection as $gameunit){
	        $this->addTab('form_section' . $tab_num, array(
	            'label' => $this->_getHelper()->__('-- ' . $gameunit->getName()),
	            'title' => $this->_getHelper()->__('-- ' . $gameunit->getName()),
				// 'class'	=> 'ajax',
				// 'url'	=> $this->getUrl('*/*/*', array('_current' => true))
	            
				// notice, I'm passing the current Gameunit's ID (we're inside a foreach()) to the initForm() function
				'content' => $this->getLayout()->createBlock('dan_sca_adminhtml/state_edit_tab_details')->initForm($gameunit->getId())->toHtml()	
	        ));
			
			$tab_num++;
		};

        return parent::_beforeToHtml();
    }
	
    protected function _getHelper(){
        return Mage::helper('dan_sca');
    }
}
?>