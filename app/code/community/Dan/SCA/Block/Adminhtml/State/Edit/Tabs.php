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
		
		
		
	// *** create a tab for each one of the current_state's gameunits
		
		// get all gameunits, filtering on Parent_ID == current_state's Entity_ID
		$collection = Mage::getResourceModel('dan_sca/gameunit_collection')
			->addFieldToFilter('parent_id', $this->_getHelper()->getState()->getData('entity_id'));
		
		$tab_num = 2;
		foreach($collection as $gameunit){
	        $this->addTab('form_section' . $tab_num, array(
	            'label' => $this->_getHelper()->__('-- ' . $gameunit['name']),
	            'title' => $this->_getHelper()->__('-- ' . $gameunit['name']),
	            'content' => '<p>This is where the administrator will manage Gameunit Details in a similar way as they manage Customer Addresses</p>'
					
					/**
					* I assume the approach here would be to have a block and template file to handle Gameunit Details
					*  in the same fashion as Customer Addresses are displayed.
					*  We'd then pass the selected Gameunit's entity_id to the the block/template via something like:
					*  setParent($gameunit['entity_id']), which would allow us to, inside the template, use getParent()
					*  and therefore retrieve the Gameunit_Details for the specified parent Gameunit.
					**/
					//$this->getLayout()->createBlock('dan_sca_adminhtml/state_edit_tab_gameunitdetails')->toHtml(),
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