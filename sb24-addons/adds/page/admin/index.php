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

		if($ad_crud AND $ad_crud->grid){
			$ad_crud->grid->addFormatter('ad_image','picture');
		}

		if($ad_crud) {
			$ad_crud->addClass('atk-box ui-widget-content');
			$ad_payment_crud = $ad_crud->addRef('adds/AdPayment');
			if($ad_payment_crud){
				$ad_payment_crud->addClass('atk-box ui-widget-content');
			}
		}
		

	}
}