<?php

class page_history_page_index extends page_base_admin {
	function init(){
		parent::init();

		$tabs=$this->add('Tabs');
		$placetype_tab = $tabs->addTab('Place Type');
		$place_tab = $tabs->addTab('Manage Places');

		$placetype_crud=$placetype_tab->add('CRUD');
		$placetype_crud->setModel('history/PlaceType');

		$place__crud=$place_tab->add('CRUD');
		$place__crud->setModel('history/Place');

	}
}