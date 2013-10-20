<?php

class page_adds_page_admin_index extends page_base_admin {
	function init(){
		parent::init();

		$crud = $this->add('CRUD');
		$crud->setModel('adds/AdBlock');
		$ad_crud = $crud->addRef('adds/Ad');
		if($ad_crud AND $ad_crud->form){
			if($ad_crud->isEditing('add')) $ad_crud->form->getElement('ad_image_id')->destroy();
		}
		

	}
}