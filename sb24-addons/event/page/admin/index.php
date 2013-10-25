<?php

class page_event_page_admin_index extends page_base_admin {
	function init(){
		parent::init();

		$tabs= $this->add('Tabs');

		$event_tab = $tabs->addTab('Events');
		$event_type_tab = $tabs->addTab('Event Types');

		$event_crud= $event_tab->add('CRUD');
		$event_crud->setModel('event/Listing');
		$event_crud->add('Controller_ChainSelector',array('chain_fields'=>array('city_id'=>'state_id')));
		$registration_crud = $event_crud->addRef('event/Registration');

		if($event_crud->grid){
			if($registration_crud AND $registration_crud->grid){
				$registration_crud->grid->addPaginator(50);
			}
			$event_crud->grid->addFormatter('event_picture','picture');
		}

		if($event_crud->form){
			if($event_crud->isEditing('add')) $event_crud->form->getElement('event_picture_id')->destroy();
		}

		$event_type_crud= $event_type_tab->add('CRUD');
		$event_type_crud->setModel('event/Type');
	}
}