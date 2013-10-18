<?php

class page_admin_modules_history_index extends page_base_admin {
	function init(){
		parent::init();

		$tabs=$this->add('Tabs');
		$placetype_tab = $tabs->addTab('Place Type');
		$place_tab = $tabs->addTab('Manage Places');

		$placetype_crud=$placetype_tab->add('CRUD');
		$placetype_crud->setModel('History_PlaceType');

		$place__crud=$place_tab->add('CRUD');
		$place__crud->setModel('History_Place');

	}
}