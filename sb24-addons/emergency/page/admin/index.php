<?php

class page_emergency_page_admin_index extends page_base_admin{
	function init(){
		parent::init();

		$tabs = $this->add('Tabs');
		$category_tab = $tabs->addTab('Category');
		$numbers_tab = $tabs->addTab('Numbers');

		$category_crud = $category_tab->add('CRUD');
		$category_crud->setModel('emergency/Category');

		$crud = $numbers_tab->add('CRUD');
		$crud->setModel('emergency/Listing');
		$crud->add('Controller_ChainSelector',array("chain_fields"=>array('city_id'=>'state_id','tehsil_id'=>'city_id')));

	}
}