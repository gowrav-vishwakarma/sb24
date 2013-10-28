<?php

class page_history_page_admin_index extends page_base_admin {
	function init(){
		parent::init();

		$tabs=$this->add('Tabs');
		$placetype_tab = $tabs->addTab('Place Type');
		$place_tab = $tabs->addTab('Manage Places');

		$placetype_crud=$placetype_tab->add('CRUD');
		$placetype_crud->setModel('history/PlaceType');

		$place_crud=$place_tab->add('CRUD');
		$place_crud->setModel('history/Place');
		$place_crud->add('Controller_ChainSelector',array('chain_fields'=>array('city_id'=>'state_id','area_id'=>'city_id')));

	}
}