<?php
class page_admin_master extends page_base_admin{
	function init(){
		parent::init();

		$tabs=$this->add('Tabs');


		$state_tab=$tabs->addTab('States');
		$state_crud=$state_tab->add('CRUD');
		$state_crud->setModel('State');

		$city_tabs=$tabs->addTab('City');
		$city_crud=$city_tabs->add('CRUD');
		$city_crud->setModel('City');
		if($city_crud->grid) $city_crud->grid->addPaginator(20);


		$tehsil_tabs=$tabs->addTab('Tehsil');
		$tehsil_crud=$tehsil_tabs->add('CRUD');
		$tehsil_crud->setModel('Tehsil');
		if($tehsil_crud->grid) $tehsil_crud->grid->addPaginator(20);

		$area_tabs=$tabs->addTab('Area');
		$area_crud=$area_tabs->add('CRUD');
		$area_crud->setModel('Area');
		$area_crud->add('Controller_ChainSelector',array('chain_fields'=>array('tehsil_id'=>'city_id')));
		if($area_crud->grid) $area_crud->grid->addPaginator(20);

	}
}